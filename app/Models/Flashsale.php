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
        'status_promo',
    ];

    protected function casts(): array
    {
        return [
            'tgl_flash_sale' => 'datetime',
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
}
