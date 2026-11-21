<?php

namespace Database\Seeders;

use App\Models\Flashsale;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now('Asia/Jakarta')->toDateString();

        // Bersihkan Flash Sale hari ini lalu generate ulang 5 menu non-paketan random (diskon 20% - 30%)
        Flashsale::whereDate('tgl_flash_sale', $today)->delete();
        Flashsale::ensureDailyFlashSales($today);
    }
}
