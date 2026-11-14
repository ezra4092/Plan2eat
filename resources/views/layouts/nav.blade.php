@php
  $activeMenu = $activeMenu ?? 'beranda';
  // Ambil role dari variabel @include, kolom 'role' di tabel users (Auth), atau Session
  $userRole = strtolower($role ?? (auth()->check() ? auth()->user()->role : session('role', 'pelanggan')));
@endphp

@if ($userRole === 'pelanggan')
  <nav class="app-nav" id="appSidebar" aria-label="Navigasi Utama">
    <!-- Brand Header (Hanya muncul di Desktop Left Sidebar seperti SB Admin 2) -->
    <div class="sidebar-brand">
      <a href="{{ url('/beranda') }}" class="sidebar-brand-link">
        <img class="sidebar-brand-icon" src="{{ asset('img/logo.svg') }}" alt="Plan2Eat Logo">
        <span class="sidebar-brand-text">PLAN2EAT</span>
      </a>
    </div>

    <hr class="sidebar-divider">

    <!-- Label Kategori Menu (Desktop) -->
    <div class="sidebar-section-label">MENU UTAMA</div>

    <!-- Daftar Menu Navigasi Pelanggan (Horizontal di Mobile, Vertikal di Desktop) -->
    <div class="nav-menu-list">
      <a href="{{ url('/beranda') }}" class="nav-item {{ $activeMenu === 'beranda' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-9.svg') }}" alt="Beranda">
        </div>
        <span class="nav-label">Beranda</span>
      </a>

      <a href="{{ url('/langganan') }}" class="nav-item {{ $activeMenu === 'langganan' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-7.svg') }}" alt="Langganan">
        </div>
        <span class="nav-label">Langganan</span>
      </a>

      <a href="{{ url('/profil') }}" class="nav-item {{ $activeMenu === 'profil' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-5.svg') }}" alt="Profil">
        </div>
        <span class="nav-label">Profil</span>
      </a>
    </div>

    <hr class="sidebar-divider">

    <!-- Info Card Bawah Sidebar (Hanya muncul di Desktop seperti SB Admin 2) -->
    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">100% Circular</div>
      <p class="sidebar-promo-text">
        Katering sehat tanpa limbah kemasan untuk gaya hidup berkelanjutan.
      </p>
    </div>
  </nav>

@elseif ($userRole === 'umkm')
  <nav class="app-nav" id="appSidebar" aria-label="Navigasi UMKM">
    <!-- Brand Header (Muncul di Desktop Left Sidebar seperti SB Admin 2) -->
    <div class="sidebar-brand">
      <a href="{{ url('/umkm/beranda') }}" class="sidebar-brand-link">
        <div class="brand-logo-badge">
          <img class="sidebar-brand-icon" src="{{ asset('img/logo.svg') }}" alt="Plan2Eat Logo">
        </div>
        <div class="sidebar-brand-info">
          <span class="sidebar-brand-text">PLAN2EAT</span>
          <span class="sidebar-brand-sub">Dapur Mitra</span>
        </div>
      </a>
    </div>

    <hr class="sidebar-divider">

    <!-- Label Kategori Menu (Desktop) -->
    <div class="sidebar-section-label">MENU DAPUR MITRA</div>

    <!-- Daftar Menu Navigasi UMKM (Horizontal di Mobile, Vertikal di Desktop) -->
    <div class="nav-menu-list">
      <a href="{{ route('umkm.dashboard') }}" class="nav-item {{ $activeMenu === 'beranda' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-3.svg') }}" alt="Beranda">
        </div>
        <span class="nav-label">Beranda</span>
      </a>

      <a href="{{ url('/umkm/manajemen') }}" class="nav-item {{ $activeMenu === 'manajemen' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-4.svg') }}" alt="Manajemen">
        </div>
        <span class="nav-label">Manajemen</span>
      </a>

      <a href="{{ url('/umkm/saldo') }}" class="nav-item {{ $activeMenu === 'saldo' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-park-outline-funds.svg') }}" alt="Saldo">
        </div>
        <span class="nav-label">Saldo</span>
      </a>

      <a href="{{ route('umkm.profil.index') }}" class="nav-item {{ $activeMenu === 'profil' ? 'active' : '' }}">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-2.svg') }}" alt="Profil">
        </div>
        <span class="nav-label">Profil</span>
      </a>
    </div>

    <hr class="sidebar-divider">

    <!-- Info Card Bawah Sidebar (Hanya muncul di Desktop) -->
    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">Zero-Waste Partner</div>
      <p class="sidebar-promo-text">
        Kelola kuota katering harian dan kurangi sisa makanan lewat fitur Night Flash Sale.
      </p>
    </div>
  </nav>
@endif