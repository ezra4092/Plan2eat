<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Umkm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanKeuanganController extends Controller
{
    /**
     * Menampilkan halaman Keuangan UMKM (Total Saldo, Saldo Milik UMKM, Saldo Tertahan, tombol Cairkan Dana, dan Log Uang Masuk).
     */
    public function index(Request $request): View|RedirectResponse
    {
        $umkm = $this->getAuthenticatedUmkm();

        if (! $umkm) {
            return redirect()
                ->route('umkm.dashboard')
                ->with('error', 'Data UMKM untuk akun Anda tidak ditemukan.');
        }

        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status', 'semua');

        $pesanan = $this->buildFilteredOrdersQuery($umkm, $request)
            ->with('detailPesanan')
            ->orderBy('tgl_mulai', 'desc')
            ->get();

        $saldoUmkm = (float) ($umkm->saldo ?? 0);

        $pesananTertahan = Pesanan::with('detailPesanan')
            ->where('id_umkm', $umkm->id_umkm)
            ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
            ->where('dana_dicairkan', false)
            ->get();

        $saldoTertahan = (float) $pesananTertahan->sum(fn (Pesanan $order): float => $this->calculateUmkmNetAmount($order));
        $jumlahPesananTertahan = $pesananTertahan->count();
        $totalSaldo = $saldoUmkm + $saldoTertahan;

        return view('umkm.keuangan', compact(
            'umkm',
            'pesanan',
            'saldoUmkm',
            'saldoTertahan',
            'jumlahPesananTertahan',
            'totalSaldo',
            'tglAwal',
            'tglAkhir',
            'status'
        ));
    }

    /**
     * Mencairkan seluruh saldo tertahan dari pesanan yang valid menjadi saldo milik UMKM.
     */
    public function cairkanDana(): RedirectResponse
    {
        $umkm = $this->getAuthenticatedUmkm();

        if (! $umkm) {
            return back()->with('error', 'Data UMKM tidak ditemukan.');
        }

        $nominalCair = 0.0;

        $disbursed = DB::transaction(function () use ($umkm, &$nominalCair): bool {
            $umkmLocked = Umkm::lockForUpdate()->findOrFail($umkm->id_umkm);

            $heldOrders = Pesanan::with('detailPesanan')
                ->where('id_umkm', $umkmLocked->id_umkm)
                ->whereNotIn('status_pesanan', ['dibatalkan', 'menunggu pembayaran'])
                ->where('dana_dicairkan', false)
                ->lockForUpdate()
                ->get();

            if ($heldOrders->isEmpty()) {
                return false;
            }

            $nominalCair = (float) $heldOrders->sum(fn (Pesanan $order): float => $this->calculateUmkmNetAmount($order));

            if ($nominalCair <= 0) {
                return false;
            }

            Pesanan::whereIn('id_pesanan', $heldOrders->pluck('id_pesanan'))
                ->update(['dana_dicairkan' => true]);

            $umkmLocked->increment('saldo', $nominalCair);

            return true;
        });

        if (! $disbursed) {
            return redirect()
                ->route('umkm.laporan_keuangan')
                ->with('error', 'Tidak ada saldo tertahan yang dapat dicairkan saat ini.');
        }

        return redirect()
            ->route('umkm.laporan_keuangan')
            ->with('success', 'Dana sebesar Rp ' . number_format($nominalCair, 0, ',', '.') . ' berhasil dicairkan ke saldo UMKM Anda!');
    }

    /**
     * Mengunduh laporan keuangan UMKM dalam format CSV yang terstruktur rapi.
     */
    public function cetakLaporan(Request $request): StreamedResponse|RedirectResponse
    {
        $umkm = $this->getAuthenticatedUmkm();

        if (! $umkm) {
            return back()->with('error', 'Data UMKM tidak ditemukan.');
        }

        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status', 'semua');

        $pesanan = $this->buildFilteredOrdersQuery($umkm, $request)
            ->with('detailPesanan')
            ->orderBy('tgl_mulai', 'desc')
            ->get();

        $fileName = 'Laporan_Keuangan_' . preg_replace('/\s+/', '_', $umkm->nama_vendor) . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $periodeLabel = ($tglAwal && $tglAkhir)
            ? date('d/m/Y', strtotime((string) $tglAwal)) . ' s/d ' . date('d/m/Y', strtotime((string) $tglAkhir))
            : 'Semua Periode';

        $statusLabel = match ($status) {
            'selesai' => 'Selesai',
            'berlangsung' => 'Sedang Berjalan',
            default => 'Semua Status',
        };

        $callback = function () use ($umkm, $pesanan, $periodeLabel, $statusLabel): void {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 agar karakter dan format kolom terbaca rapi di Microsoft Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['================================================================================']);
            fputcsv($file, ['PLAN2EAT - LAPORAN KEUANGAN MITRA UMKM']);
            fputcsv($file, ['================================================================================']);
            fputcsv($file, ['Nama UMKM', $umkm->nama_vendor]);
            fputcsv($file, ['Alamat Toko', $umkm->alamat_lengkap ?: '-']);
            fputcsv($file, ['Tanggal Dicetak', now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') . ' WIB']);
            fputcsv($file, ['Periode Data', $periodeLabel]);
            fputcsv($file, ['Filter Status', $statusLabel]);
            fputcsv($file, ['Saldo Milik UMKM', 'Rp ' . number_format((float) ($umkm->saldo ?? 0), 0, ',', '.')]);
            fputcsv($file, ['Sistem Pembayaran', 'Terpusat melalui Plan2Eat (Potongan Biaya Admin 5%)']);
            fputcsv($file, []);

            fputcsv($file, [
                'No',
                'ID Pesanan',
                'Tanggal Transaksi',
                'Kategori Pesanan',
                'Total Porsi',
                'Metode Pembayaran',
                'Status Pesanan',
                'Status Dana',
                'Pendapatan Bersih UMKM (Rp)',
            ]);

            $totalSudahCair = 0;
            $totalTertahan = 0;
            $totalKeseluruhan = 0;

            if ($pesanan->isEmpty()) {
                fputcsv($file, ['-', '-', '-', 'Belum ada data transaksi pada periode ini', '0', '-', '-', '-', '0']);
            } else {
                foreach ($pesanan as $index => $p) {
                    $isFlashSale = $p->detailPesanan->contains(fn ($detail): bool => ! is_null($detail->id_flash_sale));
                    $totalPorsi = max(1, (int) $p->detailPesanan->sum('kuantitas'));

                    $netAmount = $this->calculateUmkmNetAmount($p);
                    $statusLower = strtolower((string) $p->status_pesanan);

                    if ($statusLower === 'dibatalkan' || $statusLower === 'menunggu pembayaran') {
                        $danaLabel = 'Tidak Masuk Saldo';
                    } elseif ($p->dana_dicairkan) {
                        $danaLabel = 'Sudah Dicairkan';
                        $totalSudahCair += $netAmount;
                        $totalKeseluruhan += $netAmount;
                    } else {
                        $danaLabel = 'Saldo Tertahan';
                        $totalTertahan += $netAmount;
                        $totalKeseluruhan += $netAmount;
                    }

                    fputcsv($file, [
                        $index + 1,
                        '#P2E-' . $p->id_pesanan,
                        $p->tgl_mulai ? $p->tgl_mulai->format('d/m/Y H:i') . ' WIB' : '-',
                        $isFlashSale ? 'Night Flash Rescue' : 'Pesanan Katering',
                        $totalPorsi . ' Porsi',
                        $p->metode_pembayaran ?: '-',
                        strtoupper((string) $p->status_pesanan),
                        $danaLabel,
                        number_format($netAmount, 0, ',', '.'),
                    ]);
                }
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', '', '', '', 'RINGKASAN LAPORAN', '']);
            fputcsv($file, ['', '', '', '', '', '', '', 'Total Jumlah Transaksi', $pesanan->count() . ' Transaksi']);
            fputcsv($file, ['', '', '', '', '', '', '', 'Total Dana Sudah Dicairkan', 'Rp ' . number_format($totalSudahCair, 0, ',', '.')]);
            fputcsv($file, ['', '', '', '', '', '', '', 'Total Saldo Tertahan', 'Rp ' . number_format($totalTertahan, 0, ',', '.')]);
            fputcsv($file, ['', '', '', '', '', '', '', 'TOTAL PENDAPATAN BERSIH', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Menghitung nominal bersih milik UMKM (di luar biaya admin 5% Plan2Eat).
     */
    private function calculateUmkmNetAmount(Pesanan $order): float
    {
        if ((float) ($order->biaya_admin ?? 0) > 0) {
            return max(0.0, (float) $order->total_pembayaran - (float) $order->biaya_admin);
        }

        $detailSubtotal = (float) $order->detailPesanan->sum('subtotal');

        return $detailSubtotal > 0 ? $detailSubtotal : (float) $order->total_pembayaran;
    }

    /**
     * Mengambil data UMKM milik user yang sedang login.
     */
    private function getAuthenticatedUmkm(): ?Umkm
    {
        $idUser = Auth::id();
        if (! $idUser) {
            return null;
        }

        return Umkm::where('id_user', $idUser)->first();
    }

    /**
     * Menyusun query pesanan UMKM berdasarkan filter tanggal dan status.
     */
    private function buildFilteredOrdersQuery(Umkm $umkm, Request $request): Builder
    {
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status');

        $query = Pesanan::where('id_umkm', $umkm->id_umkm);

        if ($tglAwal && $tglAkhir) {
            $query->whereBetween('tgl_mulai', [$tglAwal . ' 00:00:00', $tglAkhir . ' 23:59:59']);
        } elseif ($tglAwal) {
            $query->where('tgl_mulai', '>=', $tglAwal . ' 00:00:00');
        } elseif ($tglAkhir) {
            $query->where('tgl_mulai', '<=', $tglAkhir . ' 23:59:59');
        }

        if ($status === 'selesai') {
            $query->where('status_pesanan', 'selesai');
        } elseif ($status === 'berlangsung') {
            $query->whereIn('status_pesanan', ['menunggu pembayaran', 'persiapan', 'masak', 'siap diambil']);
        }

        return $query;
    }
}
