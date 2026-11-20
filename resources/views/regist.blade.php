<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Plan2Eat</title>

    {{-- Google Fonts: Plus Jakarta Sans & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Stylesheets --}}
    <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>

<body class="login-page-body">
    @php
        $selectedRole = old('role', $role ?? session('register_role', 'pelanggan'));
        if (!in_array($selectedRole, ['pelanggan', 'umkm'], true)) {
            $selectedRole = 'pelanggan';
        }
    @endphp

    <main class="login-page-user">
        <section class="authentication" aria-labelledby="register-heading">
            {{-- Brand Header --}}
            <div class="brand-header">
                <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat - Plan It, Order It, Irit" class="horizontal-terang">
            </div>

            {{-- Heading & Subtitle --}}
            <div class="auth-heading">
                <h1 id="register-heading" class="text-wrapper">Daftar Akun Baru</h1>
                <p class="subtitle">
                    Mendaftar sebagai
                    <strong style="color: #16442B;">{{ $selectedRole === 'umkm' ? 'Mitra UMKM' : 'Pelanggan' }}</strong>
                    &bull; <a href="{{ route('register.pilihan') }}" style="color: #E8702A; text-decoration: none; font-weight: 600;">Ubah</a>
                </p>
            </div>

            {{-- Notifikasi Error Validasi --}}
            @if ($errors->any())
                <div class="auth-alert-error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Registrasi --}}
            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf
                <input type="hidden" name="role" value="{{ $selectedRole }}">

                {{-- Nama Lengkap --}}
                <div class="form-group-custom">
                    <label for="name" class="label">Nama Lengkap</label>
                    <div class="phone-OTP-input @error('name') is-invalid @enderror">
                        <span class="background" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <div class="input-wrapper">
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="container-2"
                                placeholder="Nama lengkap"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="form-group-custom">
                    <label for="email" class="label">Email</label>
                    <div class="phone-OTP-input @error('email') is-invalid @enderror">
                        <span class="background" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="3"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="container-2"
                                placeholder="joe@gmail.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group-custom">
                    <label for="password" class="label">Password</label>
                    <div class="phone-OTP-input @error('password') is-invalid @enderror">
                        <span class="background" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="3"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                <circle cx="12" cy="16.5" r="1"/>
                            </svg>
                        </span>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="container-2"
                                placeholder="***************"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                        <button type="button" class="btn-toggle-pass" data-target="password" aria-label="Tampilkan atau sembunyikan password">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#717972" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group-custom">
                    <label for="password_confirmation" class="label">Konfirmasi Password</label>
                    <div class="phone-OTP-input">
                        <span class="background" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="3"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                <circle cx="12" cy="16.5" r="1"/>
                            </svg>
                        </span>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="container-2"
                                placeholder="***************"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                        <button type="button" class="btn-toggle-pass" data-target="password_confirmation" aria-label="Tampilkan atau sembunyikan konfirmasi password">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#717972" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="action-primary">
                    <span class="text-wrapper-4">Lanjutkan</span>
                </button>
            </form>

            <div class="auth-card-bottom-line"></div>
        </section>

        {{-- Footer Link Login --}}
        <!-- <footer class="container-wrapper">
            <p class="belum-punya-akun">
                Sudah Punya Akun?
                <a href="{{ route('login_page') }}" class="text-wrapper-5">Login</a>
            </p>
        </footer> -->
    </main>

    <script>
        document.querySelectorAll('.btn-toggle-pass').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                if (!input) return;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>
</body>
</html>
