<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilKesehatanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\MealPlanController;
// Controller berikut belum dibuat / masih dalam tahap pengembangan (lihat timeline)
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\PembatalanController;
use App\Http\Controllers\PesananController;
// use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UlasanController;
// use App\Http\Controllers\DashboardUmkmController;
// use App\Http\Controllers\JadwalMasakController;
use App\Http\Controllers\LaporanKeuanganController;
// use App\Http\Controllers\PenutupanHarianController;
use App\Http\Controllers\VerifikasiUmkmController;
use App\Http\Controllers\AdminController;
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
Route::get('/', function () {return view('login');})->name('login_page');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pilihan Role Registrasi
Route::get('/pilihan', [AuthController::class, 'showPilihanForm'])
    ->name('register.pilihan');

// Personalisasi Pelanggan
Route::get('/pelanggan/personalisasi', [AuthController::class, 'showPersonalisasiForm'])
    ->name('pelanggan.personalisasi');
Route::post('/pelanggan/personalisasi', [AuthController::class, 'storePersonalisasi'])
    ->name('pelanggan.personalisasi-store');

// Pendaftaran Mitra UMKM
Route::get('/umkm/pendaftaran-mitra', [AuthController::class, 'showPendaftaranMitraForm'])
    ->name('umkm.pendaftaran-mitra');
Route::post('/umkm/pendaftaran-mitra', [AuthController::class, 'storePendaftaranMitra'])
    ->name('umkm.pendaftaran-store');

