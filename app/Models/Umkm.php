<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'jam_buka',
        'jam_tutup',
        'status_operasional',
        'saldo',
        'metode_pembayaran'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}