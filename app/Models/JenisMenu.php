<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisMenu extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'jenis_menu';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'jenis_menu', 'id');
    }
}
