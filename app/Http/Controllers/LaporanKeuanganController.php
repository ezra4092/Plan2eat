<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Umkm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanKeuanganController extends Controller
{
    /**
     * Menampilkan halaman Keuangan UMKM (saldo, metode pembayaran, dan log transaksi masuk).
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

        $totalSaldoAktif = (float) ($umkm->saldo ?? 0);
        $activePayments = $this->normalizePaymentMethods($umkm->metode_pembayaran);

        return view('umkm.keuangan', compact(
            'umkm',
            'pesanan',
            'totalSaldoAktif',
            'activePayments',
            'tglAwal',
            'tglAkhir',
            'status'
        ));
    }

    /**
     * Memperbarui daftar metode pembayaran aktif milik UMKM.
     */
    public function updateMetodePembayaran(Request $request): RedirectResponse
    {
        $umkm = $this->getAuthenticatedUmkm();

        if (! $umkm) {
            return back()->with('error', 'Data UMKM tidak ditemukan.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'array', 'min:1'],
            'metode_pembayaran.*' => ['required', 'string', 'in:QRIS,Transfer Bank,Tunai'],
        ], [
            'metode_pembayaran.required' => 'Pilih minimal satu metode pembayaran.',
            'metode_pembayaran.min' => 'Pilih minimal satu metode pembayaran.',
        ]);

        $umkm->update([
            'metode_pembayaran' => array_values(array_unique($validated['metode_pembayaran'])),
        ]);

        return redirect()
            ->route('umkm.laporan_keuangan')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
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

            // Blok Header Informasi Laporan UMKM
            fputcsv($file, ['================================================================================']);
            fputcsv($file, ['PLAN2EAT - LAPORAN KEUANGAN MITRA UMKM']);
            fputcsv($file, ['================================================================================']);
            fputcsv($file, ['Nama UMKM', $umkm->nama_vendor]);
            fputcsv($file, ['Alamat Toko', $umkm->alamat_lengkap ?: '-']);
            fputcsv($file, ['Tanggal Dicetak', now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') . ' WIB']);
            fputcsv($file, ['Periode Data', $periodeLabel]);
            fputcsv($file, ['Filter Status', $statusLabel]);
            fputcsv($file, ['Total Saldo Toko', 'Rp ' . number_format((float) ($umkm->saldo ?? 0), 0, ',', '.')]);
            fputcsv($file, ['Metode Pembayaran', implode(', ', $this->normalizePaymentMethods($umkm->metode_pembayaran))]);
            fputcsv($file, []);

            // Header Tabel Transaksi
            fputcsv($file, [
                'No',
                'ID Pesanan',
                'Tanggal Transaksi',
                'Kategori Pesanan',
                'Total Porsi',
                'Metode Pembayaran',
                'Status Pesanan',
                'Total (Rp)',
            ]);

            $totalPendapatanSelesai = 0;
            $totalTertahan = 0;
            $totalKeseluruhan = 0;

            if ($pesanan->isEmpty()) {
                fputcsv($file, ['-', '-', '-', 'Belum ada data transaksi pada periode ini', '0', '-', '-', '0']);
            } else {
                foreach ($pesanan as $index => $p) {
                    $isFlashSale = $p->detailPesanan->contains(function ($detail): bool {
                        return ! is_null($detail->id_flash_sale);
                    });

                    $totalPorsi = (int) $p->detailPesanan->sum('kuantitas');
                    if ($totalPorsi === 0) {
                        $totalPorsi = 1;
                    }

                    $nominal = (float) $p->total_pembayaran;
                    $statusLower = strtolower((string) $p->status_pesanan);

                    if ($statusLower === 'selesai') {
                        $totalPendapatanSelesai += $nominal;
                    } elseif (in_array($statusLower, ['menunggu pembayaran', 'persiapan', 'masak', 'siap diambil'], true)) {
                        $totalTertahan += $nominal;
                    }

                    $totalKeseluruhan += $nominal;

                    fputcsv($file, [
                        $index + 1,
                        '#P2E-' . $p->id_pesanan,
                        $p->tgl_mulai ? $p->tgl_mulai->format('d/m/Y H:i') . ' WIB' : '-',
                        $isFlashSale ? 'Night Flash Rescue' : 'Pesanan Katering',
                        $totalPorsi . ' Porsi',
                        $p->metode_pembayaran ?: '-',
                        strtoupper((string) $p->status_pesanan),
                        number_format($nominal, 0, ',', '.'),
                    ]);
                }
            }

            // Blok Ringkasan Keuangan di Bagian Bawah Tabel
            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', '', '', 'RINGKASAN LAPORAN', '']);
            fputcsv($file, ['', '', '', '', '', '', 'Total Jumlah Transaksi', $pesanan->count() . ' Transaksi']);
            fputcsv($file, ['', '', '', '', '', '', 'Total Pendapatan (Selesai)', 'Rp ' . number_format($totalPendapatanSelesai, 0, ',', '.')]);
            fputcsv($file, ['', '', '', '', '', '', 'Total Pesanan Berjalan', 'Rp ' . number_format($totalTertahan, 0, ',', '.')]);
            fputcsv($file, ['', '', '', '', '', '', 'TOTAL KESELURUHAN NOMINAL', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

    /**
     * Menormalisasi kolom metode_pembayaran UMKM menjadi array string.
     *
     * @return array<int, string>
     */
    private function normalizePaymentMethods(mixed $rawPayments): array
    {
        if (is_string($rawPayments)) {
            $decoded = json_decode($rawPayments, true);
            $payments = is_array($decoded) ? $decoded : [$rawPayments];
        } elseif (is_array($rawPayments)) {
            $payments = $rawPayments;
        } else {
            $payments = [];
        }

        $filtered = array_values(array_filter(array_map('strval', $payments)));

        return empty($filtered) ? ['QRIS'] : $filtered;
    }
}
