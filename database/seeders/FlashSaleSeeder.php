<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->toDateString();

        // 1. Dari UMKM 1 (Warteg Bahari Sifren) - Menu 1: Nasi Ayam Penyet (Harga normal 17.000)
        FlashSale::create([
            'id_menu'        => 1,
            'kuantitas'      => 20,
            'stok_sisa'      => 5, // Sisa <= 5 untuk memicu badge "STOK TERBATAS"
            'tgl_flash_sale' => $today,
            'waktu_mulai'    => '20:00:00',
            'waktu_selesai'  => '22:00:00',
            'harga_diskon'   => 9500.00,
        ]);

        // 2. Dari UMKM 2 (Dapur Sehat Pak Budi) - Menu 8: Salmon Teriyaki Bowl Quinoa (Harga normal 42.000)
        FlashSale::create([
            'id_menu'        => 8,
            'kuantitas'      => 15,
            'stok_sisa'      => 3,
            'tgl_flash_sale' => $today,
            'waktu_mulai'    => '20:00:00',
            'waktu_selesai'  => '22:00:00',
            'harga_diskon'   => 21000.00,
        ]);

        // 3. Dari UMKM 2 (Dapur Sehat Pak Budi) - Menu 7: Nasi Merah Dada Ayam Panggang Rosemary (Harga normal 28.000)
        FlashSale::create([
            'id_menu'        => 7,
            'kuantitas'      => 25,
            'stok_sisa'      => 11,
            'tgl_flash_sale' => $today,
            'waktu_mulai'    => '20:00:00',
            'waktu_selesai'  => '22:00:00',
            'harga_diskon'   => 15000.00,
        ]);

        // 4. Dari UMKM 3 (Ayam Geprek & Penyet Bu Siti) - Menu 13: Nasi Ayam Geprek Sambal Korek (Harga normal 16.000)
        FlashSale::create([
            'id_menu'        => 13,
            'kuantitas'      => 30,
            'stok_sisa'      => 12,
            'tgl_flash_sale' => $today,
            'waktu_mulai'    => '20:00:00',
            'waktu_selesai'  => '22:00:00',
            'harga_diskon'   => 8500.00,
        ]);

        // 5. Dari UMKM 4 (Salad & Juice Corner Mas Dimas) - Menu 18: Caesar Salad Ayam Panggang (Harga normal 24.000)
        FlashSale::create([
            'id_menu'        => 18,
            'kuantitas'      => 18,
            'stok_sisa'      => 4,
            'tgl_flash_sale' => $today,
            'waktu_mulai'    => '20:00:00',
            'waktu_selesai'  => '22:00:00',
            'harga_diskon'   => 12000.00,
        ]);
    }
}