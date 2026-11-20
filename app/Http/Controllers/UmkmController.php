<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\User;
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
     * Menampilkan profil akun dan profil UMKM.
     */
    public function index(): View
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_user', $user->id_user)->firstOrFail();

        return view('umkm.profil', compact('user', 'umkm'));
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
            'metode_pembayaran' => 'required',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Normalisasi metode_pembayaran agar selalu tersimpan sebagai array JSON
        $metodePembayaran = $validatedUmkm['metode_pembayaran'];
        if (is_string($metodePembayaran)) {
            $metodePembayaran = [$metodePembayaran];
        }
        $validatedUmkm['metode_pembayaran'] = array_values((array) $metodePembayaran);

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
     * Menampilkan daftar mitra UMKM yang aktif (Buka) dan terverifikasi untuk pelanggan.
     */
    public function pelangganMitra(): View
    {
        $mitras = Umkm::with([
            'menus' => function ($query): void {
                $query->where('status', true)
                    ->where('stok_harian', '>', 0)
                    ->orderBy('id_menu', 'desc');
            },
        ])
            ->terverifikasi()
            ->buka()
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
        }

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
