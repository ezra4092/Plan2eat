<?php

namespace App\Http\Controllers;

use App\Models\ProfilKesehatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilKesehatanController extends Controller
{
    /**
     * Menampilkan halaman profil dan pengaturan kesehatan pelanggan.
     */
    public function index(): View
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();
        $profil = ProfilKesehatan::where('id_user', $user->id_user)->first();

        $selectedPreferences = [];
        $customDietProgram = '';

        if ($profil) {
            $alergiList = is_array($profil->riwayat_alergi)
                ? $profil->riwayat_alergi
                : (json_decode((string) $profil->riwayat_alergi, true) ?: []);

            $alergiLower = array_map(fn ($v): string => strtolower(trim((string) $v)), $alergiList);

            if (in_array('seafood', $alergiLower, true) || in_array('no_seafood', $alergiLower, true)) {
                $selectedPreferences[] = 'no_seafood';
            }
            if (in_array('kacang-kacangan', $alergiLower, true) || in_array('kacang', $alergiLower, true) || in_array('nut_free', $alergiLower, true)) {
                $selectedPreferences[] = 'nut_free';
            }
            if (in_array('gluten', $alergiLower, true) || in_array('gluten_free', $alergiLower, true)) {
                $selectedPreferences[] = 'gluten_free';
            }

            $dietParts = array_filter(array_map('trim', explode(',', (string) $profil->jenis_diet)));
            $remainingDiet = [];

            foreach ($dietParts as $part) {
                $lower = strtolower($part);
                if ($lower === 'halal' || $lower === 'halal_only') {
                    $selectedPreferences[] = 'halal_only';
                } elseif ($lower === 'low calorie' || $lower === 'low_calorie') {
                    $selectedPreferences[] = 'low_calorie';
                } elseif ($lower === 'vegetarian') {
                    $selectedPreferences[] = 'vegetarian';
                } else {
                    $remainingDiet[] = $part;
                }
            }

            $customDietProgram = implode(', ', $remainingDiet);
            $selectedPreferences = array_values(array_unique($selectedPreferences));
        }

        return view('pelanggan.profil_kesehatan', compact(
            'user',
            'profil',
            'selectedPreferences',
            'customDietProgram'
        ));
    }

    /**
     * Menyimpan profil kesehatan baru sekaligus memperbarui data dasar akun user.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();
        $existingProfil = ProfilKesehatan::where('id_user', $user->id_user)->first();

        [$userData, $profilData] = $this->validateAndPrepare($request, $user);

        DB::transaction(function () use ($user, $existingProfil, $userData, $profilData): void {
            $user->update($userData);

            if ($existingProfil) {
                $existingProfil->update($profilData);
            } else {
                $profilData['id_user'] = $user->id_user;
                ProfilKesehatan::create($profilData);
            }
        });

        return redirect()
            ->route('pelanggan.profil_kesehatan')
            ->with('success', 'Profil kesehatan berhasil disimpan!');
    }

    /**
     * Memperbarui profil kesehatan yang sudah ada sekaligus data dasar akun user.
     */
    public function update(Request $request, int|string $id_profil): RedirectResponse
    {
        $user = User::where('id_user', Auth::id())->firstOrFail();
        $profil = ProfilKesehatan::where('id_profil', $id_profil)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        [$userData, $profilData] = $this->validateAndPrepare($request, $user);

        DB::transaction(function () use ($user, $profil, $userData, $profilData): void {
            $user->update($userData);
            $profil->update($profilData);
        });

        return redirect()
            ->route('pelanggan.profil_kesehatan')
            ->with('success', 'Profil kesehatan berhasil diperbarui!');
    }

    /**
     * Menghapus / mereset seluruh data profil kesehatan pelanggan.
     */
    public function destroy(int|string $id_profil): RedirectResponse
    {
        $profil = ProfilKesehatan::where('id_profil', $id_profil)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $profil->delete();

        return redirect()
            ->route('pelanggan.profil_kesehatan')
            ->with('deleted', 'Profil kesehatan berhasil direset!');
    }

    /**
     * Validasi dan pemetaan data input form profil kesehatan ke kolom tabel users dan profil_kesehatan.
     *
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    private function validateAndPrepare(Request $request, User $user): array
    {
        $validated = $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'no_hp'          => ['nullable', 'string', 'max:20'],
            'foto'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'preferences'    => ['nullable', 'array'],
            'preferences.*'  => ['string'],
            'jenis_diet'     => ['nullable', 'string', 'max:255'],
            'catatan_khusus' => ['nullable', 'string'],
            'batas_budget'   => ['nullable', 'integer', 'min:0'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'foto.image'            => 'File foto harus berupa gambar.',
            'foto.max'              => 'Ukuran foto maksimal 2MB.',
            'batas_budget.integer'  => 'Batas budget harus berupa angka bulat.',
        ]);

        $userData = [
            'nama_lengkap' => trim($validated['nama_lengkap']),
            'no_hp'        => $request->filled('no_hp') ? trim((string) $validated['no_hp']) : null,
        ];

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('user_photos', 'public');
        }

        // Pemetaan preferences[] ke riwayat_alergi (JSON array) & jenis_diet (string)
        $preferences = (array) ($validated['preferences'] ?? []);

        $alergiMap = [
            'no_seafood'  => 'Seafood',
            'nut_free'    => 'Kacang-kacangan',
            'gluten_free' => 'Gluten',
        ];
        $dietMap = [
            'halal_only'  => 'Halal',
            'low_calorie' => 'Low Calorie',
            'vegetarian'  => 'Vegetarian',
        ];

        $riwayatAlergi = [];
        $dietTags = [];

        foreach ($preferences as $pref) {
            if (isset($alergiMap[$pref])) {
                $riwayatAlergi[] = $alergiMap[$pref];
            }
            if (isset($dietMap[$pref])) {
                $dietTags[] = $dietMap[$pref];
            }
        }

        $customDiet = $request->filled('jenis_diet') ? trim((string) $validated['jenis_diet']) : '';
        if ($customDiet !== '') {
            foreach (array_filter(array_map('trim', explode(',', $customDiet))) as $part) {
                if (! in_array($part, $dietTags, true)) {
                    $dietTags[] = $part;
                }
            }
        }

        $profilData = [
            'riwayat_alergi' => ! empty($riwayatAlergi) ? array_values(array_unique($riwayatAlergi)) : null,
            'jenis_diet'     => ! empty($dietTags) ? implode(', ', array_unique($dietTags)) : null,
            'catatan_khusus' => $request->filled('catatan_khusus') ? trim((string) $validated['catatan_khusus']) : null,
            'batas_budget'   => $request->filled('batas_budget') ? (int) $validated['batas_budget'] : null,
        ];

        return [$userData, $profilData];
    }
}
