<?php

namespace App\Http\Controllers;

use App\Models\JenisMenu;
use App\Models\Menu;
use App\Models\Umkm;
use App\Models\ProfilKesehatan;
use App\Models\Flashsale;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu milik UMKM dengan opsi filter jenis_menu dan pencarian.
     */
    public function index(Request $request): View
    {
        // 1. Identifikasi UMKM yang sedang login (fallback ke UMKM pertama jika belum login/dev mode)
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        if (! $umkm) {
            $umkm = Umkm::first();
        }

        // 2. Query menu sesuai UMKM
        $query = Menu::with('jenisMenu');

        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        // 3. Filter berdasarkan jenis_menu (ID atau nama)
        if ($request->filled('jenis_menu') && $request->query('jenis_menu') !== 'all') {
            $filter = $request->query('jenis_menu');
            if (is_numeric($filter)) {
                $query->where('jenis_menu', (int) $filter);
            } else {
                $query->whereHas('jenisMenu', function ($q) use ($filter): void {
                    $q->where('nama', $filter);
                });
            }
        }

        // 4. Filter pencarian nama / deskripsi makanan
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search): void {
                $q->where('nama_makanan', 'like', "%{$search}%")
                    ->orWhere('deskripsi_makanan', 'like', "%{$search}%");
            });
        }

        $menus = $query->orderBy('id_menu', 'desc')->get();

        // 5. Statistik ringkasan
        $total = $menus->count();
        $tersedia = $menus->filter(fn ($m): bool => (bool) ($m->status ?? true) && ($m->stok_harian ?? 0) > 0)->count();
        $habis = $total - $tersedia;

        // 6. Data Jenis Menu untuk opsi filter dan dropdown modal
        $jenisMenuList = JenisMenu::all();
        $kategoriList = $jenisMenuList->pluck('nama')->values();

        $kategoriCount = [];
        foreach ($jenisMenuList as $jm) {
            $kategoriCount[$jm->nama] = $menus->where('jenis_menu', $jm->id)->count();
        }

        // 7. URL Aksi untuk frontend
        $urlBeranda = Route::has('umkm.dashboard') ? route('umkm.dashboard') : url('/umkm/dashboard');
        $urlCreate = route('umkm.menu-create');
        $urlUpdate = route('umkm.menu-update', '__ID__');
        $urlDelete = route('umkm.menu-delete', '__ID__');
        $urlStok = route('umkm.menu-stok', '__ID__');
        $urlFlash = Route::has('umkm.flashsale-create') ? route('umkm.flashsale-create') : '#';

        $isUmkmSuspended = $umkm ? $umkm->isDitangguhkan() : false;
        $suspendedUntilText = $isUmkmSuspended
            ? $umkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB'
            : null;
        $suspendReason = $umkm?->catatan;

        return view('umkm.manajemen_menu', compact(
            'umkm',
            'isUmkmSuspended',
            'suspendedUntilText',
            'suspendReason',
            'menus',
            'total',
            'tersedia',
            'habis',
            'jenisMenuList',
            'kategoriList',
            'kategoriCount',
            'urlBeranda',
            'urlCreate',
            'urlUpdate',
            'urlDelete',
            'urlStok',
            'urlFlash'
        ));
    }

    /**
     * Menyimpan menu baru ke dalam database.
     */
    public function store(Request $request): RedirectResponse
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        if (! $umkm) {
            $umkm = Umkm::first();
        }

        if (! $umkm) {
            return back()->with('error', 'Profil UMKM tidak ditemukan. Silakan lengkapi profil UMKM terlebih dahulu.');
        }

        if ($umkm->isDitangguhkan()) {
            $untilLabel = $umkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

            return back()->with('error', "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} sehingga tidak dapat menambah menu atau berjualan.");
        }

        $validated = $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'deskripsi_makanan' => 'nullable|string',
            'harga_normal' => 'required|numeric|min:0',
            'stok_harian' => 'required|integer|min:0',
            'kandungan_kalori' => 'nullable|integer|min:0',
            'jenis_menu' => 'nullable|exists:jenis_menu,id',
            'status' => 'nullable|boolean',
            'foto_menu' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if ($request->hasFile('foto_menu')) {
            $validated['foto_menu'] = $this->saveMenuPhoto($request->file('foto_menu'));
        }

        $validated['id_umkm'] = $umkm->id_umkm;
        $validated['status'] = $request->has('status') ? $request->boolean('status') : true;

        Menu::create($validated);

        return redirect()->route('umkm.menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Memperbarui informasi data menu.
     */
    public function update(Request $request, int|string $id_menu): RedirectResponse
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        $query = Menu::where('id_menu', $id_menu);
        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $menu = $query->firstOrFail();

        $ownerUmkm = $umkm ?: $menu->umkm;
        if ($ownerUmkm && $ownerUmkm->isDitangguhkan()) {
            $untilLabel = $ownerUmkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

            return back()->with('error', "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} sehingga tidak dapat mengubah menu.");
        }

        $validated = $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'deskripsi_makanan' => 'nullable|string',
            'harga_normal' => 'required|numeric|min:0',
            'stok_harian' => 'required|integer|min:0',
            'kandungan_kalori' => 'nullable|integer|min:0',
            'jenis_menu' => 'nullable|exists:jenis_menu,id',
            'status' => 'nullable|boolean',
            'foto_menu' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if ($request->hasFile('foto_menu')) {
            $this->deleteMenuPhoto($menu->foto_menu);
            $validated['foto_menu'] = $this->saveMenuPhoto($request->file('foto_menu'));
        } else {
            unset($validated['foto_menu']);
        }

        if ($request->has('status')) {
            $validated['status'] = $request->boolean('status');
        }

        $menu->update($validated);

        return redirect()->route('umkm.menu')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Menghapus menu dari database beserta file fotonya jika ada.
     */
    public function destroy(int|string $id_menu): RedirectResponse
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        $query = Menu::where('id_menu', $id_menu);
        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $menu = $query->firstOrFail();

        $ownerUmkm = $umkm ?: $menu->umkm;
        if ($ownerUmkm && $ownerUmkm->isDitangguhkan()) {
            $untilLabel = $ownerUmkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

            return back()->with('error', "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} sehingga tidak dapat mengubah menu.");
        }

        $this->deleteMenuPhoto($menu->foto_menu);

        $menu->delete();

        return redirect()->route('umkm.menu')->with('deleted', 'Menu berhasil dihapus!');
    }

    /**
     * Menyimpan file foto menu ke folder public/menu (dengan fallback storage jika folder terkunci OneDrive/permission).
     */
    private function saveMenuPhoto(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = 'menu_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $extension;
        $targetDir = public_path('menu');

        try {
            File::ensureDirectoryExists($targetDir, 0775, true);
            $contents = file_get_contents($file->getRealPath());

            if ($contents !== false && File::put($targetDir . DIRECTORY_SEPARATOR . $filename, $contents) !== false) {
                return 'menu/' . $filename;
            }
        } catch (\Throwable $e) {
            // Lanjut ke fallback storage jika folder public/menu terkunci oleh OneDrive/OS
        }

        return Storage::disk('public')->putFileAs('menu_images', $file, $filename);
    }

    /**
     * Menghapus file foto menu lama dari public/menu atau storage publik jika tersedia.
     */
    private function deleteMenuPhoto(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $publicFile = public_path($path);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Memperbarui stok makanan saja (mendukung toggle ketersediaan atau input angka stok).
     */
    public function updateStok(Request $request, int|string $id_menu): JsonResponse|RedirectResponse
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        $query = Menu::where('id_menu', $id_menu);
        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $menu = $query->firstOrFail();

        $ownerUmkm = $umkm ?: $menu->umkm;
        if ($ownerUmkm && $ownerUmkm->isDitangguhkan()) {
            $untilLabel = $ownerUmkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
            $msg = "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} sehingga menu tidak dapat diaktifkan atau diubah stoknya.";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 403);
            }

            return back()->with('error', $msg);
        }

        // 1. Dukungan toggle aktif/nonaktif status menu (status = 1/0)
        if ($request->has('status')) {
            $newStatus = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);
            $menu->update(['status' => $newStatus]);
        }
        // 2. Dukungan toggle ketersediaan switch (tersedia = 1/0)
        elseif ($request->has('tersedia')) {
            $tersedia = filter_var($request->input('tersedia'), FILTER_VALIDATE_BOOLEAN);
            $newStok = $tersedia ? ($menu->stok_harian > 0 ? $menu->stok_harian : 10) : 0;
            $menu->update([
                'stok_harian' => $newStok,
                'status' => $tersedia,
            ]);
        }
        // 3. Dukungan input angka stok langsung
        elseif ($request->has('stok_harian')) {
            $request->validate([
                'stok_harian' => 'required|integer|min:0',
            ]);
            $menu->update(['stok_harian' => (int) $request->input('stok_harian')]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status & stok menu berhasil diperbarui!',
                'status' => (bool) $menu->status,
                'stok_harian' => $menu->stok_harian,
            ]);
        }

        return redirect()->route('umkm.menu')->with('success', 'Status menu berhasil diperbarui!');
    }

    public function berandaPelanggan(): View
    {
        $idUser = Auth::id();

        // 1. Ambil profil kesehatan pelanggan yang sedang login (jika ada)
        $profilKesehatan = $idUser
            ? ProfilKesehatan::where('id_user', $idUser)->first()
            : null;

        // 2. Query menu yang aktif, stoknya tersedia & UMKM-nya berstatus Buka + Terverifikasi
        $query = Menu::with(['umkm', 'jenisMenu'])
            ->where('status', true)
            ->where('stok_harian', '>', 0)
            ->whereHas('umkm', function ($q): void {
                $q->terverifikasi()->buka();
            });

        // 3. Filter berdasarkan Profil Kesehatan (Alergi & Batas Budget)
        if ($profilKesehatan) {
            $alergiList = is_array($profilKesehatan->riwayat_alergi)
                ? $profilKesehatan->riwayat_alergi
                : json_decode((string) $profilKesehatan->riwayat_alergi, true);

            if (! empty($alergiList)) {
                foreach ($alergiList as $alergi) {
                    if (! empty($alergi) && strtolower((string) $alergi) !== 'tidak ada') {
                        $query->where('nama_makanan', 'not like', "%{$alergi}%")
                            ->where('deskripsi_makanan', 'not like', "%{$alergi}%");
                    }
                }
            }

            if (! empty($profilKesehatan->batas_budget) && $profilKesehatan->batas_budget > 0) {
                $query->where('harga_normal', '<=', $profilKesehatan->batas_budget);
            }
        }

        $nowWib = now('Asia/Jakarta');
        $todayStr = $nowWib->toDateString();

        // 4. Rekomendasi khusus makanan paketan (maksimal 5 menu, diacak setiap hari)
        $rekomendasiMenu = (clone $query)
            ->whereNotNull('jenis_menu')
            ->get();

        // Fallback: Jika hasil filter profil kurang dari 5, lengkapi dengan menu paketan aktif lainnya
        if ($rekomendasiMenu->count() < 5) {
            $existingIds = $rekomendasiMenu->pluck('id_menu')->toArray();

            $tambahanPaket = Menu::with(['umkm', 'jenisMenu'])
                ->whereNotNull('jenis_menu')
                ->where('status', true)
                ->where('stok_harian', '>', 0)
                ->whereHas('umkm', function ($q): void {
                    $q->terverifikasi()->buka();
                })
                ->whereNotIn('id_menu', $existingIds)
                ->get();

            $rekomendasiMenu = $rekomendasiMenu->merge($tambahanPaket);
        }

        $rekomendasiMenu = $rekomendasiMenu
            ->sortBy(fn (Menu $menu): int => crc32($todayStr . '-paket-' . $menu->id_menu))
            ->values()
            ->take(5);

        // 5. Data flash sale hanya tampil saat jam promo (20:00 - 22:00 WIB)
        //    Memilih maksimal 5 menu non-paketan dengan diskon 20% - 30%
        $currentTime = $nowWib->format('H:i:s');
        $isPromoActive = ($currentTime >= '20:00:00' && $currentTime <= '22:00:00');

        if ($isPromoActive) {
            Flashsale::ensureDailyFlashSales($todayStr);

            $flashSales = Flashsale::with(['menu.umkm', 'menu.jenisMenu'])
                ->whereDate('tgl_flash_sale', $todayStr)
                ->where('stok_sisa', '>', 0)
                ->whereHas('menu', function ($q): void {
                    $q->whereNull('jenis_menu')
                        ->where('status', true)
                        ->whereHas('umkm', function ($u): void {
                            $u->terverifikasi()->buka();
                        });
                })
                ->get()
                ->sortBy(fn (Flashsale $fs): int => crc32($todayStr . '-fs-' . $fs->id_flash_sale))
                ->values()
                ->take(5);

            $mitraList = $flashSales->map(function ($item) {
                return $item->menu?->umkm;
            })->filter()->unique('id_umkm')->values();
        } else {
            $flashSales = collect();
            $mitraList = collect();
        }

        $activeOrder = app(OrderService::class)->getActiveOrderCardForUser($idUser);
        $userSaldo = (float) (Auth::user()?->saldo ?? 0);

        return view('pelanggan.beranda', compact(
            'rekomendasiMenu',
            'profilKesehatan',
            'flashSales',
            'mitraList',
            'isPromoActive',
            'activeOrder',
            'userSaldo'
        ));
    }

    /**
     * Menampilkan daftar menu milik UMKM yang dipilih oleh pelanggan.
     */
    public function pelangganMenu(Request $request): View|RedirectResponse
    {
        $idUmkm = $request->query('id_umkm') ?? session('selected_umkm');

        if ($idUmkm) {
            $mitra = Umkm::with('user')
                ->where('id_umkm', $idUmkm)
                ->terverifikasi()
                ->first();
        } else {
            $mitra = null;
        }

        if (! $mitra) {
            return redirect()
                ->route('pelanggan.mitra')
                ->with('error', 'Silakan pilih mitra catering terlebih dahulu.');
        }

        session(['selected_umkm' => $mitra->id_umkm]);

        $isDitangguhkan = $mitra->isDitangguhkan();
        $isMitraTutup = ! $mitra->isBukaDanAktif();
        $tutupAlasan = $isDitangguhkan
            ? 'Toko ini sedang ditangguhkan sementara dan tidak menerima pesanan untuk saat ini.'
            : 'Toko sedang tutup sementara dan belum menerima pesanan saat ini.';

        $cart = session('cart', []);

        if ($isMitraTutup) {
            $menus = collect();
            if ((int) ($cart['id_umkm'] ?? 0) === (int) $mitra->id_umkm) {
                session()->forget('cart');
                $cart = [];
            }
        } else {
            $menus = Menu::with('jenisMenu')
                ->where('id_umkm', $mitra->id_umkm)
                ->where('status', true)
                ->where('stok_harian', '>', 0)
                ->orderBy('id_menu', 'desc')
                ->get();
        }
        $cartQuantities = [];
        $totalPorsi = 0;
        $subtotal = 0;

        if (! empty($cart['items']) && (int) ($cart['id_umkm'] ?? 0) === (int) $mitra->id_umkm) {
            foreach ($cart['items'] as $cartItem) {
                $menuId = (int) ($cartItem['id_menu'] ?? 0);
                $qty = (int) ($cartItem['kuantitas'] ?? 0);
                $harga = (float) ($cartItem['harga_satuan'] ?? 0);

                if ($menuId > 0 && $qty > 0) {
                    $cartQuantities[$menuId] = ($cartQuantities[$menuId] ?? 0) + $qty;
                    $totalPorsi += $qty;
                    $subtotal += $qty * $harga;
                }
            }
        }

        $cartSummary = (object) [
            'total_porsi' => $totalPorsi,
            'subtotal' => (int) $subtotal,
        ];

        return view('pelanggan.menu', compact('mitra', 'menus', 'cartQuantities', 'cartSummary', 'isMitraTutup', 'tutupAlasan'));
    }

    public function show(int|string $id_menu): View|RedirectResponse
    {
        // 1. Ambil detail menu beserta relasi UMKM dan jenisMenu
        $menu = Menu::with(['umkm.user', 'jenisMenu'])
            ->where('id_menu', $id_menu)
            ->firstOrFail();

        if (! $menu->status || ! $menu->umkm || ! $menu->umkm->isBukaDanAktif()) {
            $namaToko = $menu->umkm?->nama_vendor ?? 'Mitra';

            return redirect()
                ->route('pelanggan.mitra')
                ->with('error', "Maaf, toko {$namaToko} sedang tutup sehingga menu tidak dapat dipesan saat ini.");
        }

        // 2. Cek apakah menu ini sedang memiliki promo Flash Sale aktif hari ini
        $flashSaleAktif = Flashsale::where('id_menu', $menu->id_menu)
            ->whereDate('tgl_flash_sale', now()->toDateString())
            ->where('stok_sisa', '>', 0)
            ->first();

        // 3. Tentukan harga aktif (harga diskon jika sedang flash sale, atau harga normal)
        $hargaAktif = $flashSaleAktif ? $flashSaleAktif->harga_diskon : $menu->harga_normal;

        // 4. Ambil menu lain dari UMKM yang sama (untuk section "Menu Lainnya dari Warung Ini")
        $menuLainnya = Menu::with('jenisMenu')
            ->where('id_umkm', $menu->id_umkm)
            ->where('id_menu', '!=', $menu->id_menu)
            ->where('stok_harian', '>', 0)
            ->take(4)
            ->get();

        // 5. Ambil daftar Meal Plan milik pelanggan (jika pelanggan ingin menambahkan menu ini ke Meal Plan)
        $mealPlans = Auth::check()
            ? \App\Models\MealPlan::where('id_user', Auth::id())->orderBy('id_plan', 'desc')->get()
            : collect();

        return view('pelanggan.detail_menu', compact(
            'menu',
            'flashSaleAktif',
            'hargaAktif',
            'menuLainnya',
            'mealPlans'
        ));
    }

}
