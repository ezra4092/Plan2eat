<?php

namespace App\Services;

use App\Models\DetailPesanan;
use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Pesanan;
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
            $totalPembayaran = 0;

            // Pastikan status_pesanan menggunakan lowercase sesuai check constraint PostgreSQL
            // Nilai valid: 'menunggu pembayaran', 'persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'
            $statusInput = isset($data['status_pesanan']) ? strtolower($data['status_pesanan']) : 'persiapan';
            $validStatuses = ['menunggu pembayaran', 'persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'];
            $statusPesanan = in_array($statusInput, $validStatuses, true) ? $statusInput : 'persiapan';

            // 1. Simpan data utama ke tabel 'pesanan'
            $pesanan = Pesanan::create([
                'id_user'           => $userId,
                'id_umkm'           => $data['id_umkm'],
                'id_plan'           => $data['id_plan'] ?? null,
                'status_pesanan'    => $statusPesanan,
                'total_pembayaran'  => 0,
                'tgl_mulai'         => $data['tgl_mulai'] ?? now(),
                'tgl_selesai'       => $data['tgl_selesai'] ?? null,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'metode_pengiriman' => $data['metode_pengiriman'],
                'jadwal_pengiriman' => $data['jadwal_pengiriman'] ?? now(),
            ]);

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
                $totalPembayaran += $subtotal;

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

            // 4. Update total akhir pada tabel 'pesanan'
            $pesanan->update([
                'total_pembayaran' => $totalPembayaran,
            ]);

            return $pesanan;
        });
    }
}

