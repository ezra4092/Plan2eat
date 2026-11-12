<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKesehatan extends Model
{
    use HasFactory;

    protected $table = 'profil_kesehatan';
    protected $primaryKey = 'id_profil';

    protected $fillable = [
        'id_user',
        'riwayat_alergii',
        'jenis_diet',
        'catatan_khusus',
        'batas_budget',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}