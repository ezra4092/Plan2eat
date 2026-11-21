<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_umkm',
        'id_plan',
        'status_pesanan',
        'total_pembayaran',
        'biaya_admin',
        'dana_dicairkan',
        'tgl_mulai',
        'tgl_selesai',
        'metode_pembayaran',
        'metode_pengiriman',
        'jadwal_pengiriman',
        'waktu_pembayaran',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_mulai' => 'datetime',
            'tgl_selesai' => 'datetime',
            'jadwal_pengiriman' => 'datetime',
            'waktu_pembayaran' => 'datetime',
            'total_pembayaran' => 'decimal:2',
            'biaya_admin' => 'decimal:2',
            'dana_dicairkan' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'id_umkm', 'id_umkm');
    }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class, 'id_plan', 'id_plan');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(Ulasan::class, 'id_pesanan', 'id_pesanan');
    }
}
