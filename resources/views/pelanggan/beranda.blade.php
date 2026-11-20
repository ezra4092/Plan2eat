@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Beranda - Plan2Eat')
@section('page_badge', 'Beranda')

@section('content')
<div class="beranda-container">

  <!-- Baris Atas: Hero Carousel & Status Pesanan (1 Kolom di Mobile, 2 Kolom di Desktop) -->
  <div class="beranda-top-grid">
    
    <!-- 1. Hero Card: Zero-Waste Kitchen Carousel -->
    <section class="hero-carousel-card" id="heroCarousel">
      <div class="hero-card-header">
        <div class="badge-zero-waste">
          <img src="{{ asset('img/icon-15.svg') }}" alt="Zero Waste">
          <span>Zero-Waste Kitchen</span>
        </div>
        <button type="button" class="btn-skip-hero" id="btnSkipHero">Lewati</button>
      </div>

      <div class="hero-visual-box">
        <div class="hero-visual-img abaxudesdkzwhaeb" style="background-image: url('{{ asset('img/hero-zero-waste.jpg') }}');"></div>
        <div class="hero-visual-gradient"></div>

        <!-- Floating Badge Kiri Atas -->
        <div class="badge-circular">
          <img src="{{ asset('img/icon-12.svg') }}" alt="100% Circular">
          <span>100% Circular</span>
        </div>

        <!-- Info Bawah Banner -->
        <div class="hero-visual-caption">
          <div class="caption-icon-circle">
            <img src="{{ asset('img/icon-17.svg') }}" alt="Resep">
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

    <!-- 2. Section Status Pesanan (Inactive Order Card) -->
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
          <p>Promo hemat harian setiap pukul 20:00 - 22:00 WIB</p>
        </div>
      </div>

      <div class="fs-countdown-box">
        <div class="fs-status-pill">
          <span class="fs-pulse-dot"></span>
          <span id="fsStatusLabel">{{ ($isPromoActive ?? false) ? 'Sedang Berlangsung' : 'Dimulai Pukul 20:00' }}</span>
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
        <div class="fs-mitra-filter-label">
          <i class="fa-solid fa-store" style="color: #16442B;"></i>
          <span>Pilih Mitra:</span>
        </div>
        <div class="fs-mitra-chips" role="tablist">
          <button type="button" class="fs-mitra-chip active" data-mitra="all">
            Semua Mitra ({{ $flashSales->count() }})
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
            $fotoMenu = $fs->menu->foto_menu ?? null;
            $fotoUrl = $fotoMenu
                ? (str_starts_with($fotoMenu, 'http') ? $fotoMenu : asset('storage/' . $fotoMenu))
                : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80';
            $isHabis = ($stokSisa <= 0);
          @endphp

          <article class="fs-card" data-mitra="{{ $idUmkm }}">
            <!-- Visual Media & Badges -->
            <div class="fs-card-media">
              <img src="{{ $fotoUrl }}" alt="{{ $nama }}" class="fs-card-img" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80';">
              
              <!-- Badge Mitra di Kiri Atas -->
              <div class="fs-card-vendor-badge" title="{{ $vendor }}">
                <i class="fa-solid fa-store"></i>
                <span>{{ $vendor }}</span>
              </div>

              <!-- Pita Diskon di Kanan Atas -->
              @if ($persenDiskon > 0)
                <div class="fs-card-ribbon">
                  {{ $persenDiskon }}%
                  <span>OFF</span>
                </div>
              @endif

              <!-- Tag Ekstra di Bawah Foto -->
              <div class="fs-card-tag-special">
                <i class="fa-solid fa-tag"></i>
                <span>Zero-Waste Promo</span>
              </div>
            </div>

            <!-- Card Body Detail Informasi Promo -->
            <div class="fs-card-body">
              <h3 class="fs-card-title" title="{{ $nama }}">{{ $nama }}</h3>
              
              <div class="fs-pricing-group">
                <div class="fs-price-strike">Rp {{ number_format($hargaNormal, 0, ',', '.') }}</div>
                <div class="fs-price-discount">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</div>
              </div>

              <!-- Progress Bar Stok / Terjual -->
              <div class="fs-stock-capsule">
                <div class="fs-stock-fill {{ $stokSisa <= 5 ? 'is-low' : '' }}" style="width: {{ max(18, $persenTerjual) }}%;"></div>
                <span class="fs-stock-text">
                  {{ $stokSisa <= 5 ? 'STOK TERBATAS • SISA ' . $stokSisa : $terjual . ' TERJUAL' }}
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
    <div class="section-heading">
      <h2 class="section-title">Rekomendasi Paket Sehat</h2>
      <p class="section-subtitle">
        @if(!empty($profilKesehatan?->jenis_diet))
          Dikurasi khusus untuk preferensi diet {{ $profilKesehatan->jenis_diet }} Anda
        @else
          Dikurasi khusus berdasarkan preferensi nutrisi Anda
        @endif
      </p>
    </div>

    <div class="vendor-cards-list">
      @forelse($rekomendasiMenu as $index => $menu)
        @php
          $coverImage = $menu->foto_menu
              ? (str_starts_with($menu->foto_menu, 'http') ? $menu->foto_menu : asset('storage/' . $menu->foto_menu))
              : ($index % 2 === 0 ? asset('img/paket-keto.jpg') : asset('img/paket-earth-bowl.jpg'));

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
                <h3 class="vendor-package-name">{{ $menu->nama_makanan }}</h3>
              </div>
              <span class="badge-eco-blur">Eco Packaging</span>
            </div>
          </div>

          <div class="vendor-card-body">
            <div class="nutrition-tags">
              @if($menu->jenisMenu)
                <span class="tag-pill tag-green">{{ $menu->jenisMenu->nama }}</span>
              @else
                <span class="tag-pill tag-green">Menu Satuan</span>
              @endif

              @if(!empty($profilKesehatan?->jenis_diet))
                <span class="tag-pill tag-amber">Sesuai {{ $profilKesehatan->jenis_diet }}</span>
              @else
                <span class="tag-pill tag-amber">Bahan Segar Lokal</span>
              @endif

              <span class="tag-pill tag-neutral">{{ number_format($menu->kandungan_kalori, 0, ',', '.') }} kkal/menu</span>
              <span class="tag-pill tag-neutral">Tersedia {{ $menu->stok_harian }} porsi</span>
            </div>

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
      { subtitle: 'Minggu Ini', title: '12 Resep Nol Sampah' },
      { subtitle: 'Dampak Lingkungan', title: 'Hemat 3.4 kg Limbah Kemasan' },
      { subtitle: 'Petani Lokal Bogor', title: '100% Bahan Segar Organik' }
    ];

    const dots = document.querySelectorAll('#heroCarousel .dot');
    const subtitleEl = document.getElementById('heroSubtitle');
    const titleEl = document.getElementById('heroTitle');
    const skipBtn = document.getElementById('btnSkipHero');
    const carouselCard = document.getElementById('heroCarousel');

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        const idx = parseInt(dot.dataset.slide, 10);
        dots.forEach(d => d.classList.remove('active'));
        dot.classList.add('active');
        subtitleEl.textContent = slides[idx].subtitle;
        titleEl.textContent = slides[idx].title;
      });
    });

    if (skipBtn && carouselCard) {
      skipBtn.addEventListener('click', () => {
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
        if (statusLabel) statusLabel.textContent = 'Dimulai Pukul 20:00';
      } else if (currentH < 22) {
        target.setHours(22, 0, 0, 0);
        if (statusLabel) statusLabel.textContent = 'Sedang Berlangsung';
      } else {
        target.setDate(target.getDate() + 1);
        target.setHours(20, 0, 0, 0);
        if (statusLabel) statusLabel.textContent = 'Dimulai Besok 20:00';
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