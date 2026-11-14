<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKesehatan extends Model
{
    use HasFactory;

    // Wajib ditambahkan agar Laravel tidak mencari kolom created_at & updated_at
    public $timestamps = false;

    protected $table = 'profil_kesehatan';
    protected $primaryKey = 'id_profil';

    // Pastikan semua kolom sesuai ERD terdaftar di sini
    protected $fillable = [
        'id_user',
        'riwayat_alergi',
        'jenis_diet',
        'catatan_khusus',
        'batas_budget',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}