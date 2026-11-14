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
            'id_user' => 3, 
            'nama_vendor' => 'Warteg Sifren',
            'deskripsi_toko' => 'Menyediakan masakan rumahan enak, bergizi, dan ramah kantong mahasiswa.',
            'alamat_lengkap' => 'Jl. Babakan Raya, Bogor',
            'jam_buka' => '07:00:00',
            'jam_tutup' => '20:00:00',
            'status_operasional' => 'Buka'
        ]);
    }
}
