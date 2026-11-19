<?php

namespace App\Http\Controllers;

use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Umkm;
use App\Services\OrderService;
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
     * Menyimpan perubahan kuantitas & jam makan dari halaman Keranjang, lalu lanjut ke Pembayaran.
     */
    public function updateKeranjang(Request $request): RedirectResponse
    {
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
            'metode_pengiriman'     => 'required|string',
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
}
