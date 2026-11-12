<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Admin Plan2Eat',
            'email' => 'admin@plan2eat.com',
            'password' => 'password123', // Otomatis di-hash oleh Laravel
            'role' => 'admin',
        ]);

        // Membuat akun Mahasiswa/Pelanggan
        User::create([
            'nama_lengkap' => 'Ezra Faira',
            'email' => 'pelanggan@plan2eat.com',
            'password' => 'password123',
            'role' => 'pelanggan',
        ]);

        // Membuat akun Pemilik UMKM
        User::create([
            'nama_lengkap' => 'Ibu Sifren',
            'email' => 'umkm@plan2eat.com',
            'password' => 'password123',
            'role' => 'umkm',
        ]);
    }
}
