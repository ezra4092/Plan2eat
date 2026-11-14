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
        <a href="{{ url('/pesanan/tambah') }}" class="btn-add-order">
          <img src="{{ asset('img/vector-5.svg') }}" alt="Tambah">
          <span>Tambahkan Pesanan</span>
        </a>
      </div>
    </section>

  </div>

  <!-- 3. Section Rekomendasi Paket Sehat -->
  <section class="recommended-section">
    <div class="section-heading">
      <h2 class="section-title">Rekomendasi Paket Sehat</h2>
      <p class="section-subtitle">Dikurasi khusus berdasarkan preferensi nutrisi Anda</p>
    </div>

    <div class="vendor-cards-list">
      <!-- Vendor Card 1 -->
      <article class="vendor-card">
        <div class="vendor-media-header">
          <div class="vendor-cover-img uf" style="background-image: url('{{ asset('img/paket-keto.jpg') }}');"></div>
          <div class="vendor-media-gradient"></div>

          <div class="vendor-badges-top">
            <div class="badge-glass-white">
              <img src="{{ asset('img/icon-3.svg') }}" alt="Dietitian">
              <span>Pilihan Dietitian</span>
            </div>
            <div class="badge-glass-white">
              <img src="{{ asset('img/icon-2.svg') }}" alt="Rating">
              <span>4.9 (1.4k)</span>
            </div>
          </div>

          <div class="vendor-media-bottom">
            <div class="vendor-title-group">
              <p class="vendor-location">Dapur Botanika &bull; 1.2 km</p>
              <h3 class="vendor-package-name">Paket Keto Clean Energizer</h3>
            </div>
            <span class="badge-eco-blur">Eco Packaging</span>
          </div>
        </div>

        <div class="vendor-card-body">
          <div class="nutrition-tags">
            <span class="tag-pill tag-green">Bebas Gluten</span>
            <span class="tag-pill tag-amber">Kacang Terpisah</span>
            <span class="tag-pill tag-neutral">450 kkal/menu</span>
            <span class="tag-pill tag-neutral">0g Gula Rafinasi</span>
          </div>

          <p class="vendor-description">
            Siklus menu 5 hari bebas inflamasi dengan sumber lemak sehat organik dari perkebunan lokal Bogor.
          </p>

          <div class="vendor-card-footer">
            <div class="price-info">
              <span class="price-label">Langganan Mulai</span>
              <div class="price-amount">
                <span class="price-value">Rp 100.000</span>
                <span class="price-period">/minggu</span>
              </div>
            </div>

            <a href="{{ url('/paket/keto-clean-energizer') }}" class="btn-select-package">
              <span>Pilih Paket</span>
              <img src="{{ asset('img/icon-11.svg') }}" alt="Pilih">
            </a>
          </div>
        </div>
      </article>

      <!-- Vendor Card 2 -->
      <article class="vendor-card">
        <div class="vendor-media-header">
          <div class="vendor-cover-img div-2" style="background-image: url('{{ asset('img/paket-earth-bowl.jpg') }}');"></div>
          <div class="vendor-media-gradient"></div>

          <div class="vendor-badges-top">
            <div class="badge-glass-white">
              <img src="{{ asset('img/icon-16.svg') }}" alt="Plant-Based">
              <span>100% Plant-Based</span>
            </div>
            <div class="badge-glass-white">
              <img src="{{ asset('img/icon-13.svg') }}" alt="Rating">
              <span>4.8 (890)</span>
            </div>
          </div>

          <div class="vendor-media-bottom">
            <div class="vendor-title-group">
              <p class="vendor-location">Kebun Jiwa Artisan Kitchen &bull; 2.4 km</p>
              <h3 class="vendor-package-name">Regenerative Earth Bowl Plan</h3>
            </div>
            <span class="badge-eco-blur">Food-Miles<br>Rendah</span>
          </div>
        </div>

        <div class="vendor-card-body">
          <div class="nutrition-tags">
            <span class="tag-pill tag-green">Vegan Ramah Alergi</span>
            <span class="tag-pill tag-amber">Bebas Susu Hewani</span>
            <span class="tag-pill tag-neutral">390 kkal/menu</span>
            <span class="tag-pill tag-neutral">High Fiber</span>
          </div>

          <p class="vendor-description">
            Protein nabati fermentasi tradisional berpadu dengan sayur organik bebas pestisida langsung dari petani mitra.
          </p>

          <div class="vendor-card-footer">
            <div class="price-info">
              <span class="price-label">Langganan Mulai</span>
              <div class="price-amount">
                <span class="price-value">Rp 98.000</span>
                <span class="price-period">/minggu</span>
              </div>
            </div>

            <a href="{{ url('/paket/regenerative-earth-bowl') }}" class="btn-select-package">
              <span>Pilih Paket</span>
              <img src="{{ asset('img/icon-10.svg') }}" alt="Pilih">
            </a>
          </div>
        </div>
      </article>
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
  });
</script>
@endpush