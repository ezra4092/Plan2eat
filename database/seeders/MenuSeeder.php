<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // UMKM 1: Warteg Bahari Sifren (id_umkm: 1) - 4 Menu
        // -------------------------------------------------------------
        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Nasi Ayam Penyet Sambal Bawang',
            'deskripsi_makanan' => 'Nasi hangat dengan ayam penyet garing, lalapan timun kemangi, dan sambal bawang pedas nikmat.',
            'kandungan_kalori' => 620,
            'stok_harian' => 45,
            'harga_normal' => 17000.00,
            'foto_menu' => 'menu/nasi-ayam-penyet.jpg',
        ]);

        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Omelet Sayur Telur Spesial',
            'deskripsi_makanan' => 'Telur dadar tebal bertabur wortel, daun bawang, dan bumbu rempah gurih khas rumahan.',
            'kandungan_kalori' => 310,
            'stok_harian' => 35,
            'harga_normal' => 10000.00,
            'foto_menu' => 'menu/omelet-sayur.jpg',
        ]);

        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Nasi Orek Tempe & Tumis Buncis',
            'deskripsi_makanan' => 'Paket hemat nasi pulen disajikan dengan orek tempe manis gurih dan tumis buncis jagung manis.',
            'kandungan_kalori' => 480,
            'stok_harian' => 50,
            'harga_normal' => 12000.00,
            'foto_menu' => 'menu/nasi-orek-tempe.jpg',
        ]);

        Menu::create([
            'id_umkm' => 1,
            'nama_makanan' => 'Soto Ayam Kuah Bening',
            'deskripsi_makanan' => 'Soto ayam suwir kuah kaldu segar dengan soun, tauge, kol, dan taburan seledri bawang goreng.',
            'kandungan_kalori' => 380,
            'stok_harian' => 30,
            'harga_normal' => 15000.00,
            'foto_menu' => 'menu/soto-ayam.jpg',
        ]);

        // -------------------------------------------------------------
        // UMKM 2: Dapur Sehat Pak Budi (id_umkm: 2) - 4 Menu
        // -------------------------------------------------------------
        Menu::create([
            'id_umkm' => 2,
            'nama_makanan' => 'Nasi Merah Dada Ayam Panggang Rosemary',
            'deskripsi_makanan' => 'Dada ayam tanpa kulit dipanggang dengan rempah rosemary, disajikan dengan nasi merah dan brokoli rebus.',
            'kandungan_kalori' => 420,
            'stok_harian' => 25,
            'harga_normal' => 28000.00,
            'foto_menu' => 'menu/dada-ayam-rosemary.jpg',
        ]);

        Menu::create([
            'id_umkm' => 2,
            'nama_makanan' => 'Salmon Teriyaki Bowl Quinoa',
            'deskripsi_makanan' => 'Potongan salmon segar saus teriyaki rendah sodium di atas quinoa organik dan edamame rebus.',
            'kandungan_kalori' => 510,
            'stok_harian' => 20,
            'harga_normal' => 42000.00,
            'foto_menu' => 'menu/salmon-teriyaki-bowl.jpg',
        ]);

        Menu::create([
            'id_umkm' => 2,
            'nama_makanan' => 'Tumis Tahu Jamur Tiram Saus Tiram Diet',
            'deskripsi_makanan' => 'Tahu organik dan jamur tiram ditumis minim minyak dengan saus tiram rendah garam.',
            'kandungan_kalori' => 230,
            'stok_harian' => 30,
            'harga_normal' => 16000.00,
            'foto_menu' => 'menu/tumis-tahu-jamur.jpg',
        ]);

        Menu::create([
            'id_umkm' => 2,
            'nama_makanan' => 'Sup Kacang Merah Daging Sapi Lembut',
            'deskripsi_makanan' => 'Sup kacang merah bergizi tinggi dipadu potongan daging sapi empuk rendah lemak.',
            'kandungan_kalori' => 390,
            'stok_harian' => 25,
            'harga_normal' => 25000.00,
            'foto_menu' => 'menu/sup-kacang-merah.jpg',
        ]);

        // -------------------------------------------------------------
        // UMKM 3: Ayam Geprek & Penyet Bu Siti (id_umkm: 3) - 4 Menu
        // -------------------------------------------------------------
        Menu::create([
            'id_umkm' => 3,
            'nama_makanan' => 'Nasi Ayam Geprek Sambal Korek Level 3',
            'deskripsi_makanan' => 'Ayam goreng tepung renyah digeprek dengan cabai rawit pedas mantap plus nasi putih hangat.',
            'kandungan_kalori' => 690,
            'stok_harian' => 60,
            'harga_normal' => 16000.00,
            'foto_menu' => 'menu/ayam-geprek-korek.jpg',
        ]);

        Menu::create([
            'id_umkm' => 3,
            'nama_makanan' => 'Paket Ayam Geprek Keju Mozarella',
            'deskripsi_makanan' => 'Ayam krispi gurih dengan lelehan keju mozzarella dan sambal bakar pedas gurih.',
            'kandungan_kalori' => 780,
            'stok_harian' => 40,
            'harga_normal' => 22000.00,
            'foto_menu' => 'menu/ayam-geprek-mozarella.jpg',
        ]);

        Menu::create([
            'id_umkm' => 3,
            'nama_makanan' => 'Bebek Goreng Sambal Ijo Gurih',
            'deskripsi_makanan' => 'Potongan bebek ungkep empuk berbumbu gurih digoreng garing dengan taburan sambal ijo khas Padang.',
            'kandungan_kalori' => 740,
            'stok_harian' => 25,
            'harga_normal' => 27000.00,
            'foto_menu' => 'menu/bebek-goreng-ijo.jpg',
        ]);

        Menu::create([
            'id_umkm' => 3,
            'nama_makanan' => 'Tahu Tempe Goreng Krispi & Sambal Terasi',
            'deskripsi_makanan' => 'Camilan pendamping tahu dan tempe goreng renyah dilengkapi cocolan sambal terasi teruji.',
            'kandungan_kalori' => 260,
            'stok_harian' => 50,
            'harga_normal' => 8000.00,
            'foto_menu' => 'menu/tahu-tempe-krispi.jpg',
        ]);

        // -------------------------------------------------------------
        // UMKM 4: Salad & Juice Corner Mas Dimas (id_umkm: 4) - 3 Menu
        // -------------------------------------------------------------
        Menu::create([
            'id_umkm' => 4,
            'nama_makanan' => 'Caesar Salad Ayam Panggang',
            'deskripsi_makanan' => 'Daun selada romaine segar, irisan dada ayam panggang, crouton renyah, dan saus caesar creamy.',
            'kandungan_kalori' => 350,
            'stok_harian' => 30,
            'harga_normal' => 24000.00,
            'foto_menu' => 'menu/caesar-salad.jpg',
        ]);

        Menu::create([
            'id_umkm' => 4,
            'nama_makanan' => 'Salad Buah Segar Dressing Yogurt Madu',
            'deskripsi_makanan' => 'Kombinasi potongan apel, melon, anggur, dan nata de coco berbalut yogurt plain dan madu alami.',
            'kandungan_kalori' => 270,
            'stok_harian' => 35,
            'harga_normal' => 18000.00,
            'foto_menu' => 'menu/salad-buah-yogurt.jpg',
        ]);

        Menu::create([
            'id_umkm' => 4,
            'nama_makanan' => 'Cold Pressed Juice Green Detox Booster',
            'deskripsi_makanan' => 'Jus murni perasan bayam jepang, apel hijau, nanas madu, dan lemon tanpa tambahan air atau gula.',
            'kandungan_kalori' => 140,
            'stok_harian' => 40,
            'harga_normal' => 20000.00,
            'foto_menu' => 'menu/green-detox-juice.jpg',
        ]);
    }
}
