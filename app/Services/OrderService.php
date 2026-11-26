<?php

namespace App\Services;

use App\Models\DetailPesanan;
use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Umkm;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Membuat pesanan baru dan detail pesanan ke dalam database.
     */
    public function buatPesanan(int $userId, array $data): Pesanan
    {
        return DB::transaction(function () use ($userId, $data) {
            $umkm = Umkm::with('user')->findOrFail($data['id_umkm']);
            if (! $umkm->isBukaDanAktif()) {
                $alasan = $umkm->isDitangguhkan()
                    ? 'sedang ditangguhkan sementara oleh Admin'
                    : 'sedang tutup';
                throw new Exception("Maaf, toko {$umkm->nama_vendor} {$alasan} dan tidak dapat menerima pesanan saat ini.");
            }

            $totalPembayaran = 0;

            // Pastikan status_pesanan menggunakan lowercase sesuai check constraint PostgreSQL
            // Nilai valid: 'menunggu pembayaran', 'persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'
            $statusInput = isset($data['status_pesanan']) ? strtolower($data['status_pesanan']) : 'persiapan';
            $validStatuses = ['menunggu pembayaran', 'persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'];
            $statusPesanan = in_array($statusInput, $validStatuses, true) ? $statusInput : 'persiapan';

            // 1. Simpan data utama ke tabel 'pesanan' (dana ditahan dulu: dana_dicairkan = false)
            $pesanan = Pesanan::create([
                'id_user'           => $userId,
                'id_umkm'           => $data['id_umkm'],
                'id_plan'           => $data['id_plan'] ?? null,
                'status_pesanan'    => $statusPesanan,
                'total_pembayaran'  => 0,
                'biaya_admin'       => 0,
                'dana_dicairkan'    => false,
                'tgl_mulai'         => $data['tgl_mulai'] ?? now(),
                'tgl_selesai'       => $data['tgl_selesai'] ?? null,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'metode_pengiriman' => $data['metode_pengiriman'],
                'jadwal_pengiriman' => $data['jadwal_pengiriman'] ?? now('Asia/Jakarta'),
                'waktu_pembayaran'  => $statusPesanan !== 'menunggu pembayaran' ? now('Asia/Jakarta') : null,
                'catatan'           => ! empty($data['catatan']) ? trim((string) $data['catatan']) : null,
            ]);

            $subtotalMenu = 0;

            // 2. Proses setiap item menu dalam pesanan
            foreach ($data['items'] as $item) {
                $kuantitas = (int) $item['kuantitas'];
                $idFlashSale = $item['id_flash_sale'] ?? null;

                if ($idFlashSale) {
                    // Jika item menggunakan harga Flash Sale
                    $flashSale = Flashsale::lockForUpdate()->findOrFail($idFlashSale);

                    if ($flashSale->stok_sisa < $kuantitas) {
                        throw new Exception("Stok promo flash sale tidak mencukupi.");
                    }

                    $hargaSatuan = $flashSale->harga_diskon;
                    $flashSale->decrement('stok_sisa', $kuantitas);
                } else {
                    // Jika item menggunakan harga normal
                    $menu = Menu::lockForUpdate()->findOrFail($item['id_menu']);

                    if ($menu->stok_harian < $kuantitas) {
                        throw new Exception("Stok {$menu->nama_makanan} tidak mencukupi.");
                    }

                    $hargaSatuan = $menu->harga_normal;
                    $menu->decrement('stok_harian', $kuantitas);
                }

                $subtotal = $hargaSatuan * $kuantitas;
                $subtotalMenu += $subtotal;

                $waktuMakan = !empty($item['waktu']) ? $item['waktu'] : '12:00:00';
                if (strlen($waktuMakan) === 5) {
                    $waktuMakan .= ':00';
                }

                // 3. Simpan ke tabel 'detail_pesanan'
                DetailPesanan::create([
                    'id_pesanan'    => $pesanan->id_pesanan,
                    'id_menu'       => $item['id_menu'],
                    'id_flash_sale' => $idFlashSale,
                    'kuantitas'     => $kuantitas,
                    'waktu'         => $waktuMakan,
                    'subtotal'      => $subtotal,
                ]);
            }

            // 4. Hitung biaya admin 5% dari subtotal pemesanan & total akhir
            // Saldo UMKM belum bertambah (ditahan terlebih dahulu hingga dicairkan)
            $biayaAdmin = round($subtotalMenu * 0.05);
            $totalPembayaran = $subtotalMenu + $biayaAdmin;

            $pesanan->update([
                'biaya_admin'      => $biayaAdmin,
                'total_pembayaran' => $totalPembayaran,
            ]);

            return $pesanan;
        });
    }

    /**
     * Mengambil ringkasan pesanan aktif (hari ini atau jadwal mendatang) untuk card Status Pesanan.
     *
     * @return array<string, mixed>|null
     */
    public function getActiveOrderCardForUser(?int $userId): ?array
    {
        if (! $userId) {
            return null;
        }

        $todayStr = Carbon::today('Asia/Jakarta')->toDateString();

        $allOrders = Pesanan::with(['umkm', 'detailPesanan.menu'])
            ->where('id_user', $userId)
            ->where('status_pesanan', '!=', 'dibatalkan')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        $upcomingOrToday = $allOrders->filter(function (Pesanan $order) use ($todayStr): bool {
            $mulai = $order->tgl_mulai ? Carbon::parse($order->tgl_mulai)->toDateString() : null;
            $selesai = $order->tgl_selesai ? Carbon::parse($order->tgl_selesai)->toDateString() : $mulai;

            return $selesai !== null && $selesai >= $todayStr;
        });

        $order = $upcomingOrToday->first(function (Pesanan $o) use ($todayStr): bool {
            $statusLower = strtolower((string) $o->status_pesanan);
            $mulai = $o->tgl_mulai ? Carbon::parse($o->tgl_mulai)->toDateString() : null;

            return $statusLower !== 'selesai' && $mulai !== null && $mulai <= $todayStr;
        })
            ?? $upcomingOrToday->first(fn (Pesanan $o): bool => strtolower((string) $o->status_pesanan) !== 'selesai')
            ?? $upcomingOrToday->first();

        if (! $order) {
            return null;
        }

        $firstDetail = $order->detailPesanan->first();
        $firstMenu = $firstDetail?->menu;

        $jamTiba = $firstDetail?->waktu
            ? substr((string) $firstDetail->waktu, 0, 5)
            : ($order->jadwal_pengiriman ? Carbon::parse($order->jadwal_pengiriman)->format('H:i') : '12:00');

        $statusLower = strtolower((string) $order->status_pesanan);
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

        $fotoUrl = Menu::resolveFotoUrl(
            $firstMenu?->foto_menu,
            'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80'
        );

        $extraCount = max(0, $order->detailPesanan->count() - 1);
        $portionDetail = ($firstDetail?->kuantitas ?? 1) . 'x Porsi';
        if ($extraCount > 0) {
            $portionDetail .= " (+{$extraCount} menu lainnya)";
        }

        $mulaiDate = $order->tgl_mulai ? Carbon::parse($order->tgl_mulai)->toDateString() : $todayStr;
        $scheduleDatePrefix = $mulaiDate > $todayStr
            ? Carbon::parse($mulaiDate)->locale('id')->translatedFormat('d M Y')
            : 'Hari Ini';

        $isMealPlan = ! is_null($order->id_plan);
        $isFlashSale = $order->detailPesanan->contains(fn ($d): bool => ! is_null($d->id_flash_sale));

        return [
            'id_pesanan'     => $order->id_pesanan,
            'schedule_label' => "{$scheduleDatePrefix} • Tiba {$jamTiba} WIB",
            'mitra_name'     => $order->umkm->nama_vendor ?? 'Mitra Plan2Eat',
            'menu_name'      => $firstMenu?->nama_makanan ?? 'Menu Katering',
            'portion_detail' => $portionDetail,
            'badge_text'     => $isMealPlan ? 'Pesanan Meal Plan' : ($isFlashSale ? 'Flash Sale' : 'Pesanan Hari Ini'),
            'image'          => $fotoUrl,
            'progress_step'  => $progressStep,
            'progress_label' => $progressLabel,
            'detail_url'     => route('pelanggan.pesanan-detail', $order->id_pesanan),
        ];
    }
}

