<?php

namespace App\Http\Controllers;

use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Umkm;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FlashSaleController extends Controller
{
    /**
     * Menampilkan beranda pelanggan dengan daftar promo Flash Sale aktif dan filter per mitra.
     * Sistem promo dimulai setiap hari pada pukul 20:00 - 22:00 (jam 8 - 10 malam).
     */
    public function berandaPelanggan(Request $request): View|JsonResponse
    {
        $now = Carbon::now('Asia/Jakarta');
        $startTime = '20:00:00';
        $endTime = '22:00:00';
        $currentTime = $now->format('H:i:s');
        $isPromoActive = ($currentTime >= $startTime && $currentTime <= $endTime);
        $selectedMitra = $request->query('mitra', 'all');

        if ($isPromoActive) {
            $todayStr = $now->toDateString();
            Flashsale::ensureDailyFlashSales($todayStr);

            // Ambil maksimal 5 menu non-paketan dengan relasi menu dan umkm saat jam promo aktif (20:00 - 22:00)
            $query = Flashsale::with(['menu.umkm', 'menu.jenisMenu'])
                ->whereDate('tgl_flash_sale', $todayStr)
                ->where('stok_sisa', '>', 0)
                ->whereHas('menu', function ($q): void {
                    $q->whereNull('jenis_menu')
                        ->where('status', true)
                        ->whereHas('umkm', function ($u): void {
                            $u->terverifikasi()->buka();
                        });
                });

            // Filter berdasarkan mitra yang dipilih pelanggan
            if ($selectedMitra && $selectedMitra !== 'all') {
                $query->whereHas('menu.umkm', function ($q) use ($selectedMitra): void {
                    if (is_numeric($selectedMitra)) {
                        $q->where('id_umkm', (int) $selectedMitra);
                    } else {
                        $q->where('nama_vendor', $selectedMitra);
                    }
                });
            }

            $flashSales = $query->orderBy('id_flash_sale', 'desc')->take(5)->get();

            // Ambil daftar mitra unik yang memiliki menu flash sale untuk tombol filter mitra
            $allFlashSales = Flashsale::with('menu.umkm')
                ->whereDate('tgl_flash_sale', $todayStr)
                ->where('stok_sisa', '>', 0)
                ->whereHas('menu', function ($q): void {
                    $q->whereNull('jenis_menu')->where('status', true);
                })
                ->take(5)
                ->get();

            $mitraList = $allFlashSales->map(function ($fs) {
                return $fs->menu?->umkm;
            })->filter()->unique('id_umkm')->values();
        } else {
            $flashSales = collect();
            $mitraList = collect();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_promo_time' => $isPromoActive,
                'waktu_mulai' => $startTime,
                'waktu_selesai' => $endTime,
                'total' => $flashSales->count(),
                'data' => $flashSales,
            ]);
        }

        return view('pelanggan.beranda', compact(
            'flashSales',
            'mitraList',
            'selectedMitra',
            'isPromoActive',
            'startTime',
            'endTime'
        ));
    }

    /**
     * Endpoint API / eksplorasi flash sale untuk pelanggan.
     */
    public function pelangganFlashSale(Request $request): View|JsonResponse
    {
        return $this->berandaPelanggan($request);
    }

    /**
     * Menampilkan daftar flash sale milik UMKM yang sedang login.
     */
    public function index(Request $request): View|JsonResponse
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        if (! $umkm) {
            $umkm = Umkm::first();
        }

        $query = Flashsale::with('menu');
        if ($umkm) {
            $query->whereHas('menu', function ($q) use ($umkm): void {
                $q->where('id_umkm', $umkm->id_umkm);
            });
        }

        $flashSales = $query->orderBy('id_flash_sale', 'desc')->get();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $flashSales,
            ]);
        }

        return view('umkm.manajemen_menu', [
            'flashSales' => $flashSales,
        ]);
    }

    /**
     * Menyimpan data flash sale baru dari UMKM.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_menu' => 'required|exists:menu,id_menu',
            'harga_diskon' => 'required|numeric|min:0',
            'kuantitas' => 'required|integer|min:1',
            'stok_sisa' => 'nullable|integer|min:0',
            'tgl_flash_sale' => 'nullable|date',
            'waktu_mulai' => 'nullable|string',
            'waktu_selesai' => 'nullable|string',
        ]);

        // Default: mulai jam 20:00 dan selesai jam 22:00
        $validated['waktu_mulai'] = $request->filled('waktu_mulai') ? $request->input('waktu_mulai') : '20:00:00';
        $validated['waktu_selesai'] = $request->filled('waktu_selesai') ? $request->input('waktu_selesai') : '22:00:00';
        $validated['tgl_flash_sale'] = $request->filled('tgl_flash_sale') ? $request->input('tgl_flash_sale') : Carbon::today()->toDateString();
        $validated['stok_sisa'] = $request->filled('stok_sisa') ? (int) $request->input('stok_sisa') : (int) $validated['kuantitas'];

        Flashsale::create($validated);

        return back()->with('success', 'Promo Flash Sale berhasil diaktifkan!');
    }

    /**
     * Memperbarui informasi promo flash sale.
     */
    public function update(Request $request, int|string $id_flash_sale): RedirectResponse
    {
        $flashSale = Flashsale::findOrFail($id_flash_sale);

        $validated = $request->validate([
            'harga_diskon' => 'required|numeric|min:0',
            'kuantitas' => 'required|integer|min:1',
            'stok_sisa' => 'required|integer|min:0',
            'tgl_flash_sale' => 'nullable|date',
            'waktu_mulai' => 'nullable|string',
            'waktu_selesai' => 'nullable|string',
        ]);

        $flashSale->update($validated);

        return back()->with('success', 'Promo Flash Sale berhasil diperbarui!');
    }

    /**
     * Menghapus promo flash sale.
     */
    public function destroy(int|string $id_flash_sale): RedirectResponse
    {
        $flashSale = Flashsale::findOrFail($id_flash_sale);
        $flashSale->delete();

        return back()->with('deleted', 'Promo Flash Sale berhasil dihapus!');
    }
}
