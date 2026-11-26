<?php

namespace Tests\Feature;

use App\Models\JenisMenu;
use App\Models\Menu;
use App\Models\Umkm;
use App\Models\User;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmkmSuspensionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Membuat data dasar Admin, Pelanggan, UMKM terverifikasi, dan Menu aktif untuk pengujian.
     *
     * @return array{admin: User, pelanggan: User, owner: User, umkm: Umkm, menu: Menu}
     */
    private function seedStorefrontFixture(): array
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Test',
            'email' => 'admin@test.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $pelanggan = User::create([
            'nama_lengkap' => 'Pelanggan Test',
            'email' => 'pelanggan@test.local',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'saldo' => 200000,
        ]);

        $owner = User::create([
            'nama_lengkap' => 'Pemilik Dapur Test',
            'email' => 'umkm@test.local',
            'password' => bcrypt('password'),
            'role' => 'umkm',
        ]);

        $umkm = Umkm::create([
            'id_user' => $owner->id_user,
            'nama_vendor' => 'Dapur Uji Sehat',
            'deskripsi_toko' => 'Katering sehat harian',
            'alamat_lengkap' => 'Jl. Merdeka No. 1',
            'jam_buka' => '07:00',
            'jam_tutup' => '21:00',
            'status_operasional' => 'Buka',
            'status_verifikasi' => 'disetujui',
        ]);

        $jenis = JenisMenu::create([
            'nama' => 'Paket Harian',
        ]);

        $menu = Menu::create([
            'id_umkm' => $umkm->id_umkm,
            'jenis_menu' => $jenis->id,
            'nama_makanan' => 'Ayam Panggang Sehat Test',
            'deskripsi_makanan' => 'Tinggi protein',
            'kandungan_kalori' => 420,
            'stok_harian' => 15,
            'harga_normal' => 25000,
            'status' => true,
        ]);

        return compact('admin', 'pelanggan', 'owner', 'umkm', 'menu');
    }

    public function test_admin_suspends_umkm_for_three_days_and_blocks_selling_and_customer_orders(): void
    {
        $now = Carbon::create(2026, 11, 26, 12, 0, 0, 'Asia/Jakarta');
        Carbon::setTestNow($now);

        $fixture = $this->seedStorefrontFixture();
        $admin = $fixture['admin'];
        $pelanggan = $fixture['pelanggan'];
        $owner = $fixture['owner'];
        $umkm = $fixture['umkm'];
        $menu = $fixture['menu'];

        // 1. Admin menangguhkan UMKM tanpa mengisi catatan alasan (sebelumnya bug tidak menutup toko)
        $this->actingAs($admin)
            ->patch(route('admin.data_master.umkm_status', $umkm->id_umkm), [])
            ->assertRedirect(route('admin.data_master', ['tab' => 'umkm']));

        $owner->refresh();
        $umkm->refresh();

        $this->assertTrue((bool) $owner->is_suspended);
        $this->assertNotNull($owner->suspended_until);
        $this->assertSame(
            $now->copy()->addDays(3)->format('Y-m-d H:i:s'),
            Carbon::parse($owner->suspended_until)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        );
        $this->assertSame('Tutup', $umkm->status_operasional);

        // 1b. Pemilik UMKM yang ditangguhkan tetap bisa login (termasuk beda kapitalisasi email) & melihat banner peringatan
        $this->post(route('login'), [
            'email' => strtoupper($owner->email),
            'password' => 'password',
        ])->assertRedirect(route('umkm.dashboard'));

        $this->actingAs($owner)
            ->get(route('umkm.dashboard'))
            ->assertOk()
            ->assertSee('Akun Nonaktif / Ditangguhkan')
            ->assertSee('Peringatan: Akun UMKM Anda Sedang Ditangguhkan');

        // 2. UMKM tidak dapat membuka toko, menambah menu, atau mengubah status menu selama masa penangguhan
        $this->actingAs($owner)
            ->patchJson(route('umkm.profil-status', $umkm->id_umkm), ['status_operasional' => 'Buka'])
            ->assertStatus(403)
            ->assertJson(['success' => false]);

        $this->actingAs($owner)
            ->post(route('umkm.menu-create'), [
                'nama_makanan' => 'Menu Terlarang Saat Suspend',
                'harga_normal' => 20000,
                'stok_harian' => 10,
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('menu', ['nama_makanan' => 'Menu Terlarang Saat Suspend']);

        $this->actingAs($owner)
            ->patchJson(route('umkm.menu-stok', $menu->id_menu), ['status' => false])
            ->assertStatus(403)
            ->assertJson(['success' => false]);

        // 3. Tampilan pelanggan: menu tidak muncul di beranda, mitra menampilkan info Toko Tutup, dan halaman menu menampilkan Toko Sedang Tutup
        $this->actingAs($pelanggan)
            ->get(route('pelanggan.beranda'))
            ->assertOk()
            ->assertDontSee('Ayam Panggang Sehat Test');

        $this->actingAs($pelanggan)
            ->get(route('pelanggan.mitra'))
            ->assertOk()
            ->assertSee('Toko Tutup')
            ->assertSee('Toko Sedang Tutup');

        $this->actingAs($pelanggan)
            ->get(route('pelanggan.menu', ['id_umkm' => $umkm->id_umkm]))
            ->assertOk()
            ->assertSee('Toko Sedang Tutup')
            ->assertDontSee('Ayam Panggang Sehat Test');

        $this->actingAs($pelanggan)
            ->get(route('pelanggan.keranjang', ['menu' => $menu->id_menu]))
            ->assertRedirect(route('pelanggan.mitra'))
            ->assertSessionHas('error');

        // 4. OrderService langsung menolak pembuatan pesanan ke UMKM yang ditangguhkan
        $this->expectException(Exception::class);
        app(OrderService::class)->buatPesanan($pelanggan->id_user, [
            'id_umkm' => $umkm->id_umkm,
            'metode_pembayaran' => 'Saldo',
            'metode_pengiriman' => 'Ambil Sendiri',
            'items' => [
                ['id_menu' => $menu->id_menu, 'kuantitas' => 1, 'waktu' => '12:00'],
            ],
        ]);
    }

    public function test_umkm_suspension_automatically_expires_after_three_days(): void
    {
        $now = Carbon::create(2026, 11, 26, 12, 0, 0, 'Asia/Jakarta');
        Carbon::setTestNow($now);

        $fixture = $this->seedStorefrontFixture();
        $owner = $fixture['owner'];
        $umkm = $fixture['umkm'];
        $pelanggan = $fixture['pelanggan'];

        $owner->suspendForDays(3, 'Pelanggaran sementara');
        $umkm->refresh();
        $this->assertSame('Tutup', $umkm->status_operasional);

        // Maju waktu melewati 3 hari
        Carbon::setTestNow($now->copy()->addDays(3)->addMinute());

        $owner->refresh();
        $this->assertFalse($owner->isCurrentlySuspended());

        $umkm->refresh();
        $this->assertSame('Buka', $umkm->status_operasional);

        $this->actingAs($pelanggan)
            ->get(route('pelanggan.beranda'))
            ->assertOk()
            ->assertSee('Ayam Panggang Sehat Test');
    }
}
