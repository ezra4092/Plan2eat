<?php

namespace App\Http\Controllers;

use App\Models\ProfilKesehatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilKesehatanController extends Controller
{
    public function index()
    {
        $profil = ProfilKesehatan::where('id_user', Auth::id())->first();
        $pesan = $profil ? null : 'Data profil kesehatan tidak ada/belum ditambahkan';
        return view('pelanggan.profil_kesehatan', compact('profil', 'pesan'));
    }

    public function store(Request $request)
    {
        $cekProfil = ProfilKesehatan::where('id_user', Auth::id())->first();
        if ($cekProfil) {
            return redirect()->route('pelanggan.profil_kesehatan')
                ->with('error', 'Profil kesehatan sudah ada. Silakan gunakan tombol Perbarui.');
        }

        $validated = $request->validate([
            'riwayat_alergi' => 'nullable|string|max:255',
            'jenis_diet'     => 'nullable|string|max:255',
            'catatan_khusus' => 'nullable|string',
            'batas_budget'   => 'nullable|integer|min:0',
        ]);

        $validated['id_user'] = Auth::id();

        ProfilKesehatan::create($validated);

        return redirect()->route('pelanggan.profil_kesehatan')
            ->with('success', 'Profil kesehatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id_profil)
    {
        $profil = ProfilKesehatan::where('id_profil', $id_profil)
            ->where('id_user', Auth::id()) 
            ->firstOrFail();

        $validated = $request->validate([
            'riwayat_alergi' => 'nullable|string|max:255',
            'jenis_diet'     => 'nullable|string|max:255',
            'catatan_khusus' => 'nullable|string',
            'batas_budget'   => 'nullable|integer|min:0',
        ]);

        $profil->update($validated);

        return redirect()->route('pelanggan.profil_kesehatan')
            ->with('success', 'Profil kesehatan berhasil diperbarui!');
    }

    public function destroy($id_profil)
    {
        $profil = ProfilKesehatan::where('id_profil', $id_profil)
            ->where('id_user', Auth::id()) 
            ->firstOrFail();

        $profil->delete();

        return redirect()->route('pelanggan.profil_kesehatan')
            ->with('success', 'Profil kesehatan berhasil dihapus!');
    }
}