<?php

namespace Database\Seeders;

use App\Models\Umkm;
use Illuminate\Database\Seeder;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // UMKM 1 (id_user = 3)
        Umkm::create([
            'id_user' => 3, 
            'nama_vendor' => 'Warteg Bahari Sifren',
            'deskripsi_toko' => 'Menyediakan aneka masakan rumahan lezat, higienis, dan ramah di kantong mahasiswa.',
            'alamat_lengkap' => 'Jl. Babakan Raya No. 12, Dramaga, Bogor',
            'jam_buka' => '07:00:00',
            'jam_tutup' => '21:00:00',
            'status_operasional' => 'Buka',
            'saldo' => 500000.00,
            'metode_pembayaran' => 'QRIS, Tunai, Transfer Bank',
        ]);

        // UMKM 2 (id_user = 4)
        Umkm::create([
            'id_user' => 4, 
            'nama_vendor' => 'Dapur Sehat Pak Budi',
            'deskripsi_toko' => 'Katering harian dengan menu rendah kalori, tinggi protein, cocok untuk diet dan pola hidup sehat.',
            'alamat_lengkap' => 'Jl. Bara 6 No. 45, Dramaga, Bogor',
            'jam_buka' => '08:00:00',
            'jam_tutup' => '19:00:00',
            'status_operasional' => 'Buka',
            'saldo' => 750000.00,
            'metode_pembayaran' => 'QRIS, Transfer Bank',
        ]);

        // UMKM 3 (id_user = 5)
        Umkm::create([
            'id_user' => 5, 
            'nama_vendor' => 'Ayam Geprek & Penyet Bu Siti',
            'deskripsi_toko' => 'Spesialis ayam geprek sambal korek dan ayam penyet lalapan komplit dengan pilihan level pedas.',
            'alamat_lengkap' => 'Jl. Babakan Tengah No. 88, Dramaga, Bogor',
            'jam_buka' => '10:00:00',
            'jam_tutup' => '22:00:00',
            'status_operasional' => 'Buka',
            'saldo' => 300000.00,
            'metode_pembayaran' => 'QRIS, Tunai',
        ]);

        // UMKM 4 (id_user = 6)
        Umkm::create([
            'id_user' => 6, 
            'nama_vendor' => 'Salad & Juice Corner Mas Dimas',
            'deskripsi_toko' => 'Aneka salad buah, salad sayur segar, dan cold-pressed juice tanpa gula tambahan.',
            'alamat_lengkap' => 'Ruko Permata Dramaga Blok B3, Bogor',
            'jam_buka' => '09:00:00',
            'jam_tutup' => '20:00:00',
            'status_operasional' => 'Buka',
            'saldo' => 450000.00,
            'metode_pembayaran' => 'QRIS, Tunai, Transfer Bank',
        ]);
    }
}
