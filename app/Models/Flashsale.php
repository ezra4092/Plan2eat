<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flashsale extends Model
{
    use HasFactory;

    protected $table = 'flash_sale';
    protected $primaryKey = 'id_flash_sale';
    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'tgl_flash_sale',
        'waktu_mulai',
        'waktu_selesai',
        'kuantitas',
        'stok_sisa',
        'harga_diskon',
    ];

    protected function casts(): array
    {
        return [
            'tgl_flash_sale' => 'date',
            'kuantitas' => 'integer',
            'stok_sisa' => 'integer',
            'harga_diskon' => 'decimal:2',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_flash_sale', 'id_flash_sale');
    }

    /**
     * Menghitung harga diskon acak di rentang 20% hingga 30% dari harga normal.
     */
    public static function calculateRandomDiscountPrice(float $hargaNormal): float
    {
        $persenDiskon = random_int(20, 30);
        $rawDiskon = $hargaNormal * ((100 - $persenDiskon) / 100);

        return (float) max(1000, (int) (round($rawDiskon / 100) * 100));
    }

    /**
     * Memastikan terdapat maksimal 5 menu Flash Sale harian (khusus menu satuan / bukan paketan)
     * dengan potongan harga acak antara 20% - 30%.
     */
    public static function ensureDailyFlashSales(?string $dateStr = null): void
    {
        $targetDate = $dateStr ?: now('Asia/Jakarta')->toDateString();

        $alreadyExists = self::whereDate('tgl_flash_sale', $targetDate)
            ->where('stok_sisa', '>', 0)
            ->whereHas('menu', function ($q): void {
                $q->whereNull('jenis_menu')
                    ->where('status', true)
                    ->where('stok_harian', '>', 0);
            })
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $candidateMenus = Menu::whereNull('jenis_menu')
            ->where('status', true)
            ->where('stok_harian', '>', 0)
            ->whereRaw("LOWER(nama_makanan) NOT LIKE '%paket%'")
            ->whereRaw("LOWER(nama_makanan) NOT LIKE '%box%'")
            ->whereRaw("LOWER(nama_makanan) NOT LIKE '%plan%'")
            ->whereHas('umkm', function ($q): void {
                $q->terverifikasi()->buka();
            })
            ->get();

        if ($candidateMenus->isEmpty()) {
            return;
        }

        $selectedMenus = $candidateMenus->shuffle()->take(5);

        foreach ($selectedMenus as $menu) {
            $kuantitas = min((int) $menu->stok_harian, random_int(10, 20));
            $stokSisa = min($kuantitas, random_int(3, 12));

            self::create([
                'id_menu'        => $menu->id_menu,
                'kuantitas'      => max(1, $kuantitas),
                'stok_sisa'      => max(1, $stokSisa),
                'tgl_flash_sale' => $targetDate,
                'waktu_mulai'    => '20:00:00',
                'waktu_selesai'  => '22:00:00',
                'harga_diskon'   => self::calculateRandomDiscountPrice((float) $menu->harga_normal),
            ]);
        }
    }
}
