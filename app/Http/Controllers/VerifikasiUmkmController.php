<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerifikasiUmkmController extends Controller
{
    /**
     * Menampilkan daftar mitra UMKM yang mendaftar untuk proses verifikasi admin.
     */
    public function index(): View
    {
        $umkmList = Umkm::with(['user', 'menus.jenisMenu'])
            ->orderByRaw("CASE WHEN LOWER(CAST(status_verifikasi AS TEXT)) = 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('id_umkm')
            ->get();

        return view('admin.verifikasi_umkm', compact('umkmList'));
    }

    /**
     * Memproses persetujuan atau pembatalan/penolakan pendaftaran UMKM.
     */
    public function update(Request $request, int|string $id_umkm): RedirectResponse
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan'           => 'required_if:status_verifikasi,ditolak|nullable|string|max:500',
        ], [
            'status_verifikasi.required' => 'Status verifikasi wajib dipilih.',
            'status_verifikasi.in'       => 'Status verifikasi tidak valid.',
            'catatan.required_if'        => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $umkm = Umkm::with('user')->findOrFail($id_umkm);

        DB::transaction(function () use ($umkm, $validated): void {
            $umkm->status_verifikasi = $validated['status_verifikasi'];
            $umkm->catatan = $validated['status_verifikasi'] === 'ditolak'
                ? trim((string) ($validated['catatan'] ?? ''))
                : null;
            $umkm->save();

            if ($validated['status_verifikasi'] === 'disetujui' && $umkm->user) {
                $umkm->user->is_suspended = false;
                $umkm->user->save();
            }
        });

        $pesan = $validated['status_verifikasi'] === 'disetujui'
            ? "Mitra {$umkm->nama_vendor} berhasil disetujui."
            : "Pendaftaran {$umkm->nama_vendor} ditolak beserta catatan alasan.";

        return redirect()->route('admin.umkm')->with('success', $pesan);
    }
}
