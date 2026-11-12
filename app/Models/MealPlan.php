<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlan extends Model
{
    use HasFactory;

    protected $table = 'meal_plan';
    protected $primaryKey = 'id_plan';

    protected $fillable = [
        'id_user',
        'judul_plan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_plan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_plan', 'id_plan');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_plan', 'id_plan');
    }
}
