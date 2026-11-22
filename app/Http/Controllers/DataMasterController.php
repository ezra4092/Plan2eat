<?php

namespace App\Http\Controllers;

use App\Models\JenisMenu;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DataMasterController extends Controller
{
    /**
     * Menampilkan halaman Data Master (UMKM Terverifikasi, Data Pelanggan, dan CRUD Jenis Menu).
     */
    public function index(Request $request): View
    {
        $activeTab = (string) $request->query('tab', 'umkm');
        if (! in_array($activeTab, ['umkm', 'pelanggan', 'jenis_menu'], true)) {
            $activeTab = 'umkm';
        }

        $umkmTerverifikasi = Umkm::with(['user', 'menus.jenisMenu'])
            ->terverifikasi()
            ->orderByDesc('id_umkm')
            ->get();

        $pesananCountsByUmkm = DB::table('pesanan')
            ->select('id_umkm', DB::raw('COUNT(id_pesanan) as total'))
            ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
            ->groupBy('id_umkm')
            ->pluck('total', 'id_umkm');

        $ulasanStatsByUmkm = DB::table('ulasan')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->select(
                'pesanan.id_umkm',
                DB::raw('COUNT(ulasan.id_ulasan) as total'),
                DB::raw('COALESCE(AVG(ulasan.rating), 0) as avg_rating')
            )
            ->groupBy('pesanan.id_umkm')
            ->get()
            ->keyBy('id_umkm');

        foreach ($umkmTerverifikasi as $umkm) {
            $umkm->total_pesanan = (int) ($pesananCountsByUmkm[$umkm->id_umkm] ?? 0);
            $uStat = $ulasanStatsByUmkm->get($umkm->id_umkm);
            $umkm->total_ulasan = (int) ($uStat->total ?? 0);
            $umkm->rating_display = $umkm->total_ulasan > 0
                ? round((float) $uStat->avg_rating, 1)
                : round((float) ($umkm->rating ?? 0), 1);
        }

        $pelangganList = User::whereRaw("LOWER(CAST(role AS TEXT)) = 'pelanggan'")
            ->withCount('pesanan')
            ->orderByDesc('id_user')
            ->get();

        $jenisMenuList = JenisMenu::withCount('menus')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.data_master', compact(
            'activeTab',
            'umkmTerverifikasi',
            'pelangganList',
            'jenisMenuList'
        ));
    }

    /**
     * Menangguhkan atau mengaktifkan kembali akun UMKM terverifikasi.
     */
    public function toggleUmkmStatus(Request $request, int|string $id_umkm): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $umkm = Umkm::with('user')->findOrFail($id_umkm);
        $user = $umkm->user;

        if (! $user) {
            return redirect()
                ->route('admin.data_master', ['tab' => 'umkm'])
                ->with('error', 'Akun pemilik UMKM tidak ditemukan.');
        }

        $nextSuspended = ! (bool) ($user->is_suspended ?? false);

        DB::transaction(function () use ($user, $umkm, $nextSuspended, $validated): void {
            $user->is_suspended = $nextSuspended;
            $user->save();

            if ($nextSuspended && ! empty($validated['catatan'])) {
                $umkm->catatan = trim((string) $validated['catatan']);
                $umkm->status_operasional = 'Tutup';
            } elseif (! $nextSuspended) {
                $umkm->catatan = null;
            }
            $umkm->save();
        });

        $message = $nextSuspended
            ? "Mitra {$umkm->nama_vendor} berhasil ditangguhkan."
            : "Mitra {$umkm->nama_vendor} berhasil diaktifkan kembali.";

        return redirect()
            ->route('admin.data_master', ['tab' => 'umkm'])
            ->with('success', $message);
    }

    /**
     * Menangguhkan atau mengaktifkan kembali akun pelanggan.
     */
    public function togglePelangganSuspend(int|string $id_user): RedirectResponse
    {
        $pelanggan = User::whereRaw("LOWER(CAST(role AS TEXT)) = 'pelanggan'")
            ->findOrFail($id_user);

        $pelanggan->is_suspended = ! (bool) ($pelanggan->is_suspended ?? false);
        $pelanggan->save();

        $message = $pelanggan->is_suspended
            ? "Akun pelanggan {$pelanggan->nama_lengkap} berhasil ditangguhkan."
            : "Akun pelanggan {$pelanggan->nama_lengkap} berhasil diaktifkan kembali.";

        return redirect()
            ->route('admin.data_master', ['tab' => 'pelanggan'])
            ->with('success', $message);
    }

    /**
     * Menghapus akun pelanggan dari Data Master.
     */
    public function hapusPelanggan(int|string $id_user): RedirectResponse
    {
        $pelanggan = User::whereRaw("LOWER(CAST(role AS TEXT)) = 'pelanggan'")
            ->findOrFail($id_user);

        $nama = $pelanggan->nama_lengkap;
        $pelanggan->delete();

        return redirect()
            ->route('admin.data_master', ['tab' => 'pelanggan'])
            ->with('deleted', "Data pelanggan {$nama} berhasil dihapus permanen.");
    }

    /**
     * Menambahkan jenis menu baru.
     */
    public function storeJenisMenu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_menu,nama',
        ], [
            'nama.required' => 'Nama jenis menu wajib diisi.',
            'nama.unique' => 'Nama jenis menu sudah ada.',
        ]);

        JenisMenu::create([
            'nama' => trim((string) $validated['nama']),
        ]);

        return redirect()
            ->route('admin.data_master', ['tab' => 'jenis_menu'])
            ->with('success', 'Jenis menu baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui nama jenis menu.
     */
    public function updateJenisMenu(Request $request, int|string $id): RedirectResponse
    {
        $jenisMenu = JenisMenu::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_menu,nama,' . $jenisMenu->id . ',id',
        ], [
            'nama.required' => 'Nama jenis menu wajib diisi.',
            'nama.unique' => 'Nama jenis menu sudah digunakan.',
        ]);

        $jenisMenu->update([
            'nama' => trim((string) $validated['nama']),
        ]);

        return redirect()
            ->route('admin.data_master', ['tab' => 'jenis_menu'])
            ->with('success', 'Jenis menu berhasil diperbarui.');
    }

    /**
     * Menghapus jenis menu.
     */
    public function destroyJenisMenu(int|string $id): RedirectResponse
    {
        $jenisMenu = JenisMenu::findOrFail($id);
        $nama = $jenisMenu->nama;
        $jenisMenu->delete();

        return redirect()
            ->route('admin.data_master', ['tab' => 'jenis_menu'])
            ->with('deleted', "Jenis menu \"{$nama}\" berhasil dihapus.");
    }
}
