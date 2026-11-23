@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Beranda - Plan2Eat')
@section('page_badge', 'Beranda')

@section('content')
<div class="beranda-container">

  <!-- Greeting Pelanggan -->
  <div class="beranda-greeting">
    <h1 class="beranda-greeting-title">Halo {{ auth()->user()->nama_lengkap ?? 'Pelanggan' }}, selamat datang kembali!</h1>
  </div>

  <!-- Baris Atas: Hero Carousel & Status Pesanan (1 Kolom di Mobile, 2 Kolom di Desktop) -->
  <div class="beranda-top-grid">
    
    <!-- 1. Hero Card: Zero-Waste Kitchen Carousel -->
    <section class="hero-carousel-card" id="heroCarousel">
      <div class="hero-visual-box">
        <div class="hero-visual-img abaxudesdkzwhaeb" id="heroVisualImg" style="background-image: url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80');"></div>
        <div class="hero-visual-gradient"></div>

        <!-- Info Bawah Banner -->
        <div class="hero-visual-caption">
          <div class="caption-icon-circle" aria-hidden="true">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#16442B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
              <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
            </svg>
          </div>
          <div class="caption-text-group">
            <span class="caption-subtitle" id="heroSubtitle">Minggu Ini</span>
            <h2 class="caption-title" id="heroTitle">12 Resep Nol Sampah</h2>
          </div>
        </div>
      </div>

      <!-- Pagination Dots -->
      <div class="carousel-dots">
        <button type="button" class="dot active" data-slide="0" aria-label="Slide 1"></button>
        <button type="button" class="dot" data-slide="1" aria-label="Slide 2"></button>
        <button type="button" class="dot" data-slide="2" aria-label="Slide 3"></button>
      </div>
    </section>

    <!-- 2. Kolom Kanan: Card Saldo + Section Status Pesanan -->
    <div class="beranda-status-col">
      <!-- Card Info Saldo Pelanggan (Just Info, Tanpa Button) -->
      <section class="beranda-saldo-card" aria-label="Informasi Saldo Pelanggan">
        <div class="beranda-saldo-left">
          <span class="beranda-saldo-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
              <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
            </svg>
          </span>
          <div class="beranda-saldo-text">
            <span class="beranda-saldo-label">Saldo Plan2Eat</span>
            <strong class="beranda-saldo-amount">Rp {{ number_format((float) ($userSaldo ?? 0), 0, ',', '.') }}</strong>
          </div>
        </div>
        <span class="beranda-saldo-note">Dana refund &amp; saldo aktif</span>
      </section>

      <!-- Section Status Pesanan -->
      @if (!empty($activeOrder))
        @php
          $step = (int) ($activeOrder['progress_step'] ?? 1);
          $progressPercent = $step <= 1 ? 0 : ($step === 2 ? 50 : 100);
        @endphp
        <section class="order-status-card is-active-beranda">
          <div class="lgn-schedule-pill">
            <span class="lgn-green-dot"></span>
            <span>{{ $activeOrder['schedule_label'] }}</span>
          </div>

          <div class="lgn-order-product">
            <img
              src="{{ $activeOrder['image'] }}"
              alt="{{ $activeOrder['menu_name'] }}"
              class="lgn-product-thumb"
              onerror="this.src='https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80';"
            >
            <div class="lgn-product-info">
              <div class="lgn-vendor-row">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 9l1.5-5h15L21 9"/>
                  <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/>
                  <path d="M5 12v8h14v-8"/>
                </svg>
                <span>{{ $activeOrder['mitra_name'] }}</span>
              </div>
              <h3 class="lgn-dish-title">{{ $activeOrder['menu_name'] }}</h3>
              <p class="lgn-dish-meta">{{ $activeOrder['portion_detail'] }}</p>
              <span class="lgn-today-badge">{{ $activeOrder['badge_text'] }}</span>
            </div>
          </div>

          <div class="lgn-progress-box">
            <div class="lgn-progress-head">
              <span class="lgn-progress-title">Progress</span>
              <span class="lgn-progress-status">{{ $activeOrder['progress_label'] }}</span>
            </div>

            <div class="lgn-stepper" style="--lgn-progress: {{ $progressPercent }};">
              <div class="lgn-stepper-track">
                <span class="lgn-stepper-fill"></span>
              </div>

              <div class="lgn-step {{ $step >= 1 ? 'is-done' : '' }}">
                <span class="lgn-step-circle">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </span>
                <span class="lgn-step-label">Prep</span>
              </div>

              <div class="lgn-step {{ $step >= 2 ? 'is-done' : '' }}">
                <span class="lgn-step-circle">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </span>
                <span class="lgn-step-label">Masak</span>
              </div>

              <div class="lgn-step {{ $step >= 3 ? 'is-done' : '' }}">
                <span class="lgn-step-circle">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </span>
                <span class="lgn-step-label">Siap</span>
              </div>
            </div>
          </div>

          <div class="lgn-contact-wrap">
            <a href="{{ $activeOrder['detail_url'] }}" class="lgn-btn-contact">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
              </svg>
              <span>Lihat Detail Pesanan</span>
            </a>
          </div>
        </section>
      @else
        <section class="order-status-card">
          <div class="order-status-content">
            <p class="order-empty-text">Anda belum membuat pesanan</p>
            <form action="{{ route('pelanggan.mitra') }}" method="GET">
              <button type="submit" class="btn-add-order">
                <img src="{{ asset('img/vector-5.svg') }}" alt="Tambah">
                <span>Tambahkan Pesanan</span>
              </button>
            </form>
          </div>
        </section>
      @endif
    </div>
  </div>

  <!-- SECTION FLASH SALE (Promo Harian Jam 20:00 - 22:00) -->
  <section class="flash-sale-section" id="flashSaleSection">
    
    <!-- Banner Header Flash Sale -->
    <div class="fs-header-banner">
      <div class="fs-header-title-box">
        <div class="fs-lightning-icon">
          <i class="fa-solid fa-bolt"></i>
        </div>
        <div class="fs-header-text">
          <h2>FLASH SALE MALAM</h2>
          <p>Setiap pukul 20:00 - 22:00 WIB</p>
        </div>
      </div>

      <div class="fs-countdown-box">
        <div class="fs-status-pill">
          <span class="fs-pulse-dot"></span>
          <span id="fsStatusLabel">{{ ($isPromoActive ?? false) ? 'Berlangsung' : 'Mulai 20:00' }}</span>
        </div>
        <div class="fs-timer-display" id="fsTimerDisplay">
          <span class="fs-timer-unit" id="timerHours">00</span>
          <span class="fs-timer-sep">:</span>
          <span class="fs-timer-unit" id="timerMinutes">00</span>
          <span class="fs-timer-sep">:</span>
          <span class="fs-timer-unit" id="timerSeconds">00</span>
        </div>
      </div>
    </div>

    @if ($isPromoActive ?? false)
      <!-- Filter Tab Mitra Pilihan Pelanggan -->
      <div class="fs-mitra-filter-row">
        <div class="fs-mitra-chips" role="tablist">
          <button type="button" class="fs-mitra-chip active" data-mitra="all">
            Semua ({{ $flashSales->count() }})
          </button>
          @foreach ($mitraList as $mitra)
            @php
              $mCount = $flashSales->where('menu.id_umkm', $mitra->id_umkm)->count();
            @endphp
            <button type="button" class="fs-mitra-chip" data-mitra="{{ $mitra->id_umkm }}">
              {{ $mitra->nama_vendor }} ({{ $mCount }})
            </button>
          @endforeach
        </div>
      </div>

      <!-- Grid Card Flash Sale (Responsif Web Desktop & Mobile) -->
      <div class="fs-cards-grid" id="fsCardsGrid">
        @forelse ($flashSales as $fs)
          @php
            $nama = $fs->menu->nama_makanan ?? 'Menu Flash Sale';
            $vendor = $fs->menu->umkm->nama_vendor ?? 'Mitra Pilihan';
            $idUmkm = $fs->menu->id_umkm ?? 0;
            $hargaNormal = (float) ($fs->menu->harga_normal ?? 0);
            $hargaDiskon = (float) $fs->harga_diskon;
            $stokSisa = (int) $fs->stok_sisa;
            $kuantitas = max(1, (int) $fs->kuantitas);
            $terjual = max(0, $kuantitas - $stokSisa);
            $persenDiskon = $hargaNormal > 0 ? round((($hargaNormal - $hargaDiskon) / $hargaNormal) * 100) : 0;
            $persenTerjual = min(100, round(($terjual / $kuantitas) * 100));
            $fotoUrl = \App\Models\Menu::resolveFotoUrl(
                $fs->menu->foto_menu ?? null,
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80'
            );
            $isHabis = ($stokSisa <= 0);
          @endphp

          <article class="fs-card" data-mitra="{{ $idUmkm }}">
            <!-- Visual Media (Bersih: Hanya Foto + Pita Diskon) -->
            <div class="fs-card-media">
              <img src="{{ $fotoUrl }}" alt="{{ $nama }}" class="fs-card-img" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80';">

              @if ($persenDiskon > 0)
                <div class="fs-card-ribbon">
                  {{ $persenDiskon }}%
                  <span>OFF</span>
                </div>
              @endif
            </div>

            <!-- Card Body Detail Informasi Promo -->
            <div class="fs-card-body">
              <span class="fs-card-vendor-name" title="{{ $vendor }}">
                <i class="fa-solid fa-store" aria-hidden="true"></i>
                <span>{{ $vendor }}</span>
              </span>

              <h3 class="fs-card-title" title="{{ $nama }}">{{ $nama }}</h3>
              
              <div class="fs-pricing-group">
                <div class="fs-price-strike">Rp {{ number_format($hargaNormal, 0, ',', '.') }}</div>
                <div class="fs-price-discount">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</div>
              </div>

              <!-- Progress Bar Stok / Terjual -->
              <div class="fs-stock-capsule {{ $stokSisa <= 5 ? 'is-low-bg' : '' }}">
                <div class="fs-stock-fill {{ $stokSisa <= 5 ? 'is-low' : '' }}" style="width: {{ max(22, $persenTerjual) }}%;"></div>
                <span class="fs-stock-text">
                  {{ $stokSisa <= 5 ? 'Sisa ' . $stokSisa . ' porsi' : $terjual . ' terjual' }}
                </span>
              </div>

              <!-- Tombol Pesan -->
              <a href="{{ $isHabis ? '#' : route('pelanggan.keranjang', ['menu' => $fs->id_menu, 'fs' => $fs->id_flash_sale]) }}" class="fs-btn-order {{ $isHabis ? 'disabled' : '' }}">
                {{ $isHabis ? 'Stok Habis' : 'Pesan Sekarang' }}
              </a>
            </div>
          </article>
        @empty
          <div class="fs-empty-message">
            Belum ada menu Flash Sale aktif saat ini.
          </div>
        @endforelse

        <div class="fs-empty-message" id="fsNoFilterResult" style="display: none;">
          Tidak ada menu Flash Sale dari mitra ini saat ini.
        </div>
      </div>
    @else
      <div class="fs-empty-message">
        <i class="fa-regular fa-clock" style="color: #16442B; margin-right: 6px;"></i>
        Menu Flash Sale hanya muncul pada pukul <strong>20:00 - 22:00 WIB</strong>. Nantikan promo hemat malam ini!
      </div>
    @endif
  </section>

  <!-- 3. Section Rekomendasi Paket Sehat -->
  <section class="recommended-section">
    <div class="recommended-header-row">
      <div class="section-heading">
        <h2 class="section-title">Rekomendasi Paket Sehat</h2>
      </div>

      <a href="{{ route('pelanggan.mitra') }}" class="beranda-see-mitra-link">
        <span>Lihat semua mitra UMKM</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>
    </div>

    <div class="vendor-cards-list">
      @forelse($rekomendasiMenu as $index => $menu)
        @php
          $coverImage = \App\Models\Menu::resolveFotoUrl(
              $menu->foto_menu ?? null,
              'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80'
          );

          $badgeIcon = $index % 2 === 0 ? asset('img/icon-3.svg') : asset('img/icon-16.svg');
          $ratingIcon = $index % 2 === 0 ? asset('img/icon-2.svg') : asset('img/icon-13.svg');
          $actionIcon = $index % 2 === 0 ? asset('img/icon-11.svg') : asset('img/icon-10.svg');
        @endphp

        <article class="vendor-card">
          <div class="vendor-media-header">
            <div class="vendor-cover-img {{ $index % 2 === 0 ? 'uf' : 'div-2' }}" style="background-image: url('{{ $coverImage }}');"></div>
            <div class="vendor-media-gradient"></div>

            <div class="vendor-badges-top">
              <div class="badge-glass-white">
                <img src="{{ $badgeIcon }}" alt="Kategori">
                <span>{{ $menu->jenisMenu->nama ?? 'Pilihan Dietitian' }}</span>
              </div>
              <div class="badge-glass-white">
                <img src="{{ $ratingIcon }}" alt="Stok">
                <span>Stok {{ $menu->stok_harian }}</span>
              </div>
            </div>

            <div class="vendor-media-bottom">
              <div class="vendor-title-group">
                <p class="vendor-location">{{ $menu->umkm->nama_vendor ?? 'Mitra Dapur Plan2Eat' }} &bull; Bogor</p>
                
              </div>
            </div>
          </div>

          <div class="vendor-card-body">
            <h3 class="vendor-package-name" style="color: black">{{ $menu->nama_makanan }}</h3>
            <p class="vendor-description">
              {{ $menu->deskripsi_makanan }}
            </p>

            <div class="vendor-card-footer">
              <div class="price-info">
                <span class="price-label">{{ $menu->jenisMenu ? 'Paket Mulai' : 'Harga Menu' }}</span>
                <div class="price-amount">
                  <span class="price-value">Rp {{ number_format($menu->harga_normal, 0, ',', '.') }}</span>
                  <span class="price-period">{{ $menu->jenisMenu ? '/paket' : '/porsi' }}</span>
                </div>
              </div>

              <a href="{{ route('pelanggan.keranjang', ['menu' => $menu->id_menu]) }}" class="btn-select-package">
                <span>Pilih Paket</span>
                <img src="{{ $actionIcon }}" alt="Pilih">
              </a>
            </div>
          </div>
        </article>
      @empty
        <article class="vendor-card">
          <div class="vendor-card-body">
            <p class="vendor-description">Belum ada rekomendasi paket menu yang tersedia saat ini.</p>
          </div>
        </article>
      @endforelse
    </div>
  </section>

