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
            'email' => 'admin@plan2eat.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // Membuat akun Mahasiswa/Pelanggan
        User::create([
            'nama_lengkap' => 'Ezra Faira',
            'email' => 'pelanggan@plan2eat.test',
            'password' => 'password123',
            'role' => 'pelanggan',
        ]);

        // Membuat akun Pemilik UMKM
        User::create([
            'nama_lengkap' => 'Ibu Sifren',
            'email' => 'umkm@plan2eat.test',
            'password' => 'password123',
            'role' => 'umkm',
        ]);

        User::create([
            'nama_lengkap' => 'Pak Budi',
            'email' => 'budi@plan2eat.test',
            'password' => 'password123',
            'role' => 'umkm',
        ]);

        User::create([
            'nama_lengkap' => 'Bu Siti',
            'email' => 'siti@plan2eat.test',
            'password' => 'password123',
            'role' => 'umkm',
        ]);

        User::create([
            'nama_lengkap' => 'Mas Dimas',
            'email' => 'dimas@plan2eat.test',
            'password' => 'password123',
            'role' => 'umkm',
        ]);
    }
}
