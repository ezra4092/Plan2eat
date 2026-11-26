<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Umkm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UmkmController extends Controller
{
    /**
     * Menampilkan dashboard operasional UMKM beserta metrik dan grafik porsi mingguan.
     */
    public function dashboard(Request $request): View
    {
        $idUser = Auth::id();
        $umkm = $idUser ? Umkm::where('id_user', $idUser)->first() : null;

        if (! $umkm) {
            $umkm = Umkm::firstOrFail();
        }

        $range = $request->query('range') === '30_days' ? '30_days' : '7_days';

        $orders = Pesanan::with('detailPesanan')
            ->where('id_umkm', $umkm->id_umkm)
            ->get();

        $activeStatuses = ['persiapan', 'masak', 'siap diambil'];
        $activeOrders = $orders->filter(
            fn (Pesanan $order): bool => in_array(strtolower((string) $order->status_pesanan), $activeStatuses, true)
        );
        $completedOrders = $orders->filter(
            fn (Pesanan $order): bool => strtolower((string) $order->status_pesanan) === 'selesai'
        );
        $validOrders = $orders->filter(
            fn (Pesanan $order): bool => ! in_array(strtolower((string) $order->status_pesanan), ['dibatalkan', 'menunggu pembayaran'], true)
        );

        $pesananPerluDiproses = $activeOrders->count();
        $porsiPerluDiproses = (int) $activeOrders->sum(
            fn (Pesanan $order): int => (int) $order->detailPesanan->sum('kuantitas')
        );
        $pesananSelesai = $completedOrders->count();

        $totalOperasional = $pesananPerluDiproses + $pesananSelesai;
        $progressDiproses = $totalOperasional > 0 ? (int) round(($pesananPerluDiproses / $totalOperasional) * 100) : 0;
        $progressSelesai = $totalOperasional > 0 ? (int) round(($pesananSelesai / $totalOperasional) * 100) : 0;

        $pelangganAktif = $validOrders->pluck('id_user')->unique()->count();
        $batasMinggu = Carbon::now('Asia/Jakarta')->subDays(7);
        $pelangganMingguIni = $validOrders
            ->filter(fn (Pesanan $order): bool => $order->tgl_mulai && Carbon::parse($order->tgl_mulai)->gte($batasMinggu))
            ->pluck('id_user')
            ->unique()
            ->count();

        $ulasanStats = DB::table('ulasan')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->where('pesanan.id_umkm', $umkm->id_umkm)
            ->selectRaw('COUNT(ulasan.id_ulasan) as total, COALESCE(AVG(ulasan.rating), 0) as avg_rating')
            ->first();

        $totalUlasan = (int) ($ulasanStats->total ?? 0);
        $ratingWarung = $totalUlasan > 0
            ? round((float) $ulasanStats->avg_rating, 1)
            : round((float) ($umkm->rating ?? 0), 1);

        $totalPendapatan = 0.0;
        $pendapatanFlashSale = 0.0;

        foreach ($validOrders as $order) {
            if ($order->detailPesanan->isNotEmpty()) {
                $totalPendapatan += (float) $order->detailPesanan->sum('subtotal');
                $pendapatanFlashSale += (float) $order->detailPesanan
                    ->filter(fn ($detail): bool => ! empty($detail->id_flash_sale))
                    ->sum('subtotal');
            } else {
                $totalPendapatan += max(0.0, (float) $order->total_pembayaran - (float) ($order->biaya_admin ?? 0));
            }
        }

        $dayMeta = [
            1 => ['key' => 'senin', 'label' => 'Senin'],
            2 => ['key' => 'selasa', 'label' => 'Selasa'],
            3 => ['key' => 'rabu', 'label' => 'Rabu'],
            4 => ['key' => 'kamis', 'label' => 'Kamis'],
            5 => ['key' => 'jumat', 'label' => 'Jumat'],
            6 => ['key' => 'sabtu', 'label' => 'Sabtu'],
            7 => ['key' => 'minggu', 'label' => 'Minggu'],
        ];

        $kateringPerDay = array_fill_keys(array_column($dayMeta, 'key'), 0);
        $flashSalePerDay = array_fill_keys(array_column($dayMeta, 'key'), 0);

        $chartCutoff = $range === '30_days'
            ? Carbon::now('Asia/Jakarta')->subDays(30)->startOfDay()
            : Carbon::now('Asia/Jakarta')->subDays(7)->startOfDay();

        foreach ($validOrders as $order) {
            $orderDate = $order->tgl_mulai ?? $order->jadwal_pengiriman;
            if (! $orderDate) {
                continue;
            }
            $carbonDate = Carbon::parse($orderDate)->timezone('Asia/Jakarta');
            if ($carbonDate->lt($chartCutoff)) {
                continue;
            }

            $dayKey = $dayMeta[$carbonDate->dayOfWeekIso]['key'] ?? 'senin';
            foreach ($order->detailPesanan as $detail) {
                $qty = (int) $detail->kuantitas;
                if (! empty($detail->id_flash_sale)) {
                    $flashSalePerDay[$dayKey] += $qty;
                } else {
                    $kateringPerDay[$dayKey] += $qty;
                }
            }
        }

        $rawMax = max(10, (int) max($kateringPerDay), (int) max($flashSalePerDay));
        $chartMax = (int) (ceil($rawMax / 5) * 5);
        $chartMid = (int) round($chartMax / 2);

        $chartDays = array_values($dayMeta);
        $isSuspended = $umkm->isDitangguhkan();
        $suspendedUntilText = $isSuspended
            ? $umkm->user?->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB'
            : null;
        $suspendReason = $umkm->catatan;

        return view('umkm.dashboard', compact(
            'umkm',
            'isSuspended',
            'suspendedUntilText',
            'suspendReason',
            'range',
            'porsiPerluDiproses',
            'pelangganAktif',
            'pelangganMingguIni',
            'pesananPerluDiproses',
            'progressDiproses',
            'ratingWarung',
            'totalUlasan',
            'pesananSelesai',
            'progressSelesai',
            'totalPendapatan',
            'pendapatanFlashSale',
            'chartDays',
            'kateringPerDay',
            'flashSalePerDay',
            'chartMax',
            'chartMid'
        ));
    }

    /**
     * Menampilkan profil akun dan profil UMKM.
     */
    public function index(): View
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_user', $user->id_user)->firstOrFail();
        $isSuspended = $umkm->isDitangguhkan();
        $suspendedUntilText = $isSuspended
            ? $user->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB'
            : null;
        $suspendReason = $umkm->catatan;

        return view('umkm.profil', compact('user', 'umkm', 'isSuspended', 'suspendedUntilText', 'suspendReason'));
    }

    /**
     * Memperbarui data user dan UMKM ke database.
     */
    public function update(Request $request, int|string $id_umkm): RedirectResponse
    {
        // Memastikan UMKM merupakan milik user yang login
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_umkm', $id_umkm)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        // Mengubah input kosong menjadi NULL
        $request->merge([
            'deskripsi_toko' => $request->filled('deskripsi_toko')
                ? trim((string) $request->input('deskripsi_toko'))
                : null,
        ]);

        // Validasi data akun
        $validatedUser = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(
                    $user->id_user,
                    'id_user'
                ),
            ],
        ]);

        // Validasi data UMKM
        $validatedUmkm = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'deskripsi_toko' => 'nullable|string',
            'alamat_lengkap' => 'required|string',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
            'status_operasional' => 'required|string|max:50',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($umkm->isDitangguhkan()) {
            if (strtolower((string) $validatedUmkm['status_operasional']) === 'buka') {
                $untilLabel = $user->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

                return back()
                    ->withInput()
                    ->with('error', "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} sehingga toko tidak dapat dibuka.");
            }
            $validatedUmkm['status_operasional'] = 'Tutup';
        }

        // Handle upload foto_toko jika ada
        if ($request->hasFile('foto_toko')) {
            if ($umkm->foto_toko && Storage::disk('public')->exists($umkm->foto_toko)) {
                Storage::disk('public')->delete($umkm->foto_toko);
            }
            $validatedUmkm['foto_toko'] = $request->file('foto_toko')->store('umkm_photos', 'public');
        } else {
            unset($validatedUmkm['foto_toko']);
        }

        // Update kedua tabel secara bersamaan
        DB::transaction(function () use (
            $user,
            $umkm,
            $validatedUser,
            $validatedUmkm
        ): void {
            $user->update($validatedUser);
            $umkm->update($validatedUmkm);
        });

        return redirect()
            ->route('umkm.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Mengubah status operasional toko (Buka / Tutup) secara langsung via toggle.
     */
    public function toggleStatusOperasional(Request $request, int|string $id_umkm): JsonResponse|RedirectResponse
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_umkm', $id_umkm)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        if ($umkm->isDitangguhkan()) {
            $untilLabel = $user->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
            $msg = "Akun UMKM Anda sedang ditangguhkan hingga {$untilLabel} dan tidak dapat membuka toko atau berjualan.";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'status_operasional' => 'Tutup',
                    'message' => $msg,
                ], 403);
            }

            return redirect()->route('umkm.profil')->with('error', $msg);
        }

        $validated = $request->validate([
            'status_operasional' => 'nullable|string|in:Buka,Tutup,buka,tutup',
        ]);

        if (! empty($validated['status_operasional'])) {
            $newStatus = strtolower($validated['status_operasional']) === 'buka' ? 'Buka' : 'Tutup';
        } else {
            $newStatus = strtolower((string) $umkm->status_operasional) === 'buka' ? 'Tutup' : 'Buka';
        }

        $umkm->update([
            'status_operasional' => $newStatus,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status_operasional' => $newStatus,
                'message' => "Status operasional toko diubah menjadi {$newStatus}.",
            ]);
        }

        return redirect()
            ->route('umkm.profil')
            ->with('success', "Status operasional toko diubah menjadi {$newStatus}.");
    }

    /**
     * Menampilkan daftar mitra UMKM terverifikasi untuk pelanggan (mitra buka tampil di atas, mitra tutup/ditangguhkan diberi info Toko Tutup).
     */
    public function pelangganMitra(): View
    {
        $mitras = Umkm::with([
            'user',
            'menus' => function ($query): void {
                $query->where('status', true)
                    ->where('stok_harian', '>', 0)
                    ->orderBy('id_menu', 'desc');
            },
        ])
            ->terverifikasi()
            ->orderByDesc('rating')
            ->orderBy('id_umkm', 'asc')
            ->get();

        $ulasanCounts = DB::table('ulasan')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->select('pesanan.id_umkm', DB::raw('COUNT(ulasan.id_ulasan) as total'))
            ->groupBy('pesanan.id_umkm')
            ->pluck('total', 'id_umkm');

        foreach ($mitras as $mitra) {
            $mitra->total_ulasan = (int) ($ulasanCounts[$mitra->id_umkm] ?? 0);
            $mitra->harga_mulai = (int) ($mitra->menus->min('harga_normal') ?? 0);
            $mitra->is_ditangguhkan = $mitra->isDitangguhkan();
            $mitra->is_tutup = ! $mitra->isBukaDanAktif();
        }

        $mitras = $mitras
            ->sortBy(fn (Umkm $m): int => $m->is_tutup ? 1 : 0)
            ->values();

        return view('pelanggan.mitra', compact('mitras'));
    }

    /**
     * Logout akun UMKM.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page');
    }
}
