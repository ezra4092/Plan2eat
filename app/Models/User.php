<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

#[Fillable(['nama_lengkap', 'email', 'password', 'role', 'no_hp', 'foto', 'saldo', 'is_suspended', 'suspended_until'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Matikan timestamps bawaan Laravel karena tidak ada kolom updated_at otomatis yang diwajibkan semua query
    public $timestamps = false;

    protected $primaryKey = 'id_user';

    private static ?bool $suspendedUntilColumnExists = null;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'saldo' => 'decimal:2',
            'is_suspended' => 'boolean',
            'suspended_until' => 'datetime',
        ];
    }

    /**
     * Mengecek secara aman apakah kolom suspended_until sudah tersedia di tabel users.
     */
    public static function hasSuspendedUntilColumn(): bool
    {
        if (self::$suspendedUntilColumnExists !== null) {
            return self::$suspendedUntilColumnExists;
        }

        try {
            self::$suspendedUntilColumnExists = Schema::hasColumn('users', 'suspended_until');
        } catch (\Throwable $e) {
            self::$suspendedUntilColumnExists = false;
        }

        return self::$suspendedUntilColumnExists;
    }

    /**
     * Mengambil batas waktu penangguhan aktif (default 3 hari sejak ditangguhkan).
     */
    public function getSuspendedUntilAt(): ?Carbon
    {
        if (! (bool) ($this->is_suspended ?? false)) {
            return null;
        }

        $rawUntil = $this->getAttributes()['suspended_until'] ?? null;
        if (! empty($rawUntil)) {
            return Carbon::parse($rawUntil)->timezone('Asia/Jakarta');
        }

        $rawUpdated = $this->getAttributes()['updated_at'] ?? null;
        if (! empty($rawUpdated)) {
            return Carbon::parse($rawUpdated)->timezone('Asia/Jakarta')->addDays(3);
        }

        return null;
    }

    /**
     * Mengecek apakah user masih dalam masa penangguhan dan otomatis mencabutnya jika durasi 3 hari sudah berakhir.
     */
    public function isCurrentlySuspended(): bool
    {
        if (! (bool) ($this->is_suspended ?? false)) {
            return false;
        }

        $until = $this->getSuspendedUntilAt();
        if ($until !== null && Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo($until)) {
            $this->unsuspend();

            return false;
        }

        return true;
    }

    /**
     * Menangguhkan akun selama durasi tertentu (default 3 hari) dan menutup toko jika akun merupakan UMKM.
     */
    public function suspendForDays(int $days = 3, ?string $reason = null): void
    {
        $now = Carbon::now('Asia/Jakarta');
        $until = $now->copy()->addDays($days);

        DB::transaction(function () use ($now, $until, $reason): void {
            $this->is_suspended = true;
            if (self::hasSuspendedUntilColumn()) {
                $this->suspended_until = $until;
            }
            $this->setAttribute('updated_at', $now);
            $this->save();

            $umkm = $this->umkm()->first();
            if ($umkm) {
                $umkm->status_operasional = 'Tutup';
                if ($reason !== null && trim($reason) !== '') {
                    $umkm->catatan = trim($reason);
                }
                $umkm->save();
            }
        });
    }

    /**
     * Mencabut penangguhan akun dan memulihkan operasional toko UMKM.
     */
    public function unsuspend(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        DB::transaction(function () use ($now): void {
            $this->is_suspended = false;
            if (self::hasSuspendedUntilColumn()) {
                $this->suspended_until = null;
            }
            $this->setAttribute('updated_at', $now);
            $this->save();

            $umkm = $this->umkm()->first();
            if ($umkm) {
                $umkm->catatan = null;
                if (strtolower((string) $umkm->status_operasional) === 'tutup') {
                    $umkm->status_operasional = 'Buka';
                }
                $umkm->save();
            }
        });
    }

    public function profilKesehatan(): HasOne
    {
        return $this->hasOne(ProfilKesehatan::class, 'id_user', 'id_user');
    }

    public function umkm(): HasOne
    {
        return $this->hasOne(Umkm::class, 'id_user', 'id_user');
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class, 'id_user', 'id_user');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'id_user', 'id_user');
    }
}
