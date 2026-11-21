<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Tampilan Dashboard Admin & Filter KPI
     */
    public function dashboard(Request $request)
    {
        $filter = $request->input('filter', 'all'); // Default: semua data

        // Tentukan rentang waktu berdasarkan filter
        $dateLimit = null;
        if ($filter == '7_days') $dateLimit = Carbon::now()->subDays(7);
        if ($filter == '1_month') $dateLimit = Carbon::now()->subMonth();
        if ($filter == '4_months') $dateLimit = Carbon::now()->subMonths(4);

        // KPI 1: Total UMKM (Semua)
        $totalUmkm = Umkm::count();

        // KPI 2: UMKM Menunggu Verifikasi
        $pendingUmkm = Umkm::where('status_verifikasi', false)->count();

        // KPI 3: Total Ulasan
        // Filter waktu ulasan
        $ulasanQuery = DB::table('ulasan');
        if ($dateLimit) {
            $ulasanQuery->where('timestamp', '>=', $dateLimit);
        }
        $totalUlasan = $ulasanQuery->count();
        $avgRating = $ulasanQuery->avg('rating') ?? 0;

        // KPI 4: Total Transaksi (Pesanan Selesai)
        $transaksiQuery = DB::table('pesanan')->where('status_pesanan', 'selesai');
        if ($dateLimit) {
            $transaksiQuery->where('tgl_mulai', '>=', $dateLimit);
        }
        $totalTransaksiCount = $transaksiQuery->count();
        $totalPendapatan = $transaksiQuery->sum('total_pembayaran');

        // Data Chart (Transaksi per Hari/Bulan)
        // Grouping by date untuk chart
        $chartData = $transaksiQuery
            ->select(DB::raw('DATE(tgl_mulai) as tanggal'), DB::raw('SUM(total_pembayaran) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Ambil Data Ulasan & Nama UMKM untuk detail saat Card Ulasan di-klik
        $detailUlasan = DB::table('ulasan')
            ->join('users', 'ulasan.id_user', '=', 'users.id_user')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            ->select('ulasan.*', 'users.nama_lengkap as nama_pengguna', 'umkm.nama_vendor')
            ->orderBy('ulasan.timestamp', 'desc')
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact(
            'filter', 'totalUmkm', 'pendingUmkm', 'totalUlasan', 'avgRating', 
            'totalTransaksiCount', 'totalPendapatan', 'chartData', 'detailUlasan'
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

    public function ulasan()
    {
        $detailUlasan = DB::table('ulasan')
            ->join('users', 'ulasan.id_user', '=', 'users.id_user')
            ->join('pesanan', 'ulasan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('umkm', 'pesanan.id_umkm', '=', 'umkm.id_umkm')
            ->select('ulasan.*', 'users.nama_lengkap as nama_pengguna', 'umkm.nama_vendor')
            ->orderBy('ulasan.timestamp', 'desc')
            ->get();

        return view('admin.ulasan', compact('detailUlasan'));
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

        return back()->with('success', 'Pengguna berhasil dihapus permanen.');
    }
}