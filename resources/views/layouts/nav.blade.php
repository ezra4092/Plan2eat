@php
  $yieldNavActive = trim((string) $__env->yieldContent('nav_active'));
  $resolvedActiveMenu = $activeMenu ?? ($yieldNavActive !== '' ? $yieldNavActive : '');

  // Prioritaskan role asli akun yang sedang login agar tidak tertukar antar role
  $rawRole = auth()->check()
      ? auth()->user()->role
      : ($role ?? session('role', 'pelanggan'));
  $userRole = strtolower(trim((string) $rawRole));
  if (! in_array($userRole, ['pelanggan', 'umkm', 'admin'], true)) {
      $userRole = 'pelanggan';
  }

  $brandHomeUrl = match ($userRole) {
      'umkm' => Route::has('umkm.dashboard') ? route('umkm.dashboard') : url('/umkm/dashboard'),
      'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
      default => Route::has('pelanggan.beranda') ? route('pelanggan.beranda') : url('/pelanggan/beranda'),
  };

  if ($resolvedActiveMenu === '') {
      $resolvedActiveMenu = match (true) {
          request()->routeIs('pelanggan.langganan', 'pelanggan.meal_plan*') => 'langganan',
          request()->routeIs('pelanggan.ulasan*') => 'ulasan',
          request()->routeIs('pelanggan.profil_kesehatan*') => 'profil',
          request()->routeIs('umkm.menu*', 'umkm.flashsale*') => 'menu',
          request()->routeIs('umkm.pesanan*') => 'pesanan',
          request()->routeIs('umkm.laporan_keuangan*') => 'keuangan',
          request()->routeIs('umkm.profil*') => 'profil',
          request()->routeIs('admin.umkm*') => 'umkm',
          request()->routeIs('admin.data_master*') => 'data_master',
          request()->routeIs('admin.pengguna*') => 'pengguna',
          request()->routeIs('admin.ulasan*') => 'ulasan',
          request()->routeIs('admin.laporan', 'admin.transaksi') => 'laporan',
          default => 'beranda',
      };
  }
@endphp

