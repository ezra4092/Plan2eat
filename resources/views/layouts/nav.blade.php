@php
  $activeMenu = $activeMenu ?? 'beranda';
  // Ambil role dari variabel @include, kolom 'role' di tabel users (Auth), atau Session
  $userRole = strtolower($role ?? (auth()->check() ? auth()->user()->role : session('role', 'pelanggan')));
@endphp

<nav class="app-nav" id="appSidebar" aria-label="Navigasi Utama">
  <!-- Brand Header (Hanya muncul di Desktop Left Sidebar seperti SB Admin 2) -->
  <div class="sidebar-brand">
    <a href="{{ $userRole === 'umkm' ? (Route::has('umkm.dashboard') ? route('umkm.dashboard') : url('/umkm/dashboard')) : (Route::has('pelanggan.beranda') ? route('pelanggan.beranda') : url('/pelanggan/beranda')) }}" class="sidebar-brand-link">
      <img class="sidebar-brand-icon" src="{{ asset('images/Logo 2.png') }}" alt="Plan2Eat Logo">
      <span class="sidebar-brand-text">PLAN2EAT</span>
    </a>
  </div>

  <hr class="sidebar-divider">

  @if ($userRole === 'pelanggan')
    <!-- Label Kategori Menu (Desktop) -->
    <div class="sidebar-section-label">MENU UTAMA</div>

    <!-- Daftar Menu Navigasi Pelanggan (Horizontal di Mobile, Vertikal di Desktop) -->
    <div class="nav-menu-list">
      <form action="{{ route('pelanggan.beranda') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'beranda' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-9.svg') }}" alt="Beranda">
          </div>
          <span class="nav-label">Beranda</span>
        </button>
      </form>

      <form action="{{ route('pelanggan.langganan')}}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'langganan' || $activeMenu === 'meal_plan' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-7.svg') }}" alt="Langganan">
          </div>
          <span class="nav-label">Langganan</span>
        </button>
      </form>

      <form action="{{ route('pelanggan.ulasan') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'ulasan' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-5.svg') }}" alt="Ulasan">
          </div>
          <span class="nav-label">Ulasan</span>
        </button>
      </form>

      <form action="{{ route('pelanggan.profil_kesehatan') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'profil' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-5.svg') }}" alt="Profil">
          </div>
          <span class="nav-label">Profil</span>
        </button>
      </form>
    </div>

    <hr class="sidebar-divider">

    <!-- <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
      @csrf
      <button type="submit" class="nav-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-logout.svg') }}" alt="Logout">
        </div>
        <span class="nav-label">Logout</span>
      </button>
    </form> -->

    <!-- Info Card Bawah Sidebar (Hanya muncul di Desktop seperti SB Admin 2) -->
    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">100% Circular</div>
      <p class="sidebar-promo-text">
        Katering sehat tanpa limbah kemasan untuk gaya hidup berkelanjutan.
      </p>
    </div>

  @elseif ($userRole === 'umkm')
    <!-- Label Kategori Menu (Desktop) -->
    <div class="sidebar-section-label">MENU DAPUR MITRA</div>

    <!-- Daftar Menu Navigasi UMKM (Horizontal di Mobile, Vertikal di Desktop) -->
    <div class="nav-menu-list">
      <form action="{{ route('umkm.dashboard') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'beranda' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-3.svg') }}" alt="Beranda">
          </div>
          <span class="nav-label">Beranda</span>
        </button>
      </form>

      <form action="{{ route('umkm.menu')}}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'manajemen' || $activeMenu === 'menu' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-4.svg') }}" alt="Manajemen Menu">
          </div>
          <span class="nav-label">Menu</span>
        </button>
      </form>

      <form action="{{ route('umkm.pesanan')}}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'manajemen' || $activeMenu === 'menu' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-4.svg') }}" alt="Manajemen Menu">
          </div>
          <span class="nav-label">Pesanan</span>
        </button>
      </form>

      <form action="{{ route('umkm.laporan_keuangan') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'saldo' || $activeMenu === 'laporan' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-park-outline-funds.svg') }}" alt="Keuangan">
          </div>
          <span class="nav-label">Keuangan</span>
        </button>
      </form>

      <form action="{{ route('umkm.profil') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'profil' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-2.svg') }}" alt="Profil">
          </div>
          <span class="nav-label">Profil</span>
        </button>
      </form>
    </div>

    <hr class="sidebar-divider">

    <!-- <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
      @csrf
      <button type="submit" class="nav-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-logout.svg') }}" alt="Logout">
        </div>
        <span class="nav-label">Logout</span>
      </button>
    </form> -->

    <!-- Info Card Bawah Sidebar (Hanya muncul di Desktop) -->
    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">Zero-Waste Partner</div>
      <p class="sidebar-promo-text">
        Kelola kuota katering harian dan kurangi sisa makanan lewat fitur Night Flash Sale.
      </p>
    </div>
    
  @elseif ($userRole === 'admin')
    <!-- Label Kategori Menu (Desktop) -->
    <div class="sidebar-section-label">MENU UTAMA</div>

    <!-- Daftar Menu Utama Admin -->
    <div class="nav-menu-list admin-nav-list">
      <form action="{{ route('admin.dashboard') }}" method="GET" class="nav-form-item">
        <button type="submit" class="nav-item admin-nav-item {{ $activeMenu === 'dashboard' || $activeMenu === 'beranda' ? 'active' : '' }}">
          <div class="nav-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
          </div>
          <span class="nav-label">Dashboard</span>
        </button>
      </form>

      <form action="{{ route('admin.pengguna') }}" method="GET" class="nav-form-item">
        <button type="submit" class="nav-item admin-nav-item {{ $activeMenu === 'pengguna' ? 'active' : '' }}">
          <div class="nav-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
          </div>
          <span class="nav-label">Pengguna</span>
        </button>
      </form>

      <form action="{{ route('admin.umkm') }}" method="GET" class="nav-form-item">
        <button type="submit" class="nav-item admin-nav-item {{ $activeMenu === 'mitra' || $activeMenu === 'mitra_umkm' ? 'active' : '' }}">
          <div class="nav-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l1.5-5h15L21 9"></path>
              <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"></path>
              <path d="M5 12v8h14v-8"></path>
              <path d="M10 20v-5h4v5"></path>
            </svg>
          </div>
          <span class="nav-label">Mitra UMKM</span>
        </button>
      </form>

      <form action="{{ route('admin.laporan') }}" method="GET" class="nav-form-item">
        <button type="submit" class="nav-item admin-nav-item {{ $activeMenu === 'laporan' ? 'active' : '' }}">
          <div class="nav-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="20" x2="18" y2="10"></line>
              <line x1="12" y1="20" x2="12" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
          </div>
          <span class="nav-label">Laporan</span>
        </button>
      </form>
    </div>

    <!-- Bagian Bawah Sidebar Admin (Desktop) -->
    <div class="admin-sidebar-footer">
      <hr class="sidebar-divider admin-sidebar-divider">

      <div class="admin-footer-menu">
        <form action="{{ route('admin.pengaturan') }}" method="GET" class="nav-form-item">
          <button type="submit" class="nav-item admin-nav-item {{ $activeMenu === 'pengaturan' ? 'active' : '' }}">
            <div class="nav-icon-box">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
              </svg>
            </div>
            <span class="nav-label">Pengaturan</span>
          </button>
        </form>

        <form action="{{ route('logout') }}" method="POST" class="nav-form-item">
          @csrf
          <button type="submit" class="nav-item admin-nav-item admin-logout-item">
            <div class="nav-icon-box">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
            </div>
            <span class="nav-label">Keluar</span>
          </button>
        </form>
      </div>

      <!-- Profil Card Admin -->
      <div class="admin-profile-card">
        <div class="admin-profile-avatar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        </div>
        <div class="admin-profile-info">
          <span class="admin-profile-name">{{ Auth::user()->name ?? 'Admin Plan2Eat' }}</span>
          <span class="admin-profile-email">{{ Auth::user()->email ?? 'admin@plan2eat.id' }}</span>
        </div>
      </div>
    </div>
  @endif
</nav>
