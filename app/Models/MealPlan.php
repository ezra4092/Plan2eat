<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model {
    public $timestamps = false;
    protected $table = 'meal_plan';
    protected $primaryKey = 'id_plan';
    
    protected $fillable = [
        'id_user', 
        'judul_plan', 
        'tanggal_mulai', 
        'tanggal_selesai'
    ];

    public function user() { 
        return $this->belongsTo(User::class, 'id_user', 'id_user'); 
    }
    
    public function pesanan() { 
        return $this->hasMany(Pesanan::class, 'id_plan', 'id_plan'); 
    }
    
    // Relasi baru ke Detail Meal Plan
    public function detailMealPlan() { 
        return $this->hasMany(DetailMealPlan::class, 'id_plan', 'id_plan'); 
    }
}