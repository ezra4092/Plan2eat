<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umkm;

class VerifikasiUmkmController extends Controller
{
    /**
     * Menampilkan halaman dashboard verifikasi (Daftar UMKM)
     */
    public function index()
    {
        // Ambil semua data UMKM beserta data User (untuk menampilkan nama pemilik/email)
        $umkmList = Umkm::with('user')->orderBy('id_umkm', 'desc')->get();
        
        return view('admin.verifikasi_umkm', compact('umkmList'));
    }

    /**
     * Memproses persetujuan atau penolakan UMKM
     */
    public function update(Request $request, $id_umkm)
    {
        // Validasi input enum dan catatan
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string' 
        ]);

        $umkm = Umkm::findOrFail($id_umkm);
        
        // Simpan ke database
        $umkm->status_verifikasi = $request->status_verifikasi;
        $umkm->catatan = $request->catatan;
        $umkm->save();

        $pesan = ($request->status_verifikasi === 'disetujui') 
            ? 'UMKM berhasil disetujui.' 
            : 'Pengajuan UMKM ditolak.';

        return redirect()->route('admin.umkm')->with('success', $pesan);
    }
}