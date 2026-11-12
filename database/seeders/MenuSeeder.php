<?php

namespace Database\Seeders;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Nasi Ayam Penyet Sambal Bawang',
            'deskripsi_makanan' => 'Nasi hangat dengan ayam penyet, lalapan segar, dan sambal bawang. Cocok untuk makan siang di sela jadwal kuliah.',
            'kandungan_kalori' => 650,
            'stok_harian' => 50,
            'harga_normal' => 15000.00,
            'kategori_waktu' => 'Makan Siang',
            'foto_menu' => null, // Bisa diisi URL atau path gambar nanti
        ]);

        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Omelet Sayur Telur Spesial',
            'deskripsi_makanan' => 'Telur dadar campur irisan wortel, kol, dan bumbu rahasia. Sarapan bergizi dan praktis.',
            'kandungan_kalori' => 350,
            'stok_harian' => 30,
            'harga_normal' => 12000.00,
            'kategori_waktu' => 'Sarapan',
            'foto_menu' => null,
        ]);

        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Nasi Gila Mahasiswa',
            'deskripsi_makanan' => 'Nasi putih dengan tumis sosis, bakso, telur, dan sayur pedas manis untuk porsi makan malam.',
            'kandungan_kalori' => 720,
            'stok_harian' => 40,
            'harga_normal' => 16000.00,
            'kategori_waktu' => 'Makan Malam',
            'foto_menu' => null,
        ]);
    }
}
