<?php

namespace Database\Seeders;
use App\Models\JenisMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisMenu::create([
            'nama' => 'Paket Makan 3 Hari',
        ]);

        // id: 2
        JenisMenu::create([
            'nama' => 'Paket Makan Ayam Praktis',
        ]);

        // id: 3
        JenisMenu::create([
            'nama' => 'Paket Diet Sehat Mingguan',
        ]);
    }
}
