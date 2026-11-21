<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailMealPlan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'detail_meal_plan';
    protected $primaryKey = 'id_detail_plan';

    protected $fillable = [
        'id_plan',
        'id_menu',
        'jadwal_makan',
        'waktu_makan',
    ];

    protected function casts(): array
    {
        return [
            'jadwal_makan' => 'date',
        ];
    }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class, 'id_plan', 'id_plan');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
