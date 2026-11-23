<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Plan2Eat — Plan It, Order It, Irit | Katering Sehat &amp; Zero-Waste</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('template/css/globals.css') }}">
  <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="..\images\favicon.ico">
</head>
<body class="lp-body">

  @php
    $defaultMenus = [
        [
            'nama' => 'Ayam Bakar sambal Riau + Es teh',
            'sub' => 'Paket Makan Ayam Praktis · Dapur Mitra',
            'harga' => 18000,
            'foto' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=700&q=80',
        ],
        [
            'nama' => 'Paket Hemat Anak Kos 3 Hari (6x Makan)',
            'sub' => 'Paket Makan 3 Hari · Terjadwal H+1',
            'harga' => 75000,
            'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80',
        ],
        [
            'nama' => 'Soto Ayam Kuah Bening',
            'sub' => 'Menu Sehat Harian · Dapur Mitra',
            'harga' => 15000,
            'foto' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=700&q=80',
        ],
    ];
  @endphp

  {{-- ================= TOP NAVBAR ================= --}}
  <header class="lp-header" id="lpHeader">
    <div class="lp-container lp-nav-inner">
      <a href="{{ route('landing_page') }}" class="lp-brand">
        <img src="{{ asset('images/Logo 2.png') }}" alt="Plan2Eat Logo" class="lp-brand-icon">
        <span class="lp-brand-name">Plan<span>2</span>Eat</span>
      </a>

      <ul class="lp-nav-links">
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#tentang">Tentang Kami</a></li>
        <li><a href="#menu">Menu Pilihan</a></li>
        <li><a href="#flashsale">Flash Sale</a></li>
        <li><a href="#fitur">Keunggulan</a></li>
      </ul>

      <div class="lp-nav-actions">
        <a href="{{ route('login_page') }}" class="lp-btn-nav-login">Masuk</a>
        <a href="{{ route('register.pilihan') }}" class="lp-btn-nav-register">Daftar Akun</a>
      </div>

      <button type="button" class="lp-mobile-toggle" id="lpMobileToggle" aria-label="Buka Menu" aria-expanded="false">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <line x1="3" y1="6" x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
    </div>

    <div class="lp-mobile-drawer" id="lpMobileDrawer">
      <a href="#beranda" class="lp-drawer-link">Beranda</a>
      <a href="#tentang" class="lp-drawer-link">Tentang Kami</a>
      <a href="#menu" class="lp-drawer-link">Menu Pilihan</a>
      <a href="#flashsale" class="lp-drawer-link">Flash Sale</a>
      <a href="#fitur" class="lp-drawer-link">Keunggulan</a>

      <div class="lp-mobile-drawer-btns">
        <a href="{{ route('login_page') }}" class="lp-btn-nav-login">Masuk</a>
        <a href="{{ route('register.pilihan') }}" class="lp-btn-nav-register">Daftar Akun</a>
      </div>
    </div>
  </header>

  {{-- ================= 1. BERANDA / HERO SECTION ================= --}}
  <section class="lp-hero" id="beranda">
    <div class="lp-container lp-hero-inner">
      <p class="lp-hero-eyebrow lp-hero-anim-1">Plan It, Order It, Irit</p>
      <h1 class="lp-hero-title lp-hero-anim-2">Katering Sehat &amp; Zero-Waste Harian</h1>
      <p class="lp-hero-sub lp-hero-anim-3">
        Pesan menu katering sehat harian, atur Meal Plan mingguan terjadwal, hingga berburu Night Flash Sale hemat langsung dari dapur mitra UMKM terverifikasi.
      </p>

      <div class="lp-hero-btns lp-hero-anim-4">
        <a href="{{ route('register.pilihan') }}" class="lp-pill-btn lp-pill-orange">Belum Punya Akun? Daftar</a>
        <a href="{{ route('login_page') }}" class="lp-pill-btn lp-pill-outline-white">Sudah Punya Akun? Masuk</a>
      </div>
    </div>
  </section>

  {{-- ================= 3 SERVICE HIGHLIGHTS STRIP ================= --}}
  <section class="lp-strip-section">
    <div class="lp-container lp-strip-grid">
      <div class="lp-strip-item lp-reveal">
        <div class="lp-strip-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        </div>
        <div class="lp-strip-text">
          <h3>Pengantaran Terjadwal H+1</h3>
          <p>Pesan lebih awal untuk jam sarapan, siang, maupun malam</p>
        </div>
      </div>

      <div class="lp-strip-item lp-reveal">
        <div class="lp-strip-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="lp-strip-text">
          <h3>Mitra UMKM Terverifikasi</h3>
          <p>{{ ($umkmCount ?? 0) > 0 ? $umkmCount . ' dapur mitra aktif terkurasi admin' : 'Dapur katering lokal higienis & terkurasi admin' }}</p>
        </div>
      </div>

      <div class="lp-strip-item lp-reveal">
        <div class="lp-strip-icon" aria-hidden="true">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        </div>
        <div class="lp-strip-text">
          <h3>Refund Saldo Otomatis</h3>
          <p>Pembatalan Meal Plan aman dalam 6 jam setelah bayar</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ================= 2. TENTANG KAMI (2-COL) ================= --}}
  <section class="lp-section" id="tentang">
    <div class="lp-container lp-about-grid">
      <div class="lp-about-media lp-reveal">
        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80" alt="Dapur Mitra Plan2Eat">
        <span class="lp-about-badge">100% Zero-Waste Kitchen</span>
      </div>

      <div class="lp-reveal">
        <span class="lp-about-eyebrow">Tentang Layanan Katering Plan2Eat</span>
        <h2 class="lp-about-title">Kami adalah <span class="lp-accent-orange">Plan2Eat</span></h2>
        <p class="lp-about-text">
          Plan2Eat hadir sebagai platform katering cerdas yang menghubungkan pelanggan dengan mitra dapur UMKM lokal terverifikasi. Setiap menu disajikan dengan takaran porsi seimbang, informasi kalori, dan penyesuaian pantangan bahan makanan.
        </p>
        <p class="lp-about-text" style="margin-bottom: 24px;">
          Melalui sistem perencanaan makan terjadwal H+1, pengantaran minim sampah kemasan, serta program Flash Sale malam hari, kami mendorong pola konsumsi yang sehat, hemat, dan berkelanjutan.
        </p>

        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <a href="{{ route('register.pilihan') }}" class="lp-pill-btn lp-pill-orange">Daftar Sekarang</a>
          <a href="{{ route('login_page') }}" class="lp-pill-btn" style="background: var(--forest-green); color: var(--white);">Masuk Akun</a>
        </div>
      </div>
    </div>
  </section>

  {{-- ================= 3. MENU PILIHAN (3 MENU DARI DAPUR MITRA) ================= --}}
  <section class="lp-section-cream" id="menu">
    <div class="lp-container">
      <div class="lp-head-center lp-reveal">
        <h2 class="lp-head-title"><span class="lp-accent-orange">Menu</span> Pilihan Kami</h2>
        <p class="lp-head-desc">
          Hidangan bergizi pilihan dari mitra UMKM Plan2Eat, siap dipesan langsung maupun dijadwalkan dalam Meal Plan.
        </p>
      </div>

      <div class="lp-products-grid">
        @if (($featuredMenus ?? collect())->count() >= 3)
          @foreach ($featuredMenus as $menu)
            @php
              $foto = \App\Models\Menu::resolveFotoUrl(
                  $menu->foto_menu ?? null,
                  'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80'
              );
              $katLabel = $menu->jenisMenu?->nama ?? 'Menu Sehat';
            @endphp
            <article class="lp-product-card lp-reveal">
              <div class="lp-product-img-wrap">
                <img src="{{ $foto }}" alt="{{ $menu->nama_makanan }}" loading="lazy" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80';">
              </div>
              <h3 class="lp-product-title">{{ $menu->nama_makanan }}</h3>
              <span class="lp-product-unit">{{ $katLabel }} &middot; {{ $menu->umkm?->nama_vendor ?? 'Mitra Plan2Eat' }}</span>
              <p class="lp-product-price">Rp {{ number_format((int) $menu->harga_normal, 0, ',', '.') }}</p>
              <a href="{{ route('register.pilihan') }}" class="lp-product-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Daftar &amp; Pesan</span>
              </a>
            </article>
          @endforeach
        @else
          @foreach ($defaultMenus as $item)
            <article class="lp-product-card lp-reveal">
              <div class="lp-product-img-wrap">
                <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}" loading="lazy">
              </div>
              <h3 class="lp-product-title">{{ $item['nama'] }}</h3>
              <span class="lp-product-unit">{{ $item['sub'] }}</span>
              <p class="lp-product-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
              <a href="{{ route('register.pilihan') }}" class="lp-product-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Daftar &amp; Pesan</span>
              </a>
            </article>
          @endforeach
        @endif
      </div>
    </div>
  </section>

  {{-- ================= 4. FLASH SALE MALAM INI ================= --}}
  <section class="lp-section" id="flashsale">
    <div class="lp-container lp-deal-grid">
      <div class="lp-deal-media lp-reveal">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80" alt="Night Flash Sale Plan2Eat">
        <div class="lp-deal-circle">
          <strong>20-30%</strong>
          <span>Hemat/Porsi</span>
        </div>
      </div>

      <div class="lp-deal-content lp-reveal">
        <h2><span class="lp-accent-orange">Flash Sale</span> Malam Ini</h2>
        <div class="lp-deal-sub">Setiap Pukul 20:00 &ndash; 22:00 WIB</div>
        <p class="lp-deal-desc">
          Jangan lewatkan promo harian menu satuan segar dari dapur mitra UMKM dengan potongan harga 20% hingga 30%. Membantu kamu makan enak lebih hemat sekaligus mengurangi potensi sisa makanan (zero food-waste).
        </p>

        <div class="lp-countdown-row" id="lpCountdown">
          <div class="lp-countdown-box"><strong id="cdHours">02</strong><span>Jam</span></div>
          <div class="lp-countdown-box"><strong id="cdMins">00</strong><span>Menit</span></div>
          <div class="lp-countdown-box"><strong id="cdSecs">00</strong><span>Detik</span></div>
          <div class="lp-countdown-box"><strong>5</strong><span>Menu Promo</span></div>
        </div>

        <a href="{{ route('login_page') }}" class="lp-pill-btn lp-pill-orange">
          <span>Masuk &amp; Cek Flash Sale</span>
        </a>
      </div>
    </div>
  </section>

  {{-- ================= TESTIMONIAL CENTERED ================= --}}
  <section class="lp-section-cream">
    <div class="lp-container">
      <div class="lp-testimonial-box lp-reveal">
        <div class="lp-testi-avatar" aria-hidden="true">NF</div>
        <h3 class="lp-testi-name">Nandana Faira</h3>
        <span class="lp-testi-role">Pelanggan Aktif Plan2Eat</span>
        <p class="lp-testi-quote">
          &ldquo;Sejak pakai fitur Meal Plan di Plan2Eat, jadwal makan siang dan malam jadi teratur tanpa harus bingung milih tiap hari. Malamnya juga sering dapet menu sehat murah dari Night Flash Sale.&rdquo;
        </p>
        <div class="lp-testi-mark">&rdquo;</div>
      </div>
    </div>
  </section>

  {{-- ================= PROMO JOIN BANNER STRIP ================= --}}
  <section class="lp-sale-banner">
    <div class="lp-container">
      <div class="lp-sale-inner lp-reveal">
        <h2 class="lp-sale-title">
          Mulai Katering Sehat &amp; Hemat <span class="lp-accent-orange">Hari Ini!</span>
        </h2>
        <div class="lp-sale-highlight">
          <span>Hemat hingga</span>
          <span class="lp-sale-pct">30%</span>
          <span>tiap malam</span>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <a href="{{ route('login_page') }}" class="lp-pill-btn lp-pill-orange">Masuk Sekarang</a>
          <a href="{{ route('register.pilihan') }}" class="lp-pill-btn" style="background: var(--white); color: var(--forest-green); border: 2px solid var(--forest-green);">Daftar Gratis</a>
        </div>
      </div>
    </div>
  </section>

  {{-- ================= 5. KEUNGGULAN PLATFORM (3 CARDS) ================= --}}
  <section class="lp-section" id="fitur">
    <div class="lp-container">
      <div class="lp-head-center lp-reveal">
        <h2 class="lp-head-title"><span class="lp-accent-orange">Fitur</span> Unggulan Platform</h2>
        <p class="lp-head-desc">
          Dirancang untuk memudahkan pelanggan merencanakan makan sehat sekaligus membantu mitra UMKM mengelola dapur lebih efisien.
        </p>
      </div>

      <div class="lp-news-grid">
        <article class="lp-news-card lp-reveal">
          <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=700&q=80" alt="Meal Plan H+1" class="lp-news-img" loading="lazy">
          <div class="lp-news-body">
            <h3>Smart Meal Planning Terjadwal</h3>
            <span class="lp-news-meta">Pelanggan &middot; Jadwal H+1</span>
            <p>Atur sarapan, makan siang, dan makan malam untuk esok hari atau seminggu penuh dalam satu rencana yang rapi.</p>
            <a href="{{ route('register.pilihan') }}" class="lp-news-link">Mulai Buat Plan &rarr;</a>
          </div>
        </article>

        <article class="lp-news-card lp-reveal">
          <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=700&q=80" alt="Profil Kesehatan" class="lp-news-img" loading="lazy">
          <div class="lp-news-body">
            <h3>Profil Kesehatan &amp; Kontrol Kalori</h3>
            <span class="lp-news-meta">Nutrisi &middot; Preferensi Alergi</span>
            <p>Catat preferensi diet, alergi bahan, dan target anggaran harian agar pilihan katering selalu sesuai kebutuhan tubuhmu.</p>
            <a href="{{ route('register.pilihan') }}" class="lp-news-link">Atur Profil Sehat &rarr;</a>
          </div>
        </article>

        <article class="lp-news-card lp-reveal">
          <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=700&q=80" alt="Kemitraan UMKM" class="lp-news-img" loading="lazy">
          <div class="lp-news-body">
            <h3>Manajemen Dapur Mitra UMKM</h3>
            <span class="lp-news-meta">UMKM &middot; Zero-Waste Partner</span>
            <p>Kelola katalog menu, kuota stok harian, alur masak pesanan secara live, serta pencairan saldo penjualan transparan.</p>
            <a href="{{ route('register.form', ['role' => 'umkm']) }}" class="lp-news-link">Gabung Jadi Mitra &rarr;</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  {{-- ================= DARK FOREST FOOTER ================= --}}
  <footer class="lp-footer">
    <div class="lp-container">
      <div class="lp-footer-grid">
        <div>
          <h4>Tentang Plan2Eat</h4>
          <p>
            Platform pemesanan katering sehat harian, Meal Plan terjadwal, dan Night Flash Sale bersama mitra UMKM lokal untuk gaya hidup hemat &amp; minim sampah kemasan.
          </p>
        </div>

        <div>
          <h4>Akses Akun</h4>
          <ul class="lp-footer-list">
            <li><a href="{{ route('login_page') }}">Masuk ke Akun</a></li>
            <li><a href="{{ route('register.pilihan') }}">Daftar Pelanggan</a></li>
            <li><a href="{{ route('register.form', ['role' => 'umkm']) }}">Daftar Mitra UMKM</a></li>
          </ul>
        </div>

        <div>
          <h4>Halaman</h4>
          <ul class="lp-footer-list">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#tentang">Tentang Kami</a></li>
            <li><a href="#menu">Menu Pilihan</a></li>
            <li><a href="#flashsale">Flash Sale</a></li>
            <li><a href="#fitur">Keunggulan</a></li>
          </ul>
        </div>

        <div>
          <h4>Mulai Sekarang</h4>
          <p style="margin-bottom: 16px;">
            Sudah punya akun? Masuk sekarang. Belum punya? Buat akun baru gratis.
          </p>
          <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('login_page') }}" class="lp-btn-nav-login">Masuk</a>
            <a href="{{ route('register.pilihan') }}" class="lp-btn-nav-register">Daftar</a>
          </div>
        </div>
      </div>

      <div class="lp-footer-bottom">
        <span>Copyright &copy; {{ date('Y') }} <strong>Plan2Eat</strong>. All Rights Reserved.</span>
        <span>Plan It, Order It, Irit.</span>
      </div>
    </div>
  </footer>

  @include('partials.popup_notification')

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const header = document.getElementById('lpHeader');
      const toggle = document.getElementById('lpMobileToggle');
      const drawer = document.getElementById('lpMobileDrawer');

      function syncHeaderScroll() {
        if (!header) return;
        header.classList.toggle('is-scrolled', window.scrollY > 40);
      }

      window.addEventListener('scroll', syncHeaderScroll, { passive: true });
      syncHeaderScroll();

      if (toggle && drawer) {
        toggle.addEventListener('click', function () {
          const isOpen = drawer.classList.toggle('is-open');
          toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        drawer.querySelectorAll('a').forEach(function (link) {
          link.addEventListener('click', function () {
            drawer.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
          });
        });
      }

      // Simple scroll-reveal animation
      const revealEls = document.querySelectorAll('.lp-reveal');
      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              observer.unobserve(entry.target);
            }
          });
        }, { threshold: 0.12 });

        revealEls.forEach(function (el) {
          observer.observe(el);
        });
      } else {
        revealEls.forEach(function (el) {
          el.classList.add('is-visible');
        });
      }

      // Live countdown towards Flash Sale window (20:00 - 22:00 WIB)
      const hEl = document.getElementById('cdHours');
      const mEl = document.getElementById('cdMins');
      const sEl = document.getElementById('cdSecs');

      function tickCountdown() {
        if (!hEl || !mEl || !sEl) return;
        const now = new Date();
        const target = new Date(now);
        const h = now.getHours();

        if (h >= 20 && h < 22) {
          target.setHours(22, 0, 0, 0);
        } else {
          if (h >= 22) {
            target.setDate(target.getDate() + 1);
          }
          target.setHours(20, 0, 0, 0);
        }

        const diff = Math.max(0, Math.floor((target.getTime() - now.getTime()) / 1000));
        const hrs = Math.floor(diff / 3600);
        const mins = Math.floor((diff % 3600) / 60);
        const secs = diff % 60;

        hEl.textContent = String(hrs).padStart(2, '0');
        mEl.textContent = String(mins).padStart(2, '0');
        sEl.textContent = String(secs).padStart(2, '0');
      }

      tickCountdown();
      setInterval(tickCountdown, 1000);
    });
  </script>
</body>
</html>
