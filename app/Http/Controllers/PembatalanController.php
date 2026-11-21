<?php

namespace App\Http\Controllers;

use App\Models\Flashsale;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembatalanController extends Controller
{
    /**
     * Memproses pembatalan pesanan pelanggan:
     * - Hanya berlaku untuk pesanan yang berasal dari Meal Plan (bukan Flash Sale / checkout dadakan).
     * - Hanya berlaku maksimal 6 jam setelah pembayaran berhasil.
     * - Mengembalikan stok menu & mengembalikan dana pembayaran ke saldo akun pelanggan.
     */
    public function batal(Request $request, int|string $id_pesanan): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $userId = Auth::id();
        if (! $userId) {
            return redirect()->route('login_page');
        }

        $pesanan = Pesanan::with(['detailPesanan.menu', 'detailPesanan.flashSale', 'umkm'])
            ->where('id_user', $userId)
            ->where('id_pesanan', $id_pesanan)
            ->firstOrFail();

        $statusLower = strtolower((string) $pesanan->status_pesanan);

        if (in_array($statusLower, ['selesai', 'dibatalkan'], true)) {
            return redirect()
                ->route('pelanggan.langganan')
                ->with('error', 'Pesanan ini sudah selesai atau sebelumnya sudah dibatalkan.');
        }

        // 1. Tolak pembatalan jika pesanan merupakan checkout dadakan (Flash Sale atau tanpa Meal Plan)
        $isFlashSaleOrder = $pesanan->detailPesanan->contains(function ($detail): bool {
            return ! is_null($detail->id_flash_sale);
        });

        if (is_null($pesanan->id_plan) || $isFlashSaleOrder) {
            return redirect()
                ->route('pelanggan.langganan')
                ->with('error', 'Pesanan dadakan (Flash Sale atau pemesanan langsung) tidak dapat dibatalkan.');
        }

        // 2. Tolak pembatalan jika sudah melewati batas 6 jam setelah pembayaran berhasil
        $paidAt = $pesanan->waktu_pembayaran ?? $pesanan->tgl_mulai;
        if (! $paidAt) {
            return redirect()
                ->route('pelanggan.langganan')
                ->with('error', 'Waktu pembayaran pesanan tidak ditemukan sehingga tidak dapat dibatalkan.');
        }

        $deadline = Carbon::parse($paidAt)->timezone('Asia/Jakarta')->addHours(6);
        if (now('Asia/Jakarta')->gt($deadline)) {
            return redirect()
                ->route('pelanggan.langganan')
                ->with('error', 'Batas waktu pembatalan (maksimal 6 jam setelah pembayaran berhasil) telah berakhir.');
        }

        $refundAmount = (float) $pesanan->total_pembayaran;
        $updatedSaldo = 0.0;

        DB::transaction(function () use ($pesanan, $validated, $userId, $refundAmount, &$updatedSaldo): void {
            // 3. Ubah status pesanan menjadi dibatalkan beserta catatan pelanggan
            $pesanan->update([
                'status_pesanan' => 'dibatalkan',
                'catatan' => trim($validated['catatan']),
            ]);

            // 4. Kembalikan stok menu yang dipesan
            foreach ($pesanan->detailPesanan as $detail) {
                $qty = max(1, (int) $detail->kuantitas);

                if ($detail->id_flash_sale) {
                    Flashsale::where('id_flash_sale', $detail->id_flash_sale)
                        ->increment('stok_sisa', $qty);
                } else {
                    Menu::where('id_menu', $detail->id_menu)
                        ->increment('stok_harian', $qty);
                }
            }

            // 5. Kembalikan dana (refund) ke saldo pelanggan
            $user = User::lockForUpdate()->findOrFail($userId);
            $user->increment('saldo', $refundAmount);
            $updatedSaldo = (float) $user->fresh()->saldo;
        });

        return redirect()
            ->route('pelanggan.langganan')
            ->with('refund_dialog', [
                'id_pesanan' => $pesanan->id_pesanan,
                'mitra_name' => $pesanan->umkm->nama_vendor ?? 'Mitra Plan2Eat',
                'refund_amount' => $refundAmount,
                'new_saldo' => $updatedSaldo,
                'catatan' => trim($validated['catatan']),
                'metode_awal' => $pesanan->metode_pembayaran ?? '-',
            ]);
    }
}