// Registrasi Akun (Pelanggan & UMKM)
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
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/umkm/dashboard', [UmkmController::class, 'dashboard'])->name('umkm.dashboard');

    Route::get('/pelanggan/beranda', [MenuController::class, 'berandaPelanggan'])
        ->name('pelanggan.beranda');

    // ==========================================
    // MODUL PELANGGAN
    // ==========================================

    // --- Profil Kesehatan ---
    Route::get('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'index'])
        ->name('pelanggan.profil_kesehatan');
    Route::post('/pelanggan/profil-kesehatan', [ProfilKesehatanController::class, 'store'])
        ->name('pelanggan.profil_kesehatan-create');
    Route::put('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'update'])
        ->name('pelanggan.profil_kesehatan-update');
    Route::delete('/pelanggan/profil-kesehatan/{id_profil}', [ProfilKesehatanController::class, 'destroy'])
        ->name('pelanggan.profil_kesehatan-delete');

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

    // --- Eksplorasi Menu & Flash Sale (Pelanggan) ---
    Route::get('/pelanggan/mitra', [UmkmController::class, 'pelangganMitra'])->name('pelanggan.mitra');
    Route::get('/pelanggan/menu', [MenuController::class, 'pelangganMenu'])->name('pelanggan.menu');
    Route::get('/pelanggan/menu/{id_menu}', [MenuController::class, 'show'])->name('pelanggan.menu-detail');
    Route::get('/pelanggan/flash-sale', [FlashSaleController::class, 'pelangganFlashSale'])->name('pelanggan.flashsale');

    // --- Pemesanan & Pembayaran (Pelanggan) ---
    Route::get('/pelanggan/keranjang', [PesananController::class, 'keranjang'])->name('pelanggan.keranjang');
    Route::post('/pelanggan/keranjang/update', [PesananController::class, 'updateKeranjang'])->name('pelanggan.keranjang-update');
    Route::delete('/pelanggan/keranjang/hapus/{index}', [PesananController::class, 'hapusItemKeranjang'])->name('pelanggan.keranjang-hapus');
    Route::get('/pelanggan/checkout', [PesananController::class, 'checkoutPage'])->name('pelanggan.checkout');
    Route::post('/pelanggan/pesanan', [PesananController::class, 'store'])->name('pelanggan.pesanan-create');
    Route::get('/pelanggan/riwayat-pesanan', [PesananController::class, 'history'])->name('pelanggan.pesanan');
    Route::get('/pelanggan/pesanan/{id_pesanan}', [PesananController::class, 'show'])->name('pelanggan.pesanan-detail');
    Route::post('/pelanggan/pesanan/{id_pesanan}/pesan-lagi', [PesananController::class, 'pesanLagi'])->name('pelanggan.pesanan-reorder');
    Route::post('/pelanggan/pesanan/{id_pesanan}/batal', [PembatalanController::class, 'batal'])->name('pelanggan.pesanan-batal');
    Route::post('/pelanggan/meal-plan/{id_plan}/checkout', [PesananController::class, 'checkoutMealPlan'])->name('pelanggan.meal_plan-checkout');

    // --- Ulasan & Rating (Pelanggan) ---
    Route::get('/pelanggan/ulasan', [UlasanController::class, 'index'])->name('pelanggan.ulasan');
    Route::post('/pelanggan/ulasan', [UlasanController::class, 'store'])->name('pelanggan.ulasan-create');
    Route::put('/pelanggan/ulasan/{id_ulasan}', [UlasanController::class, 'update'])->name('pelanggan.ulasan-update');
    Route::delete('/pelanggan/ulasan/{id_ulasan}', [UlasanController::class, 'destroy'])->name('pelanggan.ulasan-delete');

    // --- Langganan ---
    Route::get('/pesanan/langganan', [PesananController::class, 'langganan'])
        ->name('pelanggan.langganan');

    // ==========================================
    // MODUL UMKM
    // ==========================================

    // --- Profil UMKM ---
    Route::get('/umkm/profil', [UmkmController::class, 'index'])
        ->name('umkm.profil');
    Route::put('/umkm/profil/{id_umkm}', [UmkmController::class, 'update'])
        ->name('umkm.profil-update');
    Route::patch('/umkm/profil/{id_umkm}/status-operasional', [UmkmController::class, 'toggleStatusOperasional'])
        ->name('umkm.profil-status');
    Route::post('/umkm/logout', [UmkmController::class, 'logout'])
        ->name('umkm.logout');

    // --- Manajemen Menu (CRUD Dialog) ---
    Route::get('/umkm/menu', [MenuController::class, 'index'])->name('umkm.menu');
    Route::post('/umkm/menu', [MenuController::class, 'store'])->name('umkm.menu-create');
    Route::put('/umkm/menu/{id_menu}', [MenuController::class, 'update'])->name('umkm.menu-update');
    Route::delete('/umkm/menu/{id_menu}', [MenuController::class, 'destroy'])->name('umkm.menu-delete');
    Route::patch('/umkm/menu/{id_menu}/stok', [MenuController::class, 'updateStok'])->name('umkm.menu-stok');

    // --- Manajemen Flash Sale (CRUD Dialog) ---
    Route::get('/umkm/flash-sale', [FlashSaleController::class, 'index'])->name('umkm.flashsale');
    Route::post('/umkm/flash-sale', [FlashSaleController::class, 'store'])->name('umkm.flashsale-create');
    Route::put('/umkm/flash-sale/{id_flash_sale}', [FlashSaleController::class, 'update'])->name('umkm.flashsale-update');
    Route::delete('/umkm/flash-sale/{id_flash_sale}', [FlashSaleController::class, 'destroy'])->name('umkm.flashsale-delete');

    // --- Manajemen Pesanan Masuk UMKM ---
    Route::get('/umkm/pesanan', [PesananController::class, 'pesananMasuk'])->name('umkm.pesanan');
    Route::get('/umkm/pesanan/{id_pesanan}', [PesananController::class, 'detailPesananMasuk'])->name('umkm.pesanan-detail');
    Route::put('/umkm/pesanan/{id_pesanan}/status', [PesananController::class, 'updateStatus'])->name('umkm.pesanan-status');
    // Route::post('/umkm/pesanan/{id_pesanan}/refund', [PembayaranController::class, 'refund'])->name('umkm.pesanan-refund');

    // --- Dashboard, Jadwal Masak & Laporan UMKM ---
    // Route::get('/umkm/jadwal-masak', [JadwalMasakController::class, 'index'])->name('umkm.jadwal_masak');
    Route::get('/umkm/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('umkm.laporan_keuangan');
    Route::post('/umkm/laporan-keuangan/cairkan', [LaporanKeuanganController::class, 'cairkanDana'])->name('umkm.laporan_keuangan-cairkan');
    Route::get('/umkm/laporan-keuangan/cetak', [LaporanKeuanganController::class, 'cetakLaporan'])->name('umkm.laporan_keuangan-cetak');
    // Route::post('/umkm/penutupan-harian', [PenutupanHarianController::class, 'store'])->name('umkm.penutupan_harian-create');


    // ==========================================
    // MODUL ADMIN
    // ==========================================

    Route::prefix('admin')->name('admin.')->group(function () {
    // Pengelolaan Pengguna (Master Data)
    Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
    Route::patch('/pengguna/{id_user}/suspend', [AdminController::class, 'toggleSuspend'])->name('pengguna.suspend');
    Route::delete('/pengguna/{id_user}', [AdminController::class, 'hapusPengguna'])->name('pengguna.delete');

    // Verifikasi UMKM
    Route::get('/umkm', [App\Http\Controllers\VerifikasiUmkmController::class, 'index'])->name('umkm');
    Route::put('/umkm/{id_umkm}/verifikasi', [App\Http\Controllers\VerifikasiUmkmController::class, 'update'])->name('umkm-verifikasi');
    // Tambahkan 2 baris ini untuk mencegah error di nav.blade.php
    Route::get('/laporan', function() { return "Halaman Laporan Belum Dibuat"; })->name('laporan');
    Route::get('/pengaturan', function() { return "Halaman Pengaturan Belum Dibuat"; })->name('pengaturan');
    Route::get('/ulasan', [AdminController::class, 'ulasan'])->name('ulasan');
    Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
    Route::delete('/ulasan/{id_ulasan}', [AdminController::class, 'hapusUlasan'])->name('ulasan.delete');

});
});
