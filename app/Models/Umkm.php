<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';
    protected $primaryKey = 'id_umkm';

    protected $fillable = [
        'id_user',
        'nama_vendor',
        'deskripsi_toko',
        'alamat_lengkap',
        'titik_koordinat_lokasi',
        'jam_buka',
        'jam_tutup',
        'status_operasional',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}