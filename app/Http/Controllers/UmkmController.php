<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UmkmController extends Controller
{
    // Menampilkan profil akun dan profil UMKM
    public function index()
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_user', $user->id_user)->firstOrFail();

        return view('umkm.profil', compact('user', 'umkm'));
    }

    // Memperbarui data user dan UMKM
    public function update(Request $request, $id_umkm)
    {
        // Memastikan UMKM merupakan milik user yang login
        $user = User::where('id_user', Auth::id())->firstOrFail();

        $umkm = Umkm::where('id_umkm', $id_umkm)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        // Mengubah input kosong menjadi NULL
        $request->merge([
            'deskripsi_toko' => $request->filled('deskripsi_toko')
                ? trim($request->deskripsi_toko)
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
            'metode_pembayaran' => 'required|string|max:100',
        ]);

        // Update kedua tabel secara bersamaan
        DB::transaction(function () use (
            $user,
            $umkm,
            $validatedUser,
            $validatedUmkm
        ) {
            $user->update($validatedUser);
            $umkm->update($validatedUmkm);
        });

        return redirect()
            ->route('umkm.profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    // Logout akun UMKM
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page');
    }
}