<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrasi Akun UMKM — Plan2Eat</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
  <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>
<body class="login-page-body">

<main id="umkmAccountStep" class="login-page-user">
  <section class="authentication">
    {{-- Brand Header Plan2Eat --}}
    <div class="brand-header">
      <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat - Plan It, Order It, Irit" class="horizontal-terang">
    </div>

    {{-- Heading --}}
    <div class="auth-heading">
      <h1 class="text-wrapper">Daftar Akun UMKM</h1>
      <p class="subtitle">Silakan masukkan detail akun Anda di bawah ini</p>
    </div>

    @if ($errors->any())
      <div class="auth-alert-error" role="alert">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form Daftar Akun UMKM --}}
    <form class="auth-form" action="{{ route('register') }}" method="POST">
      @csrf
      <input type="hidden" name="role" value="umkm">

      <div class="form-group-custom">
        <label for="umkm_name" class="label">Nama Lengkap</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="text" id="umkm_name" name="name" class="container-2" placeholder="Nama lengkap" value="{{ old('name') }}" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_email" class="label">Email</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="3"/>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="email" id="umkm_email" name="email" class="container-2" placeholder="joe@gmail.com" value="{{ old('email') }}" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_password" class="label">Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="password" id="umkm_password" name="password" class="container-2" placeholder="***************" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_password_confirmation" class="label">Konfirmasi Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="password" id="umkm_password_confirmation" name="password_confirmation" class="container-2" placeholder="***************" required>
          </div>
        </div>
      </div>

      <button type="submit" class="action-primary">
        <span class="text-wrapper-4">Lanjutkan</span>
      </button>
    </form>

    <div class="auth-card-bottom-line"></div>
  </section>

  <div class="container-wrapper" style="flex-direction: column; gap: 6px;">
    <p class="belum-punya-akun">
      Sudah Punya Akun?
      <a href="{{ route('login_page') }}" class="text-wrapper-5">Login</a>
    </p>
  </div>
</main>

</body>
</html>
