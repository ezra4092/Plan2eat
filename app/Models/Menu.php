<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    // Matikan timestamps otomatis karena tidak ada di ERD
    public $timestamps = false;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_umkm',
        // id_plan dihapus dari sini karena sudah dipindah ke tabel detail_meal_plan
        'nama_makanan',
        'deskripsi_makanan',
        'foto_menu',
        'kategori_waktu',
        'kandungan_kalori',
        'stok_harian',
        'harga_normal',
    ];

    protected function casts(): array
    {
        return [
            'kandungan_kalori' => 'integer',
            'stok_harian' => 'integer',
            'harga_normal' => 'decimal:2',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'id_umkm', 'id_umkm');
    }

    public function flashSales(): HasMany
    {
        return $this->hasMany(Flashsale::class, 'id_menu', 'id_menu');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }
}