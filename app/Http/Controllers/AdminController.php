<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Tampilan Dashboard Admin & Filter KPI
     */
    public function dashboard(Request $request)
    {
        $periode = (string) $request->input('periode', 'Bulan Ini');
        $now = Carbon::now('Asia/Jakarta');

        $dateLimit = match ($periode) {
            'Hari Ini' => $now->copy()->startOfDay(),
            'Minggu Ini' => $now->copy()->subDays(7)->startOfDay(),
            '6 Bulan Terakhir' => $now->copy()->subMonths(6)->startOfDay(),
            default => $now->copy()->startOfMonth(),
        };

        // KPI 1: Total UMKM & pertumbuhannya bulan ini
        $totalUmkm = Umkm::count();
        $umkmBaruBulanIni = Umkm::where('created_at', '>=', $now->copy()->startOfMonth())->count();

        // KPI 2: Total Pengguna (Pelanggan + UMKM)
        $totalUsers = User::whereRaw("LOWER(CAST(role AS TEXT)) != 'admin'")->count();
        $usersBaruBulanIni = User::whereRaw("LOWER(CAST(role AS TEXT)) != 'admin'")
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        // KPI 3: Pesanan Hari Ini & periode terpilih
        $todayOrders = DB::table('pesanan')
            ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
            ->whereDate('tgl_mulai', $now->toDateString())
            ->count();

        $periodeOrdersQuery = DB::table('pesanan')
            ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
            ->where('tgl_mulai', '>=', $dateLimit);

        $periodeOrdersCount = (clone $periodeOrdersQuery)->count();
        $totalPendapatan = (float) (clone $periodeOrdersQuery)->sum('total_pembayaran');
        $totalBiayaAdmin = (float) (clone $periodeOrdersQuery)->sum('biaya_admin');

        $formatRupiahRingkas = function (float $amount): string {
            if ($amount >= 1000000) {
                return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . ' Jt';
            }

            return 'Rp ' . number_format($amount, 0, ',', '.');
        };

        $stats = [
            'total_umkm' => number_format($totalUmkm, 0, ',', '.'),
            'umkm_growth' => "{$umkmBaruBulanIni} mitra baru bulan ini",
            'total_users' => number_format($totalUsers, 0, ',', '.'),
            'users_growth' => "{$usersBaruBulanIni} akun baru bulan ini",
            'today_orders' => number_format($todayOrders, 0, ',', '.'),
            'orders_growth' => number_format($periodeOrdersCount, 0, ',', '.') . ' pesanan (' . strtolower($periode) . ')',
            'total_revenue' => $formatRupiahRingkas($totalPendapatan),
            'revenue_growth' => $formatRupiahRingkas($totalBiayaAdmin) . ' biaya admin (' . strtolower($periode) . ')',
        ];

        // Grafik Pendapatan 6 Bulan Terakhir (nilai dalam Juta Rp)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $sumMonth = (float) DB::table('pesanan')
                ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
                ->whereYear('tgl_mulai', $monthDate->year)
                ->whereMonth('tgl_mulai', $monthDate->month)
                ->sum('total_pembayaran');

            $monthlyRevenue[] = [
                'month' => $monthDate->translatedFormat('M'),
                'value' => round($sumMonth / 1000000, 2),
                'raw_rp' => $sumMonth,
                'active' => ($i === 0),
            ];
        }

        $maxMonthJt = max(array_map(fn (array $m): float => (float) $m['value'], $monthlyRevenue));
        $chartMaxJt = max(1, (int) ceil($maxMonthJt));
        if ($chartMaxJt > 10) {
            $chartMaxJt = (int) (ceil($chartMaxJt / 10) * 10);
        } elseif ($chartMaxJt > 5) {
            $chartMaxJt = 10;
        } else {
            $chartMaxJt = 5;
        }

        // Aktivitas Terbaru (gabungan pendaftaran UMKM, pesanan terbaru, pengguna baru)
        $activityFeed = collect();

        $latestUmkmsForFeed = Umkm::orderByDesc('created_at')->limit(3)->get();
        foreach ($latestUmkmsForFeed as $u) {
            if (! $u->created_at) {
                continue;
            }
            $ts = Carbon::parse($u->created_at)->timezone('Asia/Jakarta');
            $statusVerif = strtolower((string) $u->status_verifikasi);
            $activityFeed->push([
                'timestamp' => $ts->timestamp,
                'color' => $statusVerif === 'disetujui' ? 'green' : 'amber',
                'title' => $statusVerif === 'disetujui' ? 'Mitra UMKM Terverifikasi' : 'UMKM Baru Mendaftar',
                'desc' => "{$u->nama_vendor} " . ($statusVerif === 'disetujui' ? 'aktif sebagai mitra.' : 'mengajukan verifikasi.'),
                'time' => $ts->diffForHumans(),
            ]);
        }

        $latestOrdersForFeed = DB::table('pesanan')
            ->join('users', 'pesanan.id_user', '=', 'users.id_user')
            ->select('pesanan.id_pesanan', 'pesanan.status_pesanan', 'pesanan.total_pembayaran', 'pesanan.tgl_mulai', 'users.nama_lengkap')
            ->orderByDesc('pesanan.id_pesanan')
            ->limit(3)
            ->get();

        foreach ($latestOrdersForFeed as $ord) {
            $ts = $ord->tgl_mulai ? Carbon::parse($ord->tgl_mulai)->timezone('Asia/Jakarta') : $now;
            $activityFeed->push([
                'timestamp' => $ts->timestamp,
                'color' => 'blue',
                'title' => 'Pesanan #P2E-' . str_pad((string) $ord->id_pesanan, 4, '0', STR_PAD_LEFT),
                'desc' => "{$ord->nama_lengkap} • Rp " . number_format((float) $ord->total_pembayaran, 0, ',', '.') . " ({$ord->status_pesanan})",
                'time' => $ts->diffForHumans(),
            ]);
        }

        $latestCustomersForFeed = User::whereRaw("LOWER(CAST(role AS TEXT)) = 'pelanggan'")
            ->orderByDesc('created_at')
            ->limit(2)
            ->get();

        foreach ($latestCustomersForFeed as $cust) {
            if (! $cust->created_at) {
                continue;
            }
            $ts = Carbon::parse($cust->created_at)->timezone('Asia/Jakarta');
            $activityFeed->push([
                'timestamp' => $ts->timestamp,
                'color' => 'green',
                'title' => 'Pelanggan Baru',
                'desc' => "{$cust->nama_lengkap} bergabung ke Plan2Eat.",
                'time' => $ts->diffForHumans(),
            ]);
        }

        $recentActivities = $activityFeed
            ->sortByDesc('timestamp')
            ->take(4)
            ->values()
            ->map(function (array $row): array {
                unset($row['timestamp']);
                return $row;
            })
            ->all();

        // Daftar Permintaan Verifikasi UMKM Terbaru (prioritaskan yang menunggu verifikasi)
        $pendingUmkms = Umkm::with(['user', 'menus.jenisMenu'])
            ->orderByRaw("CASE WHEN LOWER(CAST(status_verifikasi AS TEXT)) = 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('id_umkm')
            ->limit(6)
            ->get()
            ->map(function (Umkm $umkm): array {
                $words = preg_split('/\s+/', trim((string) $umkm->nama_vendor)) ?: ['UM'];
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
                $statusLower = strtolower((string) $umkm->status_verifikasi);
                $statusLabel = match ($statusLower) {
                    'disetujui' => 'Terverifikasi',
                    'ditangguhkan' => 'Ditangguhkan',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu',
                };
                $firstCategory = $umkm->menus->first()?->jenisMenu?->nama ?? 'Katering Sehat';

                return [
                    'id' => $umkm->id_umkm,
                    'initials' => $initials !== '' ? $initials : 'UM',
                    'name' => $umkm->nama_vendor,
                    'owner' => $umkm->user->nama_lengkap ?? 'Pemilik Usaha',
                    'category' => $firstCategory,
                    'date' => $umkm->created_at ? Carbon::parse($umkm->created_at)->translatedFormat('d M Y') : '-',
                    'status' => $statusLabel,
                ];
            })
            ->all();

        // Data tambahan yang tetap dipertahankan bila dibutuhkan
        $pendingUmkm = Umkm::whereRaw("LOWER(CAST(status_verifikasi AS TEXT)) = 'menunggu'")->count();
        $totalUlasan = DB::table('ulasan')->count();
        $avgRating = DB::table('ulasan')->avg('rating') ?? 0;

        return view('admin.dashboard', compact(
            'periode',
            'stats',
            'monthlyRevenue',
            'chartMaxJt',
            'recentActivities',
            'pendingUmkms',
            'totalUmkm',
            'pendingUmkm',
            'totalUlasan',
            'avgRating',
            'totalPendapatan'
        ));
    }

    /**
     * Tampilan Manajemen Pengguna & UMKM (Master Data)
     */
    public function pengguna(Request $request)
    {
        // Mulai query, tapi kecualikan role 'admin'
        $query = User::leftJoin('umkm', 'users.id_user', '=', 'umkm.id_user')
            ->select('users.*', 'umkm.nama_vendor', 'umkm.status_verifikasi')
            ->where('users.role', '!=', 'admin'); 

        // Jika ada filter role (misalnya parameter ?role=umkm dari card dashboard)
        if ($request->has('role')) {
            $query->where('users.role', $request->role);
        }

        $users = $query->get();

        return view('admin.pengguna', compact('users'));
    }

    /**
     * Halaman Daftar Ulasan (Monitoring & Moderasi)
     */
    public function ulasan(Request $request)
    {
        $query = DB::table('ulasan')
            ->join('users', 'ulasan.id_user', '=', 'users.id_user')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            // Join ke users lagi khusus untuk akun si UMKM (untuk cek status suspend)
            ->join('users as umkm_user', 'umkm.id_user', '=', 'umkm_user.id_user') 
            ->select(
                'ulasan.*', 
                'users.nama_lengkap as nama_pengguna', 
                'umkm.nama_vendor',
                'umkm.id_user as id_user_umkm', // Ambil ID User UMKM untuk fitur suspend
                'umkm_user.is_suspended' // Cek apakah akun UMKM sedang disuspend
            )
            ->orderBy('ulasan.timestamp', 'desc');

        // Filter Rating Rendah (Bintang 1 sampai 3)
        if ($request->input('filter') == 'rendah') {
            $query->where('ulasan.rating', '<=', 3);
        }

        $detailUlasan = $query->get();

        return view('admin.ulasan', compact('detailUlasan'));
    }

    /**
     * Fungsi Hapus Ulasan Tidak Pantas
     */
    public function hapusUlasan($id_ulasan)
    {
        // Pastikan primary key tabel ulasan kamu bernama 'id_ulasan'
        DB::table('ulasan')->where('id_ulasan', $id_ulasan)->delete();
        
        return back()->with('deleted', 'Ulasan tidak pantas berhasil dihapus.');
    }

    public function transaksi()
    {
        $transaksiPerUmkm = DB::table('pesanan')
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            ->select('umkm.nama_vendor', DB::raw('COUNT(pesanan.id_pesanan) as jumlah_pesanan'), DB::raw('SUM(pesanan.total_pembayaran) as total_pendapatan'))
            ->where('pesanan.status_pesanan', 'selesai')
            ->groupBy('umkm.id_umkm', 'umkm.nama_vendor')
            ->get();

        return view('admin.transaksi', compact('transaksiPerUmkm'));
    }

    /**
     * Halaman Laporan Platform Admin:
     * - Total GMV (SUM total_pembayaran untuk status selesai)
     * - Pendapatan Aplikasi (SUM biaya_admin untuk status selesai)
     * - Perbandingan Tipe Order (Paket Katering vs Satuan/Harian)
     * - Rekap per Mitra UMKM & Rincian Transaksi Selesai
     */
    public function laporan(Request $request): View
    {
        $data = $this->getLaporanData($request);

        return view('admin.laporan', $data);
    }

    /**
     * Mengunduh laporan GMV, pendapatan aplikasi, dan rincian transaksi selesai dalam format CSV.
     */
    public function laporanCsv(Request $request): StreamedResponse
    {
        $data = $this->getLaporanData($request, false);
        $periode = $data['periode'];
        $filename = 'laporan-plan2eat-' . $periode . '-' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }

            // UTF-8 BOM agar terbaca rapi di Microsoft Excel
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['LAPORAN KEUANGAN & TRANSAKSI PLAN2EAT']);
            fputcsv($out, ['Periode', strtoupper(str_replace('_', ' ', (string) $data['periode']))]);
            fputcsv($out, ['Tanggal Unduh', Carbon::now('Asia/Jakarta')->format('d-m-Y H:i') . ' WIB']);
            fputcsv($out, []);

            fputcsv($out, ['RINGKASAN UTAMA']);
            fputcsv($out, ['Total GMV (Nilai Transaksi Selesai)', (int) $data['totalGmv']]);
            fputcsv($out, ['Pendapatan Aplikasi (Biaya Admin)', (int) $data['pendapatanAplikasi']]);
            fputcsv($out, ['Jumlah Order Selesai', (int) $data['totalOrder']]);
            fputcsv($out, ['Rata-rata Nilai Order', (int) round((float) $data['rataRataOrder'])]);
            fputcsv($out, []);

            fputcsv($out, ['PERBANDINGAN TIPE ORDER']);
            fputcsv($out, ['Tipe Order', 'Jumlah Order', 'Persentase (%)', 'Total GMV (Rp)', 'Biaya Admin (Rp)']);
            fputcsv($out, ['Paket Katering (Meal Plan)', $data['paketCount'], $data['paketPct'], (int) $data['paketGmv'], (int) $data['paketAdmin']]);
            fputcsv($out, ['Pembelian Satuan / Harian', $data['satuanCount'], $data['satuanPct'], (int) $data['satuanGmv'], (int) $data['satuanAdmin']]);
            fputcsv($out, []);

            fputcsv($out, ['REKAP PER MITRA UMKM']);
            fputcsv($out, ['Nama Mitra UMKM', 'Paket Katering', 'Satuan / Harian', 'Total Order', 'Total GMV (Rp)', 'Pendapatan Aplikasi / Admin (Rp)']);
            foreach ($data['rekapUmkm'] as $row) {
                fputcsv($out, [
                    $row->nama_vendor,
                    (int) $row->jumlah_paket,
                    (int) $row->jumlah_satuan,
                    (int) $row->total_order,
                    (int) $row->total_gmv,
                    (int) $row->total_biaya_admin,
                ]);
            }
            fputcsv($out, []);

            fputcsv($out, ['RINCIAN TRANSAKSI SELESAI']);
            fputcsv($out, ['ID Pesanan', 'Tanggal', 'Pelanggan', 'Mitra UMKM', 'Tipe Order', 'Metode Bayar', 'Total GMV (Rp)', 'Biaya Admin (Rp)']);
            foreach ($data['daftarTransaksi'] as $trx) {
                fputcsv($out, [
                    '#P2E-' . str_pad((string) $trx->id_pesanan, 4, '0', STR_PAD_LEFT),
                    $trx->tgl_mulai ? Carbon::parse($trx->tgl_mulai)->format('d-m-Y H:i') : '-',
                    $trx->nama_pelanggan,
                    $trx->nama_vendor,
                    ! empty($trx->id_plan) ? 'Paket Katering' : 'Satuan / Harian',
                    strtoupper((string) ($trx->metode_pembayaran ?? '-')),
                    (int) $trx->total_pembayaran,
                    (int) $trx->biaya_admin,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Query builder terpusat untuk halaman laporan dan ekspor CSV.
     *
     * @return array<string, mixed>
     */
    private function getLaporanData(Request $request, bool $limitTransactions = true): array
    {
        $periode = (string) $request->query('periode', 'semua');
        $now = Carbon::now('Asia/Jakarta');

        $dateLimit = match ($periode) {
            'hari_ini' => $now->copy()->startOfDay(),
            '7_hari' => $now->copy()->subDays(7)->startOfDay(),
            'bulan_ini' => $now->copy()->startOfMonth(),
            default => null,
        };

        $baseQuery = DB::table('pesanan')
            ->whereRaw("LOWER(CAST(pesanan.status_pesanan AS TEXT)) = 'selesai'");

        if ($dateLimit) {
            $baseQuery->where('pesanan.tgl_mulai', '>=', $dateLimit);
        }

        $summary = (clone $baseQuery)
            ->selectRaw('
                COUNT(pesanan.id_pesanan) as total_order,
                COALESCE(SUM(pesanan.total_pembayaran), 0) as total_gmv,
                COALESCE(SUM(pesanan.biaya_admin), 0) as pendapatan_aplikasi,
                SUM(CASE WHEN pesanan.id_plan IS NOT NULL THEN 1 ELSE 0 END) as paket_count,
                COALESCE(SUM(CASE WHEN pesanan.id_plan IS NOT NULL THEN pesanan.total_pembayaran ELSE 0 END), 0) as paket_gmv,
                COALESCE(SUM(CASE WHEN pesanan.id_plan IS NOT NULL THEN pesanan.biaya_admin ELSE 0 END), 0) as paket_admin,
                SUM(CASE WHEN pesanan.id_plan IS NULL THEN 1 ELSE 0 END) as satuan_count,
                COALESCE(SUM(CASE WHEN pesanan.id_plan IS NULL THEN pesanan.total_pembayaran ELSE 0 END), 0) as satuan_gmv,
                COALESCE(SUM(CASE WHEN pesanan.id_plan IS NULL THEN pesanan.biaya_admin ELSE 0 END), 0) as satuan_admin
            ')
            ->first();

        $totalOrder = (int) ($summary->total_order ?? 0);
        $totalGmv = (float) ($summary->total_gmv ?? 0);
        $pendapatanAplikasi = (float) ($summary->pendapatan_aplikasi ?? 0);
        $rataRataOrder = $totalOrder > 0 ? ($totalGmv / $totalOrder) : 0.0;

        $paketCount = (int) ($summary->paket_count ?? 0);
        $paketGmv = (float) ($summary->paket_gmv ?? 0);
        $paketAdmin = (float) ($summary->paket_admin ?? 0);
        $paketPct = $totalOrder > 0 ? round(($paketCount / $totalOrder) * 100, 1) : 0.0;

        $satuanCount = (int) ($summary->satuan_count ?? 0);
        $satuanGmv = (float) ($summary->satuan_gmv ?? 0);
        $satuanAdmin = (float) ($summary->satuan_admin ?? 0);
        $satuanPct = $totalOrder > 0 ? round(100 - $paketPct, 1) : 0.0;

        $rekapUmkm = (clone $baseQuery)
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            ->selectRaw('
                umkm.id_umkm,
                umkm.nama_vendor,
                COUNT(pesanan.id_pesanan) as total_order,
                SUM(CASE WHEN pesanan.id_plan IS NOT NULL THEN 1 ELSE 0 END) as jumlah_paket,
                SUM(CASE WHEN pesanan.id_plan IS NULL THEN 1 ELSE 0 END) as jumlah_satuan,
                COALESCE(SUM(pesanan.total_pembayaran), 0) as total_gmv,
                COALESCE(SUM(pesanan.biaya_admin), 0) as total_biaya_admin
            ')
            ->groupBy('umkm.id_umkm', 'umkm.nama_vendor')
            ->orderByDesc('total_gmv')
            ->get();

        $trxQuery = (clone $baseQuery)
            ->join('users', 'pesanan.id_user', '=', 'users.id_user')
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            ->select(
                'pesanan.id_pesanan',
                'pesanan.id_plan',
                'pesanan.total_pembayaran',
                'pesanan.biaya_admin',
                'pesanan.metode_pembayaran',
                'pesanan.tgl_mulai',
                'users.nama_lengkap as nama_pelanggan',
                'umkm.nama_vendor'
            )
            ->orderByDesc('pesanan.id_pesanan');

        if ($limitTransactions) {
            $trxQuery->limit(30);
        }

        $daftarTransaksi = $trxQuery->get();

        return compact(
            'periode',
            'totalOrder',
            'totalGmv',
            'pendapatanAplikasi',
            'rataRataOrder',
            'paketCount',
            'paketGmv',
            'paketAdmin',
            'paketPct',
            'satuanCount',
            'satuanGmv',
            'satuanAdmin',
            'satuanPct',
            'rekapUmkm',
            'daftarTransaksi'
        );
    }

    /**
     * Fungsi Suspend / Aktivasi Akun
     * (Membutuhkan kolom 'is_suspended' di tabel users)
     */
    public function toggleSuspend($id_user)
    {
        $user = User::findOrFail($id_user);
        
        // Asumsi kamu menambahkan kolom is_suspended (boolean) di DB
        $user->is_suspended = !$user->is_suspended; 
        $user->save();

        $pesan = $user->is_suspended ? 'Akun berhasil ditangguhkan.' : 'Akun berhasil diaktivasi kembali.';
        return back()->with('success', $pesan);
    }

    /**
     * Fungsi Hapus Pengguna
     */
    public function hapusPengguna($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete(); // Ini akan cascade delete UMKM dan pesanan sesuai migrasi kamu

        return back()->with('deleted', 'Pengguna berhasil dihapus permanen.');
    }
}