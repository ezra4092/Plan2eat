<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama_lengkap', 'email', 'password', 'role', 'no_hp', 'foto'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Matikan timestamps bawaan Laravel karena tidak ada kolom updated_at
    public $timestamps = false; 
    
    protected $primaryKey = 'id_user';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
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