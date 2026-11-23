<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PembatalanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfilKesehatanController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\VerifikasiUmkmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes Plan2Eat
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. LANDING PAGE & AUTENTIKASI (PUBLIC)
// ==========================================
Route::get('/', [AuthController::class, 'showLandingPage'])->name('landing_page');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_page');
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
// 2. ROUTE TERAUTENTIKASI (MIDDLEWARE AUTH + ROLE)
// ==========================================
Route::middleware(['auth'])->group(function (): void {

    // ==========================================
    // MODUL PELANGGAN
    // ==========================================
    Route::middleware(['role:pelanggan'])->group(function (): void {
        Route::get('/pelanggan/beranda', [MenuController::class, 'berandaPelanggan'])
            ->name('pelanggan.beranda');

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

        // Detail Meal Plan
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
    });

    // ==========================================
    // MODUL UMKM
    // ==========================================
    Route::middleware(['role:umkm'])->group(function (): void {
        Route::get('/umkm/dashboard', [UmkmController::class, 'dashboard'])
            ->name('umkm.dashboard');

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

        // --- Laporan Keuangan UMKM ---
        Route::get('/umkm/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('umkm.laporan_keuangan');
        Route::post('/umkm/laporan-keuangan/cairkan', [LaporanKeuanganController::class, 'cairkanDana'])->name('umkm.laporan_keuangan-cairkan');
        Route::get('/umkm/laporan-keuangan/cetak', [LaporanKeuanganController::class, 'cetakLaporan'])->name('umkm.laporan_keuangan-cetak');
    });

    // ==========================================
    // MODUL ADMIN
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Data Master (UMKM Terverifikasi, Pelanggan, Jenis Menu)
        Route::get('/data-master', [DataMasterController::class, 'index'])->name('data_master');
        Route::patch('/data-master/umkm/{id_umkm}/status', [DataMasterController::class, 'toggleUmkmStatus'])->name('data_master.umkm_status');
        Route::patch('/data-master/pelanggan/{id_user}/suspend', [DataMasterController::class, 'togglePelangganSuspend'])->name('data_master.pelanggan_suspend');
        Route::delete('/data-master/pelanggan/{id_user}', [DataMasterController::class, 'hapusPelanggan'])->name('data_master.pelanggan_delete');
        Route::post('/data-master/jenis-menu', [DataMasterController::class, 'storeJenisMenu'])->name('data_master.jenis_menu_store');
        Route::put('/data-master/jenis-menu/{id}', [DataMasterController::class, 'updateJenisMenu'])->name('data_master.jenis_menu_update');
        Route::delete('/data-master/jenis-menu/{id}', [DataMasterController::class, 'destroyJenisMenu'])->name('data_master.jenis_menu_delete');

        // Pengelolaan Pengguna (Master Data)
        Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
        Route::patch('/pengguna/{id_user}/suspend', [AdminController::class, 'toggleSuspend'])->name('pengguna.suspend');
        Route::delete('/pengguna/{id_user}', [AdminController::class, 'hapusPengguna'])->name('pengguna.delete');

        // Verifikasi UMKM
        Route::get('/umkm', [VerifikasiUmkmController::class, 'index'])->name('umkm');
        Route::put('/umkm/{id_umkm}/verifikasi', [VerifikasiUmkmController::class, 'update'])->name('umkm-verifikasi');

        Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/csv', [AdminController::class, 'laporanCsv'])->name('laporan.csv');
        Route::get('/pengaturan', fn () => redirect()->route('admin.dashboard'))->name('pengaturan');
        Route::get('/ulasan', [AdminController::class, 'ulasan'])->name('ulasan');
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
        Route::delete('/ulasan/{id_ulasan}', [AdminController::class, 'hapusUlasan'])->name('ulasan.delete');
    });
});
