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
  <link rel="icon" type="image/png" sizes="32x32" href="..\images\favicon.ico">
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
          @php
            $headerUser = auth()->user();
            $headerRole = strtolower(trim((string) ($headerUser?->role ?? session('role', 'pelanggan'))));
            $headerHomeUrl = match ($headerRole) {
                'umkm' => Route::has('umkm.dashboard') ? route('umkm.dashboard') : url('/umkm/dashboard'),
                'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
                default => Route::has('pelanggan.beranda') ? route('pelanggan.beranda') : url('/pelanggan/beranda'),
            };
            $headerProfileUrl = match ($headerRole) {
                'umkm' => Route::has('umkm.profil') ? route('umkm.profil') : url('/umkm/profil'),
                'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
                default => Route::has('pelanggan.profil_kesehatan') ? route('pelanggan.profil_kesehatan') : url('/pelanggan/profil-kesehatan'),
            };
            $headerRawFoto = $headerUser?->foto;
            $headerAvatarUrl = $headerRawFoto
                ? (str_starts_with($headerRawFoto, 'http') ? $headerRawFoto : asset('storage/' . $headerRawFoto))
                : asset('images/Logo 2.png');
            $headerUserName = $headerUser?->nama_lengkap ?? 'Pengguna';
            $headerUserEmail = $headerUser?->email ?? '';
            $headerUmkm = ($headerRole === 'umkm' && $headerUser) ? $headerUser->umkm()->first() : null;
            $headerUmkmSuspended = ($headerRole === 'umkm' && $headerUser) ? $headerUser->isCurrentlySuspended() : false;
            $headerSuspendedUntil = $headerUmkmSuspended
                ? ($headerUser->getSuspendedUntilAt()?->locale('id')->translatedFormat('d M Y, H:i') . ' WIB')
                : null;
          @endphp

          <!-- Baris 1 Mobile / Kanan Desktop: Logo (Mobile) & Aksi User -->
          <div class="header-top-row">
            <a href="{{ $headerHomeUrl }}" class="brand-logo-group mobile-only-brand">
              <img class="brand-logo-icon" src="{{ asset('images/Logo 2.png') }}" alt="Plan2Eat Logo">
              <span class="brand-logo-text">Plan2Eat</span>
            </a>

            <div class="header-user-actions">
              <!-- <button type="button" class="btn-notification" aria-label="Notifikasi">
                <img src="{{ asset('img/icon-14.svg') }}" alt="Notifikasi">
              </button> -->

              <div class="header-account-wrap" id="headerAccountWrap">
                <button
                  type="button"
                  id="headerAccountBtn"
                  class="profile-avatar-link header-account-trigger"
                  aria-label="Menu Akun Pengguna"
                  aria-expanded="false"
                  aria-haspopup="true"
                >
                  <span class="desktop-user-name">{{ $headerUserName }}</span>
                  <div class="profile-avatar" style="background-image: url('{{ $headerAvatarUrl }}');"></div>
                </button>

                <div class="header-account-dropdown is-hidden" id="headerAccountDropdown" role="menu">
                  <div class="header-dropdown-head">
                    <div class="header-dropdown-avatar" style="background-image: url('{{ $headerAvatarUrl }}');"></div>
                    <div class="header-dropdown-meta">
                      <strong>{{ $headerUserName }}</strong>
                      <span>{{ $headerUserEmail }}</span>
                    </div>
                  </div>

                  <div class="header-dropdown-divider"></div>

                  <div class="header-dropdown-list">
                    <a href="{{ $headerProfileUrl }}" class="header-dropdown-item" role="menuitem">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                      </svg>
                      <span>Profil</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                      @csrf
                      <button type="submit" class="header-dropdown-item is-logout" role="menuitem">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                          <polyline points="16 17 21 12 16 7"/>
                          <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Logout</span>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Baris 2 Mobile / Kiri Desktop: Lokasi & Indikator Halaman Aktif -->
          <div class="header-bottom-row">
            <!-- <button type="button" class="location-picker">
              <img class="icon-location" src="{{ asset('img/icon-4.svg') }}" alt="Lokasi">
              <span class="location-text">Malabar, Kota Bogor</span>
              <img class="icon-chevron" src="{{ asset('img/icon-6.svg') }}" alt="Pilih Lokasi">
            </button> -->

            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
              <div class="page-status-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                  <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
                <span>@yield('page_badge', 'Beranda')</span>
              </div>

              @if ($headerUmkmSuspended)
                <span style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 999px; background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; font-size: 12px; font-weight: 700;">
                  <span style="width: 7px; height: 7px; border-radius: 50%; background: #DC2626;"></span>
                  <span>Akun Nonaktif / Ditangguhkan</span>
                </span>
              @endif
            </div>
          </div>
        </div>
      </header>

      <!-- Konten Utama Halaman -->
      <main class="app-main-content">
        @if ($headerUmkmSuspended)
          <div role="alert" style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #DC2626; border-radius: 16px; padding: 14px 18px; margin-bottom: 18px; color: #7F1D1D;">
            <div style="font-weight: 800; font-size: 14.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
              <span>Peringatan: Akun UMKM Anda Sedang Ditangguhkan (Nonaktif 3 Hari)</span>
            </div>
            <div style="font-size: 13px; line-height: 1.55;">
              Selama masa penangguhan hingga <strong>{{ $headerSuspendedUntil ?? 'masa penangguhan berakhir' }}</strong>, status toko Anda otomatis ditutup, menu Anda tidak ditampilkan ke pelanggan, dan Anda tidak dapat membuka toko, menambah/mengubah menu, membuat flash sale, maupun menerima pesanan baru.
              @if (! empty($headerUmkm?->catatan))
                <div style="margin-top: 6px; padding: 8px 10px; border-radius: 10px; background: #FEE2E2; color: #7F1D1D;">
                  <strong>Alasan Penangguhan:</strong> {{ $headerUmkm->catatan }}
                </div>
              @endif
            </div>
          </div>
        @endif

        @yield('content')
      </main>

    </div>
  </div>

  @include('partials.popup_notification')
  @stack('modals')
  @stack('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const accountWrap = document.getElementById('headerAccountWrap');
      const accountBtn = document.getElementById('headerAccountBtn');
      const accountDropdown = document.getElementById('headerAccountDropdown');

      if (!accountWrap || !accountBtn || !accountDropdown) return;

      function setAccountDropdown(open) {
        accountDropdown.classList.toggle('is-hidden', !open);
        accountBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      accountBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isClosed = accountDropdown.classList.contains('is-hidden');
        setAccountDropdown(isClosed);
      });

      document.addEventListener('click', function (e) {
        if (!accountWrap.contains(e.target)) {
          setAccountDropdown(false);
        }
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          setAccountDropdown(false);
        }
      });
    });
  </script>
</body>
</html>