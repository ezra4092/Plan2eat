<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    // Menampilkan halaman profil UMKM milik user yang sedang login
    public function index()
    {
        $umkm = Umkm::where('id_user', Auth::id())->first();

        return view('umkm.profil', compact('umkm'));
    }

    // Memperbarui data profil UMKM
    public function update(Request $request, $id_umkm)
    {
        $umkm = Umkm::where('id_umkm', $id_umkm)
            ->where('id_user', Auth::id()) // Memastikan hanya pemilik toko yang bisa edit
            ->firstOrFail();

        $validated = $request->validate([
            'nama_vendor'        => 'required|string|max:255',
            'deskripsi_toko'     => 'nullable|string',
            'alamat_lengkap'     => 'nullable|string',
            'jam_buka'           => 'nullable',
            'jam_tutup'          => 'nullable',
            'status_operasional' => 'required|string|max:50',
            'metode_pembayaran'  => 'nullable|string|max:255',
        ]);

        // Kolom 'saldo' sengaja tidak dimasukkan ke validasi update agar tidak bisa diubah manual lewat form profil

        $umkm->update($validated);

        return redirect()->route('umkm.profil.index')
            ->with('success', 'Profil UMKM berhasil diperbarui!');
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page');
    }
}