</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const slides = [
      {
        subtitle: 'Minggu Ini',
        title: '12 Resep Nol Sampah',
        image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80'
      },
      {
        subtitle: 'Dampak Lingkungan',
        title: 'Hemat 3.4 kg Limbah Kemasan',
        image: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80'
      },
      {
        subtitle: 'Petani Lokal Bogor',
        title: '100% Bahan Segar Organik',
        image: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=900&q=80'
      }
    ];

    const dots = document.querySelectorAll('#heroCarousel .dot');
    const subtitleEl = document.getElementById('heroSubtitle');
    const titleEl = document.getElementById('heroTitle');
    const visualImgEl = document.getElementById('heroVisualImg');
    const skipBtn = document.getElementById('btnSkipHero');
    const carouselCard = document.getElementById('heroCarousel');
    let currentSlideIdx = 0;

    function goToSlide(idx) {
      currentSlideIdx = idx % slides.length;
      dots.forEach((d, i) => d.classList.toggle('active', i === currentSlideIdx));
      if (subtitleEl) subtitleEl.textContent = slides[currentSlideIdx].subtitle;
      if (titleEl) titleEl.textContent = slides[currentSlideIdx].title;
      if (visualImgEl) {
        visualImgEl.style.backgroundImage = "url('" + slides[currentSlideIdx].image + "')";
      }
    }

    let heroAutoTimer = setInterval(function () {
      goToSlide(currentSlideIdx + 1);
    }, 3000);

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        const idx = parseInt(dot.dataset.slide, 10);
        goToSlide(idx);
        clearInterval(heroAutoTimer);
        heroAutoTimer = setInterval(function () {
          goToSlide(currentSlideIdx + 1);
        }, 3000);
      });
    });

    if (skipBtn && carouselCard) {
      skipBtn.addEventListener('click', () => {
        clearInterval(heroAutoTimer);
        carouselCard.style.display = 'none';
      });
    }

    // ==========================================
    // FLASH SALE: Countdown Timer & Filter Mitra
    // ==========================================
    const timerH = document.getElementById('timerHours');
    const timerM = document.getElementById('timerMinutes');
    const timerS = document.getElementById('timerSeconds');
    const statusLabel = document.getElementById('fsStatusLabel');

    function updateFlashSaleTimer() {
      const now = new Date();
      const currentH = now.getHours();

      let target = new Date(now);

      if (currentH < 20) {
        target.setHours(20, 0, 0, 0);
        if (statusLabel) statusLabel.textContent = 'Mulai 20:00';
      } else if (currentH < 22) {
        target.setHours(22, 0, 0, 0);
        if (statusLabel) statusLabel.textContent = 'Berlangsung';
      } else {
        target.setDate(target.getDate() + 1);
        target.setHours(20, 0, 0, 0);
        if (statusLabel) statusLabel.textContent = 'Besok 20:00';
      }

      const diff = Math.max(0, Math.floor((target - now) / 1000));
      const hours = Math.floor(diff / 3600);
      const minutes = Math.floor((diff % 3600) / 60);
      const seconds = diff % 60;

      if (timerH) timerH.textContent = String(hours).padStart(2, '0');
      if (timerM) timerM.textContent = String(minutes).padStart(2, '0');
      if (timerS) timerS.textContent = String(seconds).padStart(2, '0');
    }

    updateFlashSaleTimer();
    setInterval(updateFlashSaleTimer, 1000);

    // Filter Mitra Interaktif
    const mitraChips = document.querySelectorAll('.fs-mitra-chip');
    const fsCards = document.querySelectorAll('.fs-card');
    const noResultMsg = document.getElementById('fsNoFilterResult');

    mitraChips.forEach(chip => {
      chip.addEventListener('click', function () {
        mitraChips.forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const selectedMitra = this.dataset.mitra;
        let visibleCount = 0;

        fsCards.forEach(card => {
          if (selectedMitra === 'all' || card.dataset.mitra === selectedMitra) {
            card.style.display = 'flex';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        if (noResultMsg) {
          noResultMsg.style.display = (visibleCount === 0 && fsCards.length > 0) ? 'block' : 'none';
        }
      });
    });
  });
</script>
@endpush