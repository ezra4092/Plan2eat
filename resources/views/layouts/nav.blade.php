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

    <!-- Daftar Menu Navigasi Pelanggan (Horizontal di Mobile, Vertikal di Desktop) -->
    <div class="nav-menu-list">
      <form action="{{ route('admin.dashboard') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'beranda' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-9.svg') }}" alt="Beranda">
          </div>
          <span class="nav-label">Dashboard</span>
        </button>
      </form>

      <!-- <form action="{{ route('admin.pengguna')}}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'langganan' || $activeMenu === 'meal_plan' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-7.svg') }}" alt="Langganan">
          </div>
          <span class="nav-label">Pengguna</span>
        </button>
      </form> -->

      <form action="{{ route('admin.umkm') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'ulasan' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-5.svg') }}" alt="Ulasan">
          </div>
          <span class="nav-label">Mitra Umkm</span>
        </button>
      </form>

      <form action="{{ route('admin.laporan') }}" method="GET" style="margin: 0;">
        <button type="submit" class="nav-item {{ $activeMenu === 'profil' ? 'active' : '' }}" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
          <div class="nav-icon-box">
            <img src="{{ asset('img/icon-5.svg') }}" alt="Profil">
          </div>
          <span class="nav-label">Laporan</span>
        </button>
      </form>
    </div>

    <hr class="sidebar-divider">

    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
      @csrf
      <button type="submit" class="nav-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: inherit;">
        <div class="nav-icon-box">
          <img src="{{ asset('img/icon-logout.svg') }}" alt="Logout">
        </div>
        <span class="nav-label">Logout</span>
      </button>
    </form>

    <!-- Info Card Bawah Sidebar (Hanya muncul di Desktop seperti SB Admin 2) -->
    <div class="sidebar-promo-card">
      <div class="sidebar-promo-badge">100% Circular</div>
      <p class="sidebar-promo-text">
        Katering sehat tanpa limbah kemasan untuk gaya hidup berkelanjutan.
      </p>
    </div>
  @endif
</nav>
