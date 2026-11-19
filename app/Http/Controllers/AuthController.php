<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login_page');
    }

    public function showRegistrationForm()
    {
        return view('registpelanggan');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
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
            'pending_registration' => [
                'nama_lengkap' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pelanggan',
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
            function ($message) use ($validated) {
                $message->to($validated['email'])
                    ->subject('Kode Verifikasi Akun Plan2Eat');
            }
        );

        return redirect()->route('otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showOtpForm()
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register.form')
                ->withErrors(['email' => 'Silakan lakukan registrasi terlebih dahulu.']);
        }

        return view('otp', [
            'email' => $pending['email'],
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register.form')
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
            session()->forget('pending_registration');

            return redirect()->route('register.form')
                ->withErrors(['email' => 'Terlalu banyak percobaan OTP. Silakan registrasi kembali.']);
        }

        if (!Hash::check($request->otp_code, $pending['otp_hash'])) {
            $pending['otp_attempts']++;

            session(['pending_registration' => $pending]);

            return back()->withErrors([
                'otp_code' => 'Kode OTP yang kamu masukkan salah.',
            ]);
        }

        if (User::where('email', $pending['email'])->exists()) {
            session()->forget('pending_registration');

            return redirect()->route('register.form')
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan login.']);
        }

        $user = User::create([
            'nama_lengkap' => $pending['nama_lengkap'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'role' => $pending['role'],
        ]);

        session()->forget('pending_registration');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.personalisasi')
            ->with('success', 'Registrasi berhasil. Selamat datang di Plan2Eat!');
    }

    public function resendOtp()
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register.form')
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
            function ($message) use ($pending) {
                $message->to($pending['email'])
                    ->subject('Kode Verifikasi Akun Plan2Eat');
            }
        );

        return redirect()->route('otp.form')
            ->with('success', 'Kode OTP baru berhasil dikirim.');
    }

    public function login(Request $request)
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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login_page');
    }
}