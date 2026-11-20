<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_umkm',
        'jenis_menu',
        'nama_makanan',
        'deskripsi_makanan',
        'foto_menu',
        'kandungan_kalori',
        'stok_harian',
        'harga_normal',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kandungan_kalori' => 'integer',
            'stok_harian' => 'integer',
            'harga_normal' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'id_umkm', 'id_umkm');
    }

    public function jenisMenu(): BelongsTo
    {
        return $this->belongsTo(JenisMenu::class, 'jenis_menu', 'id');
    }

    public function flashSales(): HasMany
    {
        return $this->hasMany(Flashsale::class, 'id_menu', 'id_menu');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    public function detailMealPlan(): HasMany
    {
        return $this->hasMany(DetailMealPlan::class, 'id_menu', 'id_menu');
    }
}