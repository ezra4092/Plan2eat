<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    // Menampilkan halaman daftar pesanan yang sudah selesai untuk diulas
    public function index()
    {
        $id_user = Auth::id() ?? 1; // Hapus "?? 1" jika otentikasi login sudah jalan

        // Ambil pesanan yang sudah selesai beserta relasi ulasan dan detail UMKM-nya
        $pesananSelesai = Pesanan::with(['ulasan', 'umkm'])
            ->where('id_user', $id_user)
            ->where('status_pesanan', 'selesai')
            ->orderBy('tgl_selesai', 'desc')
            ->get();

        return view('pelanggan.ulasan', compact('pesananSelesai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'rating'     => 'required|integer|min:1|max:5',
            'ulasan'     => 'nullable|string',
            'foto_ulasan'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Opsional tapi divalidasi jika ada
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);
        $id_user = Auth::id() ?? 1;

        // 1. Validasi Kepemilikan
        if ($pesanan->id_user !== $id_user) {
            return back()->with('error', 'Akses ditolak. Ini bukan pesanan Anda.');
        }

        // 2. Validasi Status Pesanan
        if ($pesanan->status_pesanan !== 'selesai') {
            return back()->with('error', 'Pesanan belum diselesaikan.');
        }

        // 3. Pencegahan Ulasan Ganda
        if (Ulasan::where('id_pesanan', $pesanan->id_pesanan)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        // 4. Proses Upload Gambar (jika ada)
        $path = null;
        if ($request->hasFile('foto_ulasan')) {
            $path = app(MediaStorageService::class)->upload($request->file('foto_ulasan'), 'ulasan_images');
        }

        Ulasan::create([
            'id_pesanan'  => $pesanan->id_pesanan,
            'id_user'     => $id_user,
            'rating'      => $request->rating,
            'ulasan'      => $request->ulasan,
            'foto_ulasan' => $path // Pastikan kolom ini sudah ditambah di DB & Model
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

}