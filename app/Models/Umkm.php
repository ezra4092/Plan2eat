<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    use HasFactory;

    // Matikan timestamps otomatis karena tidak ada di ERD
    public $timestamps = false;

    protected $table = 'umkm';
    protected $primaryKey = 'id_umkm';

    protected $fillable = [
        'id_user',
        'nama_vendor',
        'deskripsi_toko',
        'alamat_lengkap',
        'foto_toko',
        'jam_buka',
        'jam_tutup',
        'status_operasional',
        'status_verifikasi',
        'catatan',
        'saldo',
        'metode_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:2',
            'metode_pembayaran' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_umkm', 'id_umkm');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_umkm', 'id_umkm');
    }
}