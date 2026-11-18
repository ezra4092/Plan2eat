<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Umkm;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Dapatkan ID user yang sedang login dan UMKM-nya
        $id_user = Auth::id() ?? 1; // Ubah "?? 1" jika sistem login sudah siap
        $umkm = Umkm::where('id_user', $id_user)->first();

        if (!$umkm) {
            return back()->with('error', 'Data UMKM tidak ditemukan.');
        }

        // 2. Tangkap parameter dari Form Modal
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status');

        // 3. Susun Query berdasarkan Filter
        $query = Pesanan::where('id_umkm', $umkm->id_umkm);

        if ($tglAwal && $tglAkhir) {
            // Tambahkan waktu agar mencakup satu hari penuh di tanggal akhir
            $query->whereBetween('tgl_mulai', [$tglAwal . ' 00:00:00', $tglAkhir . ' 23:59:59']);
        }

        if ($status === 'selesai') {
            $query->where('status_pesanan', 'selesai');
        } elseif ($status === 'berlangsung') {
            $query->whereIn('status_pesanan', ['persiapan', 'masak', 'diambil']);
        }
        // Jika status == 'semua', biarkan query tanpa filter status

        $pesanan = $query->orderBy('tgl_mulai', 'desc')->get();

        // 4. Siapkan Header untuk Auto-Download File Excel (CSV)
        $fileName = 'Laporan_Keuangan_' . preg_replace('/\s+/', '_', $umkm->nama_vendor) . '_' . date('Ymd') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // 5. Susun Isi File (Stream Download)
        $callback = function() use($pesanan) {
            $file = fopen('php://output', 'w');
            
            // Baris Header Excel
            fputcsv($file, ['ID Pesanan', 'Tanggal Transaksi', 'Status Pesanan', 'Metode Pembayaran', 'Total (Rp)']);

            $totalPendapatan = 0;

            foreach ($pesanan as $p) {
                fputcsv($file, [
                    $p->id_pesanan,
                    $p->tgl_mulai,
                    strtoupper($p->status_pesanan),
                    $p->metode_pembayaran,
                    $p->total_pembayaran
                ]);
                $totalPendapatan += $p->total_pembayaran;
            }

            // Baris Kosong sebagai pemisah
            fputcsv($file, ['', '', '', '', '']);
            // Baris Total Penjualan
            fputcsv($file, ['', '', '', 'TOTAL PENDAPATAN:', $totalPendapatan]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}