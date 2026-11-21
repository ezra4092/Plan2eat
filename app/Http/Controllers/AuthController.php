<?php

namespace App\Http\Controllers;

use App\Models\ProfilKesehatan;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login');
    }

    public function showPilihanForm(): View
    {
        return view('pilihan');
    }

    public function showRegistrationForm(Request $request): View|RedirectResponse
    {
        $role = $request->query('role') ?? session('register_role');

        if (! in_array($role, ['pelanggan', 'umkm'], true)) {
            return redirect()->route('register.pilihan')
                ->withErrors(['role' => 'Silakan pilih peran pendaftaran terlebih dahulu (Pelanggan atau UMKM).']);
        }

        session(['register_role' => $role]);

        return view('regist', [
            'role' => $role,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        if (! $request->filled('role') && session()->has('register_role')) {
            $request->merge(['role' => session('register_role')]);
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['pelanggan', 'umkm'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'role.required' => 'Peran akun wajib dipilih.',
            'role.in' => 'Peran akun tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $otp = (string) random_int(100000, 999999);

        session([
            'register_role' => $validated['role'],
            'pending_registration' => [
                'nama_lengkap' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'otp_hash' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(5)->timestamp,
                'otp_attempts' => 0,
            ],
        ]);

        Mail::raw(
            "Halo {$validated['name']},\n\n"
            . "Kode verifikasi akun Plan2Eat kamu adalah:\n\n"
            . "{$otp}\n\n"
            . "Kode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapa pun.\n\n"
            . "Terima kasih,\nTim Plan2Eat",
            function ($message) use ($validated): void {
                $message->to($validated['email'])
                    ->subject('Kode Verifikasi Akun Plan2Eat');
            }
        );

        return redirect()->route('otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showOtpForm(): View|RedirectResponse
    {
        $pending = session('pending_registration');

        if (! $pending) {
            return redirect()->route('register.pilihan')
                ->withErrors(['email' => 'Silakan lakukan registrasi terlebih dahulu.']);
        }

        return view('otp', [
            'email' => $pending['email'],
            'role' => $pending['role'],
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $pending = session('pending_registration');

        if (! $pending) {
            return redirect()->route('register.pilihan')
                ->withErrors(['email' => 'Sesi registrasi tidak ditemukan. Silakan daftar kembali.']);
        }

        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.digits' => 'Kode OTP harus terdiri dari 6 angka.',
        ]);

        if (now()->timestamp > $pending['otp_expires_at']) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode.',
            ]);
        }

        if ($pending['otp_attempts'] >= 5) {
            session()->forget(['pending_registration', 'register_role']);

            return redirect()->route('register.pilihan')
                ->withErrors(['email' => 'Terlalu banyak percobaan OTP. Silakan registrasi kembali.']);
        }

        if (! Hash::check($request->otp_code, $pending['otp_hash'])) {
            $pending['otp_attempts']++;

            session(['pending_registration' => $pending]);

            return back()->withErrors([
                'otp_code' => 'Kode OTP yang kamu masukkan salah.',
            ]);
        }

        if (User::where('email', $pending['email'])->exists()) {
            session()->forget(['pending_registration', 'register_role']);

            return redirect()->route('login_page')
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan login.']);
        }

        $user = User::create([
            'nama_lengkap' => $pending['nama_lengkap'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'role' => $pending['role'],
        ]);

        session()->forget(['pending_registration', 'register_role']);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'umkm') {
            return redirect()->route('umkm.pendaftaran-mitra')
                ->with('success', 'Verifikasi OTP berhasil! Silakan lengkapi pendaftaran warung Anda.');
        }

        return redirect()->route('pelanggan.personalisasi')
            ->with('success', 'Verifikasi OTP berhasil! Silakan pilih preferensi makan Anda.');
    }

    public function resendOtp(): RedirectResponse
    {
        $pending = session('pending_registration');

        if (! $pending) {
            return redirect()->route('register.pilihan')
                ->withErrors(['email' => 'Sesi registrasi tidak ditemukan. Silakan daftar kembali.']);
        }

        $otp = (string) random_int(100000, 999999);

        $pending['otp_hash'] = Hash::make($otp);
        $pending['otp_expires_at'] = now()->addMinutes(5)->timestamp;
        $pending['otp_attempts'] = 0;

        session(['pending_registration' => $pending]);

        Mail::raw(
            "Halo {$pending['nama_lengkap']},\n\n"
            . "Kode verifikasi akun Plan2Eat kamu adalah:\n\n"
            . "{$otp}\n\n"
            . "Kode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapa pun.\n\n"
            . "Terima kasih,\nTim Plan2Eat",
            function ($message) use ($pending): void {
                $message->to($pending['email'])
                    ->subject('Kode Verifikasi Akun Plan2Eat');
            }
        );

        return redirect()->route('otp.form')
            ->with('success', 'Kode OTP baru berhasil dikirim.');
    }

    public function showPersonalisasiForm(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login_page');
        }

        return view('personalisasi');
    }

    public function storePersonalisasi(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login_page');
        }

        $preferences = (array) $request->input('preferences', []);

        $alergiMap = [
            'no_seafood' => 'Seafood',
            'nut_free' => 'Kacang-kacangan',
            'gluten_free' => 'Gluten',
        ];
        $dietMap = [
            'halal_only' => 'Halal',
            'low_calorie' => 'Low Calorie',
            'vegetarian' => 'Vegetarian',
        ];

        $alergi = [];
        $diet = [];

        foreach ($preferences as $pref) {
            if (isset($alergiMap[$pref])) {
                $alergi[] = $alergiMap[$pref];
            }
            if (isset($dietMap[$pref])) {
                $diet[] = $dietMap[$pref];
            }
        }

        ProfilKesehatan::updateOrCreate(
            ['id_user' => Auth::id()],
            [
                'riwayat_alergi' => ! empty($alergi) ? $alergi : null,
                'jenis_diet' => ! empty($diet) ? implode(', ', $diet) : null,
            ]
        );

        return redirect()->route('pelanggan.beranda')
            ->with('success', 'Preferensi berhasil disimpan! Selamat datang di Plan2Eat.');
    }

    public function showPendaftaranMitraForm(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login_page');
        }

        return view('pendaftaran');
    }

    public function storePendaftaranMitra(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login_page');
        }

        $validated = $request->validate([
            'ktp_name' => ['required', 'string', 'max:255'],
            'store_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'pickup_address' => ['required', 'string'],
        ], [
            'ktp_name.required' => 'Nama lengkap sesuai KTP wajib diisi.',
            'store_name.required' => 'Nama warung / dapur wajib diisi.',
            'whatsapp_number.required' => 'Nomor WhatsApp usaha wajib diisi.',
            'pickup_address.required' => 'Alamat lengkap pengambilan wajib diisi.',
        ]);

        $user = User::where('id_user', Auth::id())->firstOrFail();

        DB::transaction(function () use ($user, $validated): void {
            $user->update([
                'nama_lengkap' => $validated['ktp_name'],
                'no_hp' => $validated['whatsapp_number'],
            ]);

            Umkm::updateOrCreate(
                ['id_user' => $user->id_user],
                [
                    'nama_vendor' => $validated['store_name'],
                    'alamat_lengkap' => $validated['pickup_address'],
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '21:00:00',
                    'status_operasional' => 'Buka',
                    'status_verifikasi' => 'menunggu',
                    'rating' => 0,
                    'saldo' => 0,
                ]
            );
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page')
            ->with('success', 'Pendaftaran UMKM berhasil! Akun toko Anda sedang menunggu verifikasi Admin sebelum dapat masuk.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'umkm') {
                $umkm = Umkm::where('id_user', $user->id_user)->first();

                if (! $umkm) {
                    return redirect()->route('umkm.pendaftaran-mitra')
                        ->with('success', 'Silakan lengkapi pendaftaran toko UMKM Anda terlebih dahulu.');
                }

                if (! $umkm->isTerverifikasi()) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withErrors([
                        'email' => 'Akun toko UMKM Anda masih menunggu verifikasi Admin.',
                    ])->onlyInput('email');
                }

                return redirect()->route('umkm.dashboard');
            } elseif ($user->role === 'pelanggan') {
                return redirect()->route('pelanggan.beranda');
            }

            Auth::logout();

            return redirect()->route('login_page');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page');
    }
}
