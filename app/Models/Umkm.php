<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'rating',
        'saldo',
        'metode_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
            'saldo' => 'decimal:2',
            'metode_pembayaran' => 'array',
        ];
    }

    /**
     * Mengecek apakah UMKM berstatus terverifikasi ('disetujui' atau boolean true).
     */
    public function isTerverifikasi(): bool
    {
        $val = strtolower((string) $this->status_verifikasi);

        return in_array($val, ['disetujui', 'true', '1'], true) || $this->status_verifikasi === true;
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

    /**
     * Scope UMKM yang sudah terverifikasi (mendukung nilai 'disetujui' maupun boolean).
     */
    public function scopeTerverifikasi(Builder $query): Builder
    {
        return $query->whereRaw("LOWER(CAST(status_verifikasi AS TEXT)) IN ('disetujui', 'true', '1')");
    }

    /**
     * Scope UMKM yang berstatus operasional Buka.
     */
    public function scopeBuka(Builder $query): Builder
    {
        return $query->whereRaw("LOWER(CAST(status_operasional AS TEXT)) IN ('buka', 'true', '1')");
    }
}