<nav class="app-nav" id="appSidebar" aria-label="Navigasi Utama">
  <!-- Brand Header (Hanya muncul di Desktop Left Sidebar) -->
  <div class="sidebar-brand">
    <a href="{{ $brandHomeUrl }}" class="sidebar-brand-link">
      <img class="sidebar-brand-icon" src="{{ asset('images/Logo 2.png') }}" alt="Plan2Eat Logo">
      <span class="sidebar-brand-text">PLAN2EAT</span>
    </a>
  </div>

  <hr class="sidebar-divider">

  @if ($userRole === 'pelanggan')
    <div class="sidebar-section-label">MENU UTAMA</div>

    <div class="nav-menu-list">
      <a href="{{ route('pelanggan.beranda') }}"
         class="nav-item {{ request()->routeIs('pelanggan.beranda', 'pelanggan.mitra', 'pelanggan.menu*', 'pelanggan.flashsale', 'pelanggan.keranjang*', 'pelanggan.checkout', 'pelanggan.pesanan-detail') || $resolvedActiveMenu === 'beranda' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 10.5 12 3l9 7.5"/>
            <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>
          </svg>
        </div>
        <span class="nav-label">Beranda</span>
      </a>

      <a href="{{ route('pelanggan.langganan') }}"
         class="nav-item {{ request()->routeIs('pelanggan.langganan', 'pelanggan.meal_plan*') || in_array($resolvedActiveMenu, ['langganan', 'meal_plan'], true) ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <span class="nav-label">Langganan</span>
      </a>

      <a href="{{ route('pelanggan.ulasan') }}"
         class="nav-item {{ request()->routeIs('pelanggan.ulasan*') || $resolvedActiveMenu === 'ulasan' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>
        <span class="nav-label">Ulasan</span>
      </a>

      <a href="{{ route('pelanggan.profil_kesehatan') }}"
         class="nav-item {{ request()->routeIs('pelanggan.profil_kesehatan*') || $resolvedActiveMenu === 'profil' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <span class="nav-label">Profil</span>
      </a>
    </div>

    <hr class="sidebar-divider">

    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">100% Circular</div>
      <p class="sidebar-promo-text">
        Katering sehat tanpa limbah kemasan untuk gaya hidup berkelanjutan.
      </p>
    </div>

  @elseif ($userRole === 'umkm')
    <div class="sidebar-section-label">MENU DAPUR MITRA</div>

    <div class="nav-menu-list">
      <a href="{{ route('umkm.dashboard') }}"
         class="nav-item {{ request()->routeIs('umkm.dashboard') || $resolvedActiveMenu === 'beranda' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 10.5 12 3l9 7.5"/>
            <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>
          </svg>
        </div>
        <span class="nav-label">Beranda</span>
      </a>

      <a href="{{ route('umkm.menu') }}"
         class="nav-item {{ request()->routeIs('umkm.menu*', 'umkm.flashsale*') || in_array($resolvedActiveMenu, ['manajemen', 'menu'], true) ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
            <path d="M7 2v20"/>
            <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
          </svg>
        </div>
        <span class="nav-label">Menu</span>
      </a>

      <a href="{{ route('umkm.pesanan') }}"
         class="nav-item {{ request()->routeIs('umkm.pesanan*') || $resolvedActiveMenu === 'pesanan' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <path d="M9 12h6"/>
            <path d="M9 16h4"/>
          </svg>
        </div>
        <span class="nav-label">Pesanan</span>
      </a>

      <a href="{{ route('umkm.laporan_keuangan') }}"
         class="nav-item {{ request()->routeIs('umkm.laporan_keuangan*') || in_array($resolvedActiveMenu, ['saldo', 'laporan', 'keuangan'], true) ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
          </svg>
        </div>
        <span class="nav-label">Keuangan</span>
      </a>

      <a href="{{ route('umkm.profil') }}"
         class="nav-item {{ request()->routeIs('umkm.profil*') || $resolvedActiveMenu === 'profil' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <span class="nav-label">Profil</span>
      </a>
    </div>

    <hr class="sidebar-divider">

  @elseif ($userRole === 'admin')
    <div class="sidebar-section-label">MENU ADMIN</div>

    <div class="nav-menu-list">
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') || in_array($resolvedActiveMenu, ['beranda', 'dashboard'], true) ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="9" rx="1"/>
            <rect x="14" y="3" width="7" height="5" rx="1"/>
            <rect x="14" y="12" width="7" height="9" rx="1"/>
            <rect x="3" y="16" width="7" height="5" rx="1"/>
          </svg>
        </div>
        <span class="nav-label">Dashboard</span>
      </a>

      <a href="{{ route('admin.data_master') }}"
         class="nav-item {{ request()->routeIs('admin.data_master*') || $resolvedActiveMenu === 'data_master' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <ellipse cx="12" cy="5" rx="9" ry="3"/>
            <path d="M3 5v14a9 3 0 0 0 18 0V5"/>
            <path d="M3 12a9 3 0 0 0 18 0"/>
          </svg>
        </div>
        <span class="nav-label">Data Master</span>
      </a>

      <a href="{{ route('admin.umkm') }}"
         class="nav-item {{ request()->routeIs('admin.umkm*') || $resolvedActiveMenu === 'umkm' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l2-5h14l2 5"/>
            <path d="M5 12v8h14v-8"/>
            <path d="M10 20v-5h4v5"/>
          </svg>
        </div>
        <span class="nav-label">Verifikasi UMKM</span>
      </a>

      <a href="{{ route('admin.laporan') }}"
         class="nav-item {{ request()->routeIs('admin.laporan', 'admin.transaksi') || $resolvedActiveMenu === 'laporan' ? 'active' : '' }}">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
        </div>
        <span class="nav-label">Laporan</span>
      </a>
    </div>

    <hr class="sidebar-divider">

    <!-- <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
      @csrf
      <button type="submit" class="nav-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
        <div class="nav-icon-box" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </div>
        <span class="nav-label">Logout</span>
      </button>
    </form> -->
  @endif
</nav>
