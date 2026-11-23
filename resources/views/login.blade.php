<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | Plan2Eat</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="..\images\favicon.ico">


    <!-- Stylesheets -->
    <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>
<body class="login-page-body">

    <main class="login-page-user">
        <div class="authentication">
            <div style="width: 100%; display: flex; justify-content: flex-start; margin-bottom: 4px;">
                <a href="{{ route('landing_page') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #16442B; text-decoration: none;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
            
            <!-- Brand Header / Logo -->
            <div class="brand-header">
                <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat - Plan It, Order It, Irit" class="horizontal-terang">
            </div>

            <!-- Title & Subtitle -->
            <div class="auth-heading">
                <h1 class="text-wrapper">Selamat Datang Kembali!</h1>
                <p class="subtitle">Silakan masukkan detail Anda untuk masuk</p>
            </div>

            <!-- Form Login -->
            <form class="auth-form" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Input Email -->
                <div class="form-group-custom">
                    <label for="email" class="label">Email</label>
                    <div class="phone-OTP-input">
                        <div class="background">
                            <!-- Icon Email (SVG) -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="3"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </div>
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="container-2" placeholder="joe@gmail.com" value="{{ old('email') }}" autofocus required>
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


                <!-- Submit Button -->
                <button type="submit" class="action-primary">
                    <span class="text-wrapper-4">Masuk Sekarang</span>
                    <svg class="icon-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="divider-margin">
                <div class="divider">
                    <div class="horizontal-divider"></div>
                    <!-- <span class="text">atau masuk dengan</span> -->
                    <!-- <div class="horizontal-divider"></div> -->
                </div>
            </div>

            <!-- Social Sign-In (Google) -->
            <!-- <div class="social-sign-in-grid">
                <a href="{{ url('auth/google') }}" class="button-google">
                    <svg class="SVG" width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.79-.07-1.54-.19-2.27h-11.3v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82Z"/>
                        <path fill="#34A853" d="M12.255 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96h-3.98v3.09C3.515 21.3 7.565 24 12.255 24Z"/>
                        <path fill="#FBBC05" d="M5.525 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62h-3.98a11.86 11.86 0 0 0 0 10.76l3.98-3.09Z"/>
                        <path fill="#EA4335" d="M12.255 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C18.205 1.19 15.495 0 12.255 0c-4.69 0-8.74 2.7-10.71 6.62l3.98 3.09c.95-2.85 3.6-4.96 6.73-4.96Z"/>
                    </svg>
                    <span class="text-2">Google</span>
                </a>
            </div> -->

            <!-- Footer Register Link -->
            <div class="container-wrapper">
                <p class="belum-punya-akun">
                    Belum Punya akun? <a href="{{ route('register.pilihan') }}" class="text-wrapper-5">Registrasi</a>
                </p>
            </div>

        </div>
    </main>

    @include('partials.popup_notification')

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