<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilKesehatanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\MealPlanController;

Route::get('/', function () {
    return view('login');
})->name('login_page');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/umkm/dashboard', function () {
        return view('umkm.dashboard');
    })->name('umkm.dashboard');

    Route::get('/pelanggan/beranda', function () {
        return view('pelanggan.beranda');
    })->name('pelanggan.beranda');
    
    // Route Profil Kesehatan
    Route::get('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'index'])
        ->name('pelanggan.profil_kesehatan');
    Route::post('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'store'])
        ->name('pelanggan.profil_kesehatan.store');
    Route::put('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'update'])
        ->name('pelanggan.profil_kesehatan.update');
    Route::delete('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'destroy'])
        ->name('pelanggan.profil_kesehatan.destroy');

    // Menampilkan halaman utama Meal Plan
    Route::get('/pelanggan/meal-plan', [MealPlanController::class, 'index'])
        ->name('pelanggan.meal_plan.index');
    
    // CRUD Utama Meal Plan
    Route::post('/pelanggan/meal-plan', [MealPlanController::class, 'store'])
        ->name('pelanggan.meal_plan.store');
    Route::put('/pelanggan/meal-plan/{id_plan}', [MealPlanController::class, 'update'])
        ->name('pelanggan.meal_plan.update');
    Route::delete('/pelanggan/meal-plan/{id_plan}', [MealPlanController::class, 'destroy'])
        ->name('pelanggan.meal_plan.destroy');

    // CRUD Kelola Detail Meal Plan (Menu, Jadwal, Waktu)
    Route::post('/pelanggan/meal-plan/{id_plan}/detail', [MealPlanController::class, 'storeDetail'])
        ->name('pelanggan.meal_plan.detail.store');
    Route::delete('/pelanggan/meal-plan/detail/{id_detail_plan}', [MealPlanController::class, 'destroyDetail'])
        ->name('pelanggan.meal_plan.detail.destroy');

    Route::put('/pelanggan/meal-plan/detail/{id}', [MealPlanController::class, 'updateDetail'])->name('pelanggan.meal_plan.detail.update');

    // Route Profil UMKM
    Route::get('/umkm/profil', [UmkmController::class, 'index'])
        ->name('umkm.profil.index');

    Route::put('/umkm/profil/{id_umkm}', [UmkmController::class, 'update'])
        ->name('umkm.profil.update');

    Route::post('/umkm/logout', [UmkmController::class, 'logout'])
        ->name('umkm.logout');

        
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

