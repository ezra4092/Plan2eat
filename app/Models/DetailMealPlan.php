<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailMealPlan extends Model {
    public $timestamps = false;
    protected $table = 'detail_meal_plan';
    protected $primaryKey = 'id_detail_plan';
    
    protected $fillable = [
        'id_plan', 
        'id_menu', 
        'jadwal_makan', 
        'waktu_makan'
    ];

    public function mealPlan() { 
        return $this->belongsTo(MealPlan::class, 'id_plan', 'id_plan'); 
    }
    
    public function menu() { 
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu'); 
    }
}