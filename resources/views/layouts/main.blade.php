<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Plan2Eat')</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('template/css/globals.css') }}">
  <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
  <script src="https://kit.fontawesome.com/04689dffa8.js" crossorigin="anonymous"></script>
  @stack('styles')
</head>
<body class="app-shell">

  <div class="app-layout" id="appLayout">
    <!-- Navigasi (Bottom Bar di Mobile, Left Sidebar di Desktop) -->
    @include('layouts.nav')

    <!-- Wrapper Sisi Kanan (Topbar + Konten Halaman) -->
    <div class="app-content-wrapper">
      
      <!-- Top Header -->
      <header class="app-header">
        <div class="header-inner">
          <!-- Baris 1 Mobile / Kanan Desktop: Logo (Mobile) & Aksi User -->
          <div class="header-top-row">
            <a href="{{ url('/beranda') }}" class="brand-logo-group mobile-only-brand">
              <img class="brand-logo-icon" src="{{ asset('img/logo.svg') }}" alt="Plan2Eat Logo">
              <span class="brand-logo-text">Plan2Eat</span>
            </a>

            <div class="header-user-actions">
              <button type="button" class="btn-notification" aria-label="Notifikasi">
                <img src="{{ asset('img/icon-14.svg') }}" alt="Notifikasi">
              </button>
              <a href="{{ url('/profil') }}" class="profile-avatar-link" aria-label="Profil Pengguna">
                <span class="desktop-user-name">Nandana</span>
                <div class="profile-avatar" style="background-image: url('{{ asset('img/profile.jpg') }}');"></div>
              </a>
            </div>
          </div>

          <!-- Baris 2 Mobile / Kiri Desktop: Lokasi & Indikator Halaman Aktif -->
          <div class="header-bottom-row">
            <button type="button" class="location-picker">
              <img class="icon-location" src="{{ asset('img/icon-4.svg') }}" alt="Lokasi">
              <span class="location-text">Malabar, Kota Bogor</span>
              <img class="icon-chevron" src="{{ asset('img/icon-6.svg') }}" alt="Pilih Lokasi">
            </button>

            <div class="page-status-pill">
              <img src="{{ asset('img/icon-8.svg') }}" alt="Ikon Halaman">
              <span>@yield('page_badge', 'Beranda')</span>
            </div>
          </div>
        </div>
      </header>

      <!-- Konten Utama Halaman -->
      <main class="app-main-content">
        @yield('content')
      </main>

    </div>
  </div>

  @stack('modals')
  @stack('scripts')
</body>
</html>