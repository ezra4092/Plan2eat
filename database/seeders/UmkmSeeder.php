<?php

namespace Database\Seeders;
use App\Models\Umkm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Umkm::create([
            'id_user' => 1, 
            'nama_vendor' => 'Warteg Sifren',
            'deskripsi_toko' => 'Menyediakan masakan rumahan enak, bergizi, dan ramah kantong mahasiswa.',
            'alamat_lengkap' => 'Jl. Babakan Raya, Bogor',
            'titik_koordinat_lokasi' => '-6.5593, 106.7262', 
            'jam_buka' => '07:00:00',
            'jam_tutup' => '20:00:00',
            'status_operasional' => 'Buka',
        ]);
    }
}
