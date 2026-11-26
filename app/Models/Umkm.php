<?php

namespace App\Models;

use Carbon\Carbon;
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
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
            'saldo' => 'decimal:2',
        ];
    }

    /**
     * Mengecek apakah UMKM berstatus terverifikasi ('disetujui').
     */
    public function isTerverifikasi(): bool
    {
        return strtolower((string) $this->status_verifikasi) === 'disetujui';
    }

    /**
     * Mengecek apakah akun pemilik UMKM sedang dalam masa penangguhan aktif.
     */
    public function isDitangguhkan(): bool
    {
        $owner = $this->relationLoaded('user') ? $this->user : $this->user()->first();

        return (bool) ($owner?->isCurrentlySuspended() ?? false);
    }

    /**
     * Mengecek apakah UMKM terverifikasi, toko sedang buka, dan tidak sedang ditangguhkan.
     */
    public function isBukaDanAktif(): bool
    {
        return $this->isTerverifikasi()
            && strtolower((string) $this->status_operasional) === 'buka'
            && ! $this->isDitangguhkan();
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
     * Scope UMKM yang sudah terverifikasi (status_verifikasi = 'disetujui').
     */
    public function scopeTerverifikasi(Builder $query): Builder
    {
        return $query->whereRaw("LOWER(CAST(status_verifikasi AS TEXT)) = 'disetujui'");
    }

    /**
     * Scope UMKM yang tidak sedang dalam masa penangguhan aktif.
     */
    public function scopeTidakDitangguhkan(Builder $query): Builder
    {
        $hasColumn = User::hasSuspendedUntilColumn();
        $nowStr = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $fallbackCutoffStr = Carbon::now('Asia/Jakarta')->subDays(3)->toDateTimeString();

        return $query->whereHas('user', function (Builder $userQuery) use ($hasColumn, $nowStr, $fallbackCutoffStr): void {
            $userQuery->where(function (Builder $q) use ($hasColumn, $nowStr, $fallbackCutoffStr): void {
                $q->where('is_suspended', false)
                    ->orWhereNull('is_suspended');

                if ($hasColumn) {
                    $q->orWhere(function (Builder $exp) use ($nowStr): void {
                        $exp->whereNotNull('suspended_until')
                            ->where('suspended_until', '<=', $nowStr);
                    });
                } else {
                    $q->orWhere(function (Builder $exp) use ($fallbackCutoffStr): void {
                        $exp->whereNotNull('updated_at')
                            ->where('updated_at', '<=', $fallbackCutoffStr);
                    });
                }
            });
        });
    }

    /**
     * Scope UMKM yang berstatus operasional Buka dan tidak sedang ditangguhkan.
     */
    public function scopeBuka(Builder $query): Builder
    {
        return $query
            ->whereRaw("LOWER(CAST(status_operasional AS TEXT)) IN ('buka', 'true', '1')")
            ->tidakDitangguhkan();
    }
}