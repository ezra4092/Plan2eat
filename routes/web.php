update route UMKM/Pelanggan
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilKesehatanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\MealPlanController;
// Controller berikut belum dibuat / masih dalam tahap pengembangan (lihat timeline)
// use App\Http\Controllers\MenuController;
// use App\Http\Controllers\FlashSaleController;
// use App\Http\Controllers\PesananController;
// use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UlasanController;
// use App\Http\Controllers\DashboardUmkmController;
// use App\Http\Controllers\JadwalMasakController;
use App\Http\Controllers\LaporanKeuanganController;
// use App\Http\Controllers\PenutupanHarianController;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\MasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes Plan2Eat
|--------------------------------------------------------------------------
| Konvensi Penamaan Route:
| - Menampilkan halaman / view : {prefix}.{nama}        (cth: umkm.profil, umkm.menu)
| - Proses tambah data (dialog) : {prefix}.{nama}-create (cth: umkm.menu-create)
| - Proses edit/update (dialog) : {prefix}.{nama}-update (cth: umkm.menu-update)
| - Proses hapus data           : {prefix}.{nama}-delete (cth: umkm.menu-delete)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. AUTENTIKASI (PUBLIC & GUEST)
// ==========================================
Route::get('/', function () {
    return view('login');
})->name('login_page');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register & Verifikasi OTP (Pelanggan)
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register'])->name('register-post');
// Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp');
// Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp-verify');

// Personalisasi Pelanggan
Route::get('/pelanggan/personalisasi', function () {
    return view('pelanggan.personalisasi');
})->name('pelanggan.personalisasi');

// Pendaftaran Mitra UMKM
Route::get('/umkm/pendaftaran-mitra', function () {
    return view('umkm.pendaftaran-mitra');
})->name('umkm.pendaftaran-mitra');

// Registrasi Pelanggan
Route::get('/register', [AuthController::class, 'showRegistrationForm'])
    ->name('register.form');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

// Verifikasi OTP
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])
    ->name('otp.form');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
    ->name('verify-otp');

Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
    ->name('resend-otp');

// ==========================================
// 2. ROUTE TERAUTENTIKASI (MIDDLEWARE AUTH)
// ==========================================
Route::group(['middleware' => ['auth']], function() {

    // ------------------------------------------
    // DASHBOARD PER ROLE
    // ------------------------------------------
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/umkm/dashboard', function () {
        return view('umkm.dashboard');
    })->name('umkm.dashboard');

    Route::get('/pelanggan/beranda', function () {
        return view('pelanggan.beranda');
    })->name('pelanggan.beranda');

    


    // ==========================================
    // MODUL PELANGGAN
    // ==========================================

    // --- Profil Kesehatan ---
    Route::get('/profil', [ProfilKesehatanController::class, 'index'])->name('pelanggan.profil');
    Route::get('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'index'])
        ->name('pelanggan.profil_kesehata');
    Route::post('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'store'])
        ->name('pelanggan.profil_kesehatan-create');
    Route::put('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'update'])
        ->name('pelanggan.profil_kesehatan-update');
    Route::delete('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'destroy'])
        ->name('pelanggan.profil_kesehatan-delete');

    // Alias kompatibilitas view lama
    Route::post('/pelanggan/profil-kesehatan/legacy-store', [ProfilKesehatanController::class, 'store'])->name('pelanggan.profil_kesehatan.store');
    Route::put('/pelanggan/profil-kesehatan/{id_profil}/legacy-update', [ProfilKesehatanController::class, 'update'])->name('pelanggan.profil_kesehatan.update');
    Route::delete('/pelanggan/profil-kesehatan/{id_profil}/legacy-destroy', [ProfilKesehatanController::class, 'destroy'])->name('pelanggan.profil_kesehatan.destroy');

    // --- Meal Plan & Detail Meal Plan ---
    Route::get('/pelanggan/meal-plan', [MealPlanController::class, 'index'])
        ->name('pelanggan.meal_plan');
    Route::post('/pelanggan/meal-plan', [MealPlanController::class, 'store'])
        ->name('pelanggan.meal_plan-create');
    Route::put('/pelanggan/meal-plan/{id_plan}', [MealPlanController::class, 'update'])
        ->name('pelanggan.meal_plan-update');
    Route::delete('/pelanggan/meal-plan/{id_plan}', [MealPlanController::class, 'destroy'])
        ->name('pelanggan.meal_plan-delete');

    // Detail Meal Plan (Dialog Tambah/Edit/Hapus menu dalam plan)
    Route::post('/pelanggan/meal-plan/{id_plan}/detail', [MealPlanController::class, 'storeDetail'])
        ->name('pelanggan.meal_plan_detail-create');
    Route::put('/pelanggan/meal-plan/detail/{id}', [MealPlanController::class, 'updateDetail'])
        ->name('pelanggan.meal_plan_detail-update');
    Route::delete('/pelanggan/meal-plan/detail/{id_detail_plan}', [MealPlanController::class, 'destroyDetail'])
        ->name('pelanggan.meal_plan_detail-delete');

    // Alias kompatibilitas view lama Meal Plan
    Route::get('/pelanggan/meal-plan/legacy-index', [MealPlanController::class, 'index'])->name('pelanggan.meal_plan.index');
    Route::post('/pelanggan/meal-plan/legacy-store', [MealPlanController::class, 'store'])->name('pelanggan.meal_plan.store');
    Route::put('/pelanggan/meal-plan/{id_plan}/legacy-update', [MealPlanController::class, 'update'])->name('pelanggan.meal_plan.update');
    Route::delete('/pelanggan/meal-plan/{id_plan}/legacy-destroy', [MealPlanController::class, 'destroy'])->name('pelanggan.meal_plan.destroy');
    Route::post('/pelanggan/meal-plan/{id_plan}/detail/legacy-store', [MealPlanController::class, 'storeDetail'])->name('pelanggan.meal_plan.detail.store');
    Route::put('/pelanggan/meal-plan/detail/{id}/legacy-update', [MealPlanController::class, 'updateDetail'])->name('pelanggan.meal_plan.detail.update');
    Route::delete('/pelanggan/meal-plan/detail/{id_detail_plan}/legacy-destroy', [MealPlanController::class, 'destroyDetail'])->name('pelanggan.meal_plan.detail.destroy');

    // --- Eksplorasi Menu & Flash Sale (Pelanggan) ---
    // Route::get('/pelanggan/menu', [MenuController::class, 'pelangganMenu'])->name('pelanggan.menu');
    // Route::get('/pelanggan/menu/{id_menu}', [MenuController::class, 'show'])->name('pelanggan.menu-detail');
    // Route::get('/pelanggan/flash-sale', [FlashSaleController::class, 'pelangganFlashSale'])->name('pelanggan.flashsale');

    // --- Pemesanan & Pembayaran (Pelanggan) ---
    // Route::get('/pelanggan/checkout', [PesananController::class, 'checkoutPage'])->name('pelanggan.checkout');
    // Route::post('/pelanggan/pesanan', [PesananController::class, 'store'])->name('pelanggan.pesanan-create');
    // Route::get('/pelanggan/riwayat-pesanan', [PesananController::class, 'history'])->name('pelanggan.pesanan');
    // Route::get('/pelanggan/pesanan/{id_pesanan}', [PesananController::class, 'show'])->name('pelanggan.pesanan-detail');
    // Route::put('/pelanggan/pesanan/{id_pesanan}/cancel', [PesananController::class, 'cancel'])->name('pelanggan.pesanan-cancel');
    // Route::post('/pelanggan/pembayaran', [PembayaranController::class, 'bayar'])->name('pelanggan.pembayaran-create');

    // --- Ulasan & Rating (Pelanggan) ---
    // Route::post('/pelanggan/ulasan', [UlasanController::class, 'store'])->name('pelanggan.ulasan-create');
    Route::put('/pelanggan/ulasan/{id_ulasan}', [UlasanController::class, 'update'])->name('pelanggan.ulasan-update');
    // Route::delete('/pelanggan/ulasan/{id_ulasan}', [UlasanController::class, 'destroy'])->name('pelanggan.ulasan-delete');


    // ==========================================
    // MODUL UMKM
    // ==========================================

    // --- Profil UMKM ---
    Route::get('/umkm/profil', [UmkmController::class, 'index'])
        ->name('umkm.profil');
     Route::put('/umkm/profil/{id_umkm}', [UmkmController::class, 'update'])
         ->name('umkm.profil-update');
     Route::post('/umkm/logout', [UmkmController::class, 'logout'])
         ->name('umkm.logout');

    // // Alias kompatibilitas view lama
    Route::get('/umkm/profil/legacy-index', [UmkmController::class, 'index'])->name('umkm.profil.index');
    Route::put('/umkm/profil/{id_umkm}/legacy-update', [UmkmController::class, 'update'])->name('umkm.profil.update');

    // --- Manajemen Menu (CRUD Dialog) ---
    // Route::get('/umkm/menu', [MenuController::class, 'index'])->name('umkm.menu');
    // Route::post('/umkm/menu', [MenuController::class, 'store'])->name('umkm.menu-create');
    // Route::put('/umkm/menu/{id_menu}', [MenuController::class, 'update'])->name('umkm.menu-update');
    // Route::delete('/umkm/menu/{id_menu}', [MenuController::class, 'destroy'])->name('umkm.menu-delete');
    // Route::patch('/umkm/menu/{id_menu}/stok', [MenuController::class, 'updateStok'])->name('umkm.menu-stok');

    // --- Manajemen Flash Sale (CRUD Dialog) ---
    // Route::get('/umkm/flash-sale', [FlashSaleController::class, 'index'])->name('umkm.flashsale');
    // Route::post('/umkm/flash-sale', [FlashSaleController::class, 'store'])->name('umkm.flashsale-create');
    // Route::put('/umkm/flash-sale/{id_flash_sale}', [FlashSaleController::class, 'update'])->name('umkm.flashsale-update');
    // Route::delete('/umkm/flash-sale/{id_flash_sale}', [FlashSaleController::class, 'destroy'])->name('umkm.flashsale-delete');

    // --- Manajemen Pesanan Masuk UMKM ---
    // Route::get('/umkm/pesanan', [PesananController::class, 'pesananMasuk'])->name('umkm.pesanan');
    // Route::get('/umkm/pesanan/{id_pesanan}', [PesananController::class, 'detailPesananMasuk'])->name('umkm.pesanan-detail');
    // Route::put('/umkm/pesanan/{id_pesanan}/status', [PesananController::class, 'updateStatus'])->name('umkm.pesanan-status');
    // Route::post('/umkm/pesanan/{id_pesanan}/refund', [PembayaranController::class, 'refund'])->name('umkm.pesanan-refund');

    // --- Dashboard, Jadwal Masak & Laporan UMKM ---
    // Route::get('/umkm/jadwal-masak', [JadwalMasakController::class, 'index'])->name('umkm.jadwal_masak');
    Route::get('/umkm/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('umkm.laporan_keuangan');
    // Route::post('/umkm/penutupan-harian', [PenutupanHarianController::class, 'store'])->name('umkm.penutupan_harian-create');


    // ==========================================
    // MODUL ADMIN
    // ==========================================

    // --- Manajemen User & UMKM ---
    // Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
    // Route::delete('/admin/users/{id_user}', [AdminController::class, 'deleteUser'])->name('admin.users-delete');
    // Route::get('/admin/umkm', [AdminController::class, 'listUmkm'])->name('admin.umkm');
    // Route::put('/admin/umkm/{id_umkm}/verifikasi', [AdminController::class, 'verifyUmkm'])->name('admin.umkm-verifikasi');

    // --- Master Data ---
    // Route::get('/admin/master-data', [MasterDataController::class, 'index'])->name('admin.master_data');
    // Route::post('/admin/master-data', [MasterDataController::class, 'store'])->name('admin.master_data-create');
    // Route::put('/admin/master-data/{id}', [MasterDataController::class, 'update'])->name('admin.master_data-update');
    // Route::delete('/admin/master-data/{id}', [MasterDataController::class, 'destroy'])->name('admin.master_data-delete');

});
