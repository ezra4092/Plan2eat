<?php

namespace App\Http\Controllers;

use App\Models\DetailMealPlan;
use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Umkm;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Halaman Keranjang:
     * - Jika ada parameter ?menu=ID, otomatis tambahkan ke session keranjang.
     * - Membedakan menu paketan (kuantitas = 1, atur jam makan setiap menu)
     *   vs menu satuan (atur kuantitas porsi + jam makan).
     */
    public function keranjang(Request $request): View|RedirectResponse
    {
        $cart = session('cart', [
            'id_umkm' => null,
            'items'   => [],
        ]);

        if ($request->filled('menu')) {
            $menu = Menu::with(['umkm', 'jenisMenu'])->findOrFail($request->query('menu'));
            $idFlashSale = $request->query('fs');
            $flashSale = $idFlashSale ? Flashsale::find($idFlashSale) : null;

            // Jika keranjang berisi menu dari UMKM berbeda, reset keranjang agar 1 pesanan = 1 UMKM
            if (!empty($cart['id_umkm']) && (int) $cart['id_umkm'] !== (int) $menu->id_umkm) {
                $cart = [
                    'id_umkm' => $menu->id_umkm,
                    'items'   => [],
                ];
            }

            $cart['id_umkm'] = $menu->id_umkm;

            // Tentukan apakah menu ini adalah menu paketan
            $isPaket = !is_null($menu->jenis_menu)
                || str_contains(strtolower($menu->nama_makanan), 'paket')
                || str_contains(strtolower($menu->nama_makanan), 'box')
                || str_contains(strtolower($menu->nama_makanan), 'plan');

            $hargaSatuan = $flashSale ? (float) $flashSale->harga_diskon : (float) $menu->harga_normal;

            // Cek apakah item yang sama sudah ada di keranjang
            $existingIndex = null;
            foreach ($cart['items'] as $idx => $item) {
                if ((int) $item['id_menu'] === (int) $menu->id_menu && ($item['id_flash_sale'] ?? null) == $idFlashSale) {
                    $existingIndex = $idx;
                    break;
                }
            }

            if ($existingIndex !== null) {
                if (!$isPaket) {
                    $cart['items'][$existingIndex]['kuantitas'] += 1;
                }
            } else {
                $cart['items'][] = [
                    'id_menu'       => $menu->id_menu,
                    'id_flash_sale' => $flashSale?->id_flash_sale,
                    'nama_makanan'  => $menu->nama_makanan,
                    'jenis_nama'    => $menu->jenisMenu?->nama ?? ($isPaket ? 'Menu Paketan' : 'Menu Satuan'),
                    'is_paket'      => $isPaket,
                    'harga_satuan'  => $hargaSatuan,
                    'stok_maks'     => $flashSale ? $flashSale->stok_sisa : $menu->stok_harian,
                    'kuantitas'     => 1,
                    'waktu'         => $isPaket ? '08:00' : '12:00', // Menu 1 paket default jam 08:00
                ];

                // Jika menu paketan dan baru 1 menu di keranjang, sertakan menu pendamping dari UMKM yang sama sebagai Menu 2
                if ($isPaket && count($cart['items']) === 1) {
                    $companion = Menu::where('id_umkm', $menu->id_umkm)
                        ->where('id_menu', '!=', $menu->id_menu)
                        ->where('stok_harian', '>', 0)
                        ->first();

                    if ($companion) {
                        $cart['items'][] = [
                            'id_menu'       => $companion->id_menu,
                            'id_flash_sale' => null,
                            'nama_makanan'  => $companion->nama_makanan,
                            'jenis_nama'    => $companion->jenisMenu?->nama ?? 'Menu Pendamping Paket',
                            'is_paket'      => true,
                            'harga_satuan'  => (float) $companion->harga_normal,
                            'stok_maks'     => $companion->stok_harian,
                            'kuantitas'     => 1,
                            'waktu'         => '17:00', // Menu 2 paket default jam 17:00 (5 sore)
                        ];
                    }
                }
            }

            session(['cart' => $cart]);

            // Redirect bersih tanpa query string
            return redirect()->route('pelanggan.keranjang');
        }

        $umkm = !empty($cart['id_umkm']) ? Umkm::find($cart['id_umkm']) : null;
        $menuLainUmkm = $umkm
            ? Menu::with('jenisMenu')->where('id_umkm', $umkm->id_umkm)->where('stok_harian', '>', 0)->get()
            : collect();

        return view('pelanggan.keranjang', compact('cart', 'umkm', 'menuLainUmkm'));
    }

    /**
     * Menyimpan perubahan kuantitas & jam makan dari halaman Keranjang atau Menu Mitra, lalu lanjut/balas JSON.
     */
    public function updateKeranjang(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->has('id_menu')) {
            $idMenu = (int) $request->input('id_menu', 0);
            $requestedQty = max(0, (int) $request->input('qty', 0));
            $menu = Menu::with('jenisMenu')->find($idMenu);

            if (! $menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu tidak ditemukan.',
                ], 404);
            }

            $cart = session('cart', [
                'id_umkm' => null,
                'items' => [],
            ]);

            if (! empty($cart['id_umkm']) && (int) $cart['id_umkm'] !== (int) $menu->id_umkm) {
                $cart = [
                    'id_umkm' => $menu->id_umkm,
                    'items' => [],
                ];
            }

            $cart['id_umkm'] = $menu->id_umkm;
            $qty = min((int) $menu->stok_harian, $requestedQty);

            $existingIndex = null;
            foreach ($cart['items'] as $idx => $item) {
                if ((int) $item['id_menu'] === (int) $menu->id_menu && empty($item['id_flash_sale'])) {
                    $existingIndex = $idx;
                    break;
                }
            }

            if ($qty === 0) {
                if ($existingIndex !== null) {
                    unset($cart['items'][$existingIndex]);
                    $cart['items'] = array_values($cart['items']);
                }
                if (empty($cart['items'])) {
                    $cart['id_umkm'] = null;
                }
            } elseif ($existingIndex !== null) {
                $cart['items'][$existingIndex]['kuantitas'] = $qty;
                $cart['items'][$existingIndex]['stok_maks'] = (int) $menu->stok_harian;
            } else {
                $cart['items'][] = [
                    'id_menu' => $menu->id_menu,
                    'id_flash_sale' => null,
                    'nama_makanan' => $menu->nama_makanan,
                    'jenis_nama' => $menu->jenisMenu?->nama ?? 'Menu Satuan',
                    'is_paket' => false,
                    'harga_satuan' => (float) $menu->harga_normal,
                    'stok_maks' => (int) $menu->stok_harian,
                    'kuantitas' => $qty,
                    'waktu' => '12:00',
                ];
            }

            session(['cart' => $cart]);

            $totalPorsi = 0;
            $subtotal = 0;
            foreach ($cart['items'] as $item) {
                $itemQty = (int) ($item['kuantitas'] ?? 0);
                $itemHarga = (float) ($item['harga_satuan'] ?? 0);
                $totalPorsi += $itemQty;
                $subtotal += $itemQty * $itemHarga;
            }

            return response()->json([
                'success' => true,
                'total_porsi' => $totalPorsi,
                'subtotal' => (int) $subtotal,
            ]);
        }

        $cart = session('cart');

        if (empty($cart['items'])) {
            return redirect()->route('pelanggan.beranda')->with('error', 'Keranjang Anda masih kosong.');
        }

        $itemsInput = $request->input('items', []);

        foreach ($cart['items'] as $index => &$item) {
            if (isset($itemsInput[$index])) {
                // Jika menu paketan, kuantitas tetap 1; jika satuan, ikuti input user
                if ($item['is_paket']) {
                    $item['kuantitas'] = 1;
                } else {
                    $qty = max(1, min((int) $item['stok_maks'], (int) ($itemsInput[$index]['kuantitas'] ?? 1)));
                    $item['kuantitas'] = $qty;
                }

                $item['waktu'] = $itemsInput[$index]['waktu'] ?? '12:00';
            }
        }
        unset($item);

        $cart['tgl_mulai']   = $request->input('tgl_mulai', now()->toDateString());
        $cart['tgl_selesai'] = $request->input('tgl_selesai');

        session(['cart' => $cart]);

        return redirect()->route('pelanggan.checkout');
    }

    /**
     * Menghapus 1 item dari keranjang.
     */
    public function hapusItemKeranjang(int $index): RedirectResponse
    {
        $cart = session('cart');

        if (isset($cart['items'][$index])) {
            unset($cart['items'][$index]);
            $cart['items'] = array_values($cart['items']);

            if (empty($cart['items'])) {
                session()->forget('cart');
            } else {
                session(['cart' => $cart]);
            }
        }

        return redirect()->route('pelanggan.keranjang')->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Halaman Pembayaran / Checkout.
     */
    public function checkoutPage(): View|RedirectResponse
    {
        $cart = session('cart');

        if (empty($cart['items'])) {
            return redirect()->route('pelanggan.beranda')->with('error', 'Silakan pilih menu terlebih dahulu.');
        }

        $umkm = Umkm::findOrFail($cart['id_umkm']);

        $totalHarga = collect($cart['items'])->sum(function ($item) {
            return $item['harga_satuan'] * $item['kuantitas'];
        });

        // Metode pembayaran yang disediakan UMKM
        $metodePembayaranUmkm = is_array($umkm->metode_pembayaran)
            ? $umkm->metode_pembayaran
            : (json_decode((string) $umkm->metode_pembayaran, true) ?: ['QRIS', 'Transfer Bank', 'Tunai']);

        return view('pelanggan.pembayaran', compact('cart', 'umkm', 'totalHarga', 'metodePembayaranUmkm'));
    }

    /**
     * Memproses penyimpanan pesanan & detail pesanan ke database via OrderService.
     */
    public function store(Request $request, OrderService $orderService): RedirectResponse
    {
        $validated = $request->validate([
            'id_umkm'               => 'required|exists:umkm,id_umkm',
            'id_plan'               => 'nullable|exists:meal_plan,id_plan',
            'metode_pembayaran'     => 'required|string',
            // 'metode_pengiriman'     => 'required|string',
            'jadwal_pengiriman'     => 'nullable|string',
            'tgl_mulai'             => 'nullable|string',
            'tgl_selesai'           => 'nullable|string',
            'items'                 => 'required|array|min:1',
            'items.*.id_menu'       => 'required|exists:menu,id_menu',
            'items.*.id_flash_sale' => 'nullable|exists:flash_sale,id_flash_sale',
            'items.*.kuantitas'     => 'required|integer|min:1',
            'items.*.waktu'         => 'nullable|string',
            'status_pesanan'        => 'nullable|string',
        ]);

        try {
            // Karena pembayaran disimulasikan lunas setelah jeda 5 detik, status di-set ke 'persiapan'
            $validated['status_pesanan'] = 'persiapan';

            $userId = Auth::id() ?? 2;
            $pesanan = $orderService->buatPesanan($userId, $validated);

            // Kosongkan keranjang setelah pesanan berhasil dibuat
            session()->forget('cart');

            return redirect()
                ->route('pelanggan.pesanan-detail', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat! Pembayaran Anda telah terverifikasi lunas.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Detail Pesanan (konfirmasi akhir setelah bayar).
     */
    public function show(int|string $id_pesanan): View
    {
        $userId = Auth::id() ?? 2;

        $pesanan = Pesanan::with(['umkm', 'detailPesanan.menu', 'detailPesanan.flashSale'])
            ->where('id_pesanan', $id_pesanan)
            ->firstOrFail();

        return view('pelanggan.detail_pesanan', compact('pesanan'));
    }

    /**
     * Menampilkan daftar pesanan masuk untuk UMKM (tahap persiapan, masak, siap diambil).
     */
    public function pesananMasuk(): View
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        if (! $umkm) {
            $umkm = Umkm::first();
        }

        $query = Pesanan::with(['user', 'detailPesanan.menu'])
            ->whereIn('status_pesanan', ['persiapan', 'masak', 'siap diambil'])
            ->orderBy('id_pesanan', 'desc');

        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $pesanan = $query->get()->map(function (Pesanan $order): array {
            return [
                'id_pesanan' => $order->id_pesanan,
                'status_pesanan' => strtolower((string) $order->status_pesanan),
                'nama_pelanggan' => $order->user->nama_lengkap ?? 'Pelanggan',
                'lokasi' => $order->metode_pengiriman ?? '',
                'items' => $order->detailPesanan->map(fn ($detail): array => [
                    'kuantitas' => $detail->kuantitas,
                    'nama' => $detail->menu->nama_makanan ?? 'Menu Katering',
                ])->values()->all(),
            ];
        });

        return view('umkm.manajemen_pesanan', compact('pesanan'));
    }

    /**
     * Menampilkan detail pesanan masuk milik UMKM.
     */
    public function detailPesananMasuk(int|string $id_pesanan): JsonResponse|View
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        $query = Pesanan::with(['user', 'umkm', 'detailPesanan.menu', 'detailPesanan.flashSale'])
            ->where('id_pesanan', $id_pesanan);

        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $pesanan = $query->firstOrFail();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $pesanan,
            ]);
        }

        return view('pelanggan.detail_pesanan', compact('pesanan'));
    }

    /**
     * Memperbarui status pesanan UMKM (persiapan -> masak -> siap diambil -> selesai).
     */
    public function updateStatus(Request $request, int|string $id_pesanan): JsonResponse|RedirectResponse
    {
        $request->validate([
            'status_pesanan' => 'required|string',
        ]);

        $rawStatus = strtolower(trim((string) $request->input('status_pesanan')));
        if ($rawStatus === 'diambil') {
            $rawStatus = 'siap diambil';
        }

        $allowedStatuses = ['persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'];
        if (! in_array($rawStatus, $allowedStatuses, true)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pesanan tidak valid.',
                ], 422);
            }

            return back()->with('error', 'Status pesanan tidak valid.');
        }

        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        $query = Pesanan::where('id_pesanan', $id_pesanan);
        if ($umkm) {
            $query->where('id_umkm', $umkm->id_umkm);
        }

        $pesanan = $query->firstOrFail();
        $pesanan->update([
            'status_pesanan' => $rawStatus,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Status pesanan #{$pesanan->id_pesanan} diubah menjadi {$rawStatus}.",
                'status_pesanan' => $pesanan->status_pesanan,
            ]);
        }

        return redirect()
            ->route('umkm.pesanan')
            ->with('success', "Status pesanan #{$pesanan->id_pesanan} diubah menjadi {$rawStatus}.");
    }

    /**
     * Halaman Langganan Pelanggan:
     * - Status pesanan hari ini (aktif / mitra tutup / belum ada pesanan)
     * - Kalender rentang 7 hari (hari ini + 6 hari ke depan) beserta menu/meal plan per tanggal
     * - Riwayat pesanan pelanggan beserta fitur "Pesan lagi"
     */
    public function langganan(): View
    {
        $userId = Auth::id();
        $today = Carbon::today();
        $todayStr = $today->toDateString();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(6); // Minggu
        $startRangeStr = $startOfWeek->toDateString();
        $endRangeStr = $endOfWeek->toDateString();

        $allOrders = Pesanan::with(['umkm', 'detailPesanan.menu'])
            ->where('id_user', $userId)
            ->orderBy('id_pesanan', 'desc')
            ->get();

        // Cari pesanan aktif hari ini (tidak dibatalkan, mencakup tanggal hari ini)
        $todayOrders = $allOrders->filter(function (Pesanan $order) use ($todayStr): bool {
            if (strtolower((string) $order->status_pesanan) === 'dibatalkan') {
                return false;
            }
            $mulai = $order->tgl_mulai ? Carbon::parse($order->tgl_mulai)->toDateString() : null;
            $selesai = $order->tgl_selesai ? Carbon::parse($order->tgl_selesai)->toDateString() : $mulai;

            return $mulai !== null && $mulai <= $todayStr && $selesai >= $todayStr;
        });

        $todayOrder = $todayOrders->first(fn (Pesanan $o): bool => strtolower((string) $o->status_pesanan) !== 'selesai')
            ?? $todayOrders->first();

        $subscriptionStatus = 'empty';
        $activeOrder = null;

        if ($todayOrder) {
            $isMitraTutup = $todayOrder->umkm
                && strtolower((string) $todayOrder->umkm->status_operasional) === 'tutup';

            if ($isMitraTutup) {
                $subscriptionStatus = 'holiday';
            } else {
                $subscriptionStatus = 'active';
                $firstDetail = $todayOrder->detailPesanan->first();
                $firstMenu = $firstDetail?->menu;

                $jamTiba = $firstDetail?->waktu
                    ? substr((string) $firstDetail->waktu, 0, 5)
                    : ($todayOrder->jadwal_pengiriman ? Carbon::parse($todayOrder->jadwal_pengiriman)->format('H:i') : '12:00');

                $statusLower = strtolower((string) $todayOrder->status_pesanan);
                $progressStep = match ($statusLower) {
                    'masak' => 2,
                    'siap diambil', 'selesai' => 3,
                    default => 1,
                };
                $progressLabel = match ($statusLower) {
                    'menunggu pembayaran' => 'Menunggu Pembayaran',
                    'persiapan' => 'Sedang Disiapkan',
                    'masak' => 'Sedang Dimasak',
                    'siap diambil' => 'Siap Diambil',
                    'selesai' => 'Pesanan Selesai',
                    default => ucfirst($statusLower),
                };

                $fotoPath = $firstMenu?->foto_menu;
                $fotoUrl = $fotoPath
                    ? (str_starts_with($fotoPath, 'http') ? $fotoPath : asset('storage/' . $fotoPath))
                    : 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80';

                $extraCount = max(0, $todayOrder->detailPesanan->count() - 1);
                $portionDetail = ($firstDetail?->kuantitas ?? 1) . 'x Porsi';
                if ($extraCount > 0) {
                    $portionDetail .= " (+{$extraCount} menu lainnya)";
                }

                $activeOrder = [
                    'id_pesanan'     => $todayOrder->id_pesanan,
                    'schedule_label' => "Terjadwal • Tiba {$jamTiba} WIB",
                    'mitra_name'     => $todayOrder->umkm->nama_vendor ?? 'Mitra Plan2Eat',
                    'menu_name'      => $firstMenu?->nama_makanan ?? 'Menu Katering',
                    'portion_detail' => $portionDetail,
                    'badge_text'     => 'Pesanan Hari Ini',
                    'image'          => $fotoUrl,
                    'progress_step'  => $progressStep,
                    'progress_label' => $progressLabel,
                    'detail_url'     => route('pelanggan.pesanan-detail', $todayOrder->id_pesanan),
                ];
            }
        }

        // Ambil jadwal Meal Plan user dalam rentang Senin - Minggu
        $detailMealPlans = DetailMealPlan::with(['menu.umkm', 'mealPlan'])
            ->whereHas('mealPlan', function ($q) use ($userId): void {
                $q->where('id_user', $userId);
            })
            ->whereBetween('jadwal_makan', [$startRangeStr, $endRangeStr])
            ->get();

        // Susun data kalender 7 hari (Senin s/d Minggu pada minggu ini)
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $d = $startOfWeek->copy()->addDays($i);
            $dStr = $d->toDateString();
            $dayItems = [];
            $dayHasClosedMitra = false;

            // 1. Item dari Pesanan pada tanggal ini
            foreach ($allOrders as $ord) {
                if (strtolower((string) $ord->status_pesanan) === 'dibatalkan') {
                    continue;
                }
                $mulai = $ord->tgl_mulai ? Carbon::parse($ord->tgl_mulai)->toDateString() : null;
                $selesai = $ord->tgl_selesai ? Carbon::parse($ord->tgl_selesai)->toDateString() : $mulai;

                if ($mulai !== null && $mulai <= $dStr && $selesai >= $dStr) {
                    if ($ord->umkm && strtolower((string) $ord->umkm->status_operasional) === 'tutup') {
                        $dayHasClosedMitra = true;
                    }
                    foreach ($ord->detailPesanan as $det) {
                        $jam = $det->waktu
                            ? substr((string) $det->waktu, 0, 5) . ' WIB'
                            : ($ord->jadwal_pengiriman ? Carbon::parse($ord->jadwal_pengiriman)->format('H:i') . ' WIB' : '-');

                        $dayItems[] = [
                            'source'    => 'Pesanan',
                            'menu_nama' => $det->menu->nama_makanan ?? 'Menu Katering',
                            'waktu'     => $jam,
                            'mitra'     => $ord->umkm->nama_vendor ?? 'Mitra Plan2Eat',
                            'kuantitas' => $det->kuantitas,
                        ];
                    }
                }
            }

            // 2. Item dari Meal Plan pada tanggal ini
            foreach ($detailMealPlans as $dmp) {
                if (Carbon::parse($dmp->jadwal_makan)->toDateString() === $dStr) {
                    $dayItems[] = [
                        'source'    => 'Meal Plan',
                        'menu_nama' => $dmp->menu->nama_makanan ?? 'Menu Meal Plan',
                        'waktu'     => $dmp->waktu_makan ?? '-',
                        'mitra'     => $dmp->menu?->umkm?->nama_vendor ?? ($dmp->mealPlan->judul_plan ?? 'Meal Plan'),
                        'kuantitas' => 1,
                    ];
                }
            }

            // Warna status kalender:
            // Hari sebelumnya: hijau jika ada jadwal, merah muda jika kosong; Hari ini: hijau/holiday; Hari berikutnya (besok+): abu-abu
            if ($d->lt($today)) {
                $state = ! empty($dayItems) ? 'completed' : 'missed';
            } elseif ($d->eq($today)) {
                $state = $dayHasClosedMitra ? 'holiday' : 'completed';
            } else {
                $state = 'upcoming';
            }

            $days[] = [
                'date'       => $d->format('j'),
                'full_date'  => $dStr,
                'label_date' => $d->translatedFormat('d M Y'),
                'day'        => $d->format('D'),
                'state'      => $state,
                'selected'   => $d->eq($today),
                'is_today'   => $d->eq($today),
                'is_holiday' => $dayHasClosedMitra,
                'items'      => $dayItems,
            ];
        }

        $riwayatPesanan = $allOrders;

        return view('pelanggan.langganan', compact(
            'subscriptionStatus',
            'activeOrder',
            'days',
            'riwayatPesanan'
        ));
    }

    /**
     * Memasukkan ulang item dari riwayat pesanan ke keranjang untuk dipesan lagi.
     */
    public function pesanLagi(int|string $id_pesanan): RedirectResponse
    {
        $pesanan = Pesanan::with(['umkm', 'detailPesanan.menu.jenisMenu'])
            ->where('id_user', Auth::id())
            ->where('id_pesanan', $id_pesanan)
            ->firstOrFail();

        $cart = [
            'id_umkm' => $pesanan->id_umkm,
            'items'   => [],
        ];

        foreach ($pesanan->detailPesanan as $detail) {
            $menu = $detail->menu;
            if (! $menu || $menu->stok_harian <= 0) {
                continue;
            }

            $isPaket = ! is_null($menu->jenis_menu)
                || str_contains(strtolower($menu->nama_makanan), 'paket')
                || str_contains(strtolower($menu->nama_makanan), 'box')
                || str_contains(strtolower($menu->nama_makanan), 'plan');

            $cart['items'][] = [
                'id_menu'       => $menu->id_menu,
                'id_flash_sale' => null,
                'nama_makanan'  => $menu->nama_makanan,
                'jenis_nama'    => $menu->jenisMenu?->nama ?? ($isPaket ? 'Menu Paketan' : 'Menu Satuan'),
                'is_paket'      => $isPaket,
                'harga_satuan'  => (float) $menu->harga_normal,
                'stok_maks'     => $menu->stok_harian,
                'kuantitas'     => $isPaket ? 1 : max(1, min((int) $menu->stok_harian, (int) $detail->kuantitas)),
                'waktu'         => $detail->waktu ? substr((string) $detail->waktu, 0, 5) : '12:00',
            ];
        }

        if (empty($cart['items'])) {
            return redirect()
                ->route('pelanggan.langganan')
                ->with('error', 'Maaf, stok menu pada pesanan tersebut saat ini sedang kosong.');
        }

        session(['cart' => $cart]);

        return redirect()
            ->route('pelanggan.keranjang')
            ->with('success', 'Menu dari pesanan sebelumnya telah ditambahkan ke keranjang!');
    }
}
