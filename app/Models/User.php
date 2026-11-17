<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama_lengkap', 'email', 'password', 'role'])]
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

    // Relasi ke Profil Kesehatan
    public function profilKesehatan()
    {
        return $this->hasOne(ProfilKesehatan::class, 'id_user', 'id_user');
    }

    // Relasi ke UMKM
    public function umkm()
    {
        return $this->hasOne(Umkm::class, 'id_user', 'id_user');
    }
}