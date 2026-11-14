@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Beranda UMKM - Plan2Eat')
@section('page_badge', 'Dapur Mitra')

@section('content')
<div class="umkm-dashboard">

  <!-- 1. Hero Card Status Toko ("Halo, UMKM !") -->
  <section class="umkm-hero-card">
    <div class="umkm-hero-top">
      <h1 class="umkm-hero-title">Halo, UMKM !</h1>

      <button type="button" class="store-status-toggle is-open" id="btnStoreStatus" aria-pressed="true">
        <span class="status-dot"></span>
        <span class="status-text" id="storeStatusText">Buka</span>
      </button>
    </div>

    <p class="umkm-hero-subtitle">
      Kamu memiliki <strong>8 porsi</strong> pesanan yang harus disiapkan hari ini
    </p>

    <div class="umkm-hero-actions">
      <a href="{{ url('/umkm/manajemen/menu') }}" class="btn-umkm-hero">
        <img src="{{ asset('img/image.svg') }}" alt="Atur Menu">
        <span>Atur Menu</span>
      </a>
      <a href="{{ url('/umkm/manajemen/jadwal') }}" class="btn-umkm-hero">
        <img src="{{ asset('img/icon-10.svg') }}" alt="Lihat Jadwal Masak">
        <span>Lihat Jadwal Masak</span>
      </a>
    </div>
  </section>

  <!-- 2. Grid 4 Metrik Operasional -->
  <section class="umkm-metrics-grid" aria-label="Ringkasan Operasional">
    <!-- Metrik 1: Pelanggan Aktif -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Pelanggan Aktif</span>
        <div class="metric-icon-circle icon-mint">
          <i class="fa-solid fa-house"></i>
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">19</div>
        <div class="metric-trend">
          <img src="{{ asset('img/icon-6.svg') }}" alt="Naik">
          <span>+12% mgg ini</span>
        </div>
      </div>
    </article>

    <!-- Metrik 2: Perlu diproses -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Perlu diproses</span>
        <div class="metric-icon-circle icon-amber">
          <img src="{{ asset('img/icon-7.svg') }}" alt="Perlu diproses">
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">8</div>
        <div class="metric-progress-track" aria-label="Progres pesanan diproses">
          <div class="metric-progress-fill" style="width: 62%;"></div>
        </div>
      </div>
    </article>

    <!-- Metrik 3: Rating Warung -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Rating Warung</span>
        <div class="metric-icon-circle icon-amber">
          <img src="{{ asset('img/vector.svg') }}" alt="Rating Warung">
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value-group">
          <span class="metric-value">4.8</span>
          <span class="metric-value-sub">/ 5</span>
        </div>
        <span class="metric-caption">Ulasan Mahasiswa</span>
      </div>
    </article>

    <!-- Metrik 4: Pesanan Selesai -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Pesanan Selesai</span>
        <div class="metric-icon-circle icon-mint">
          <img src="{{ asset('img/vector-7.svg') }}" alt="Pesanan Selesai">
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">20</div>
        <div class="metric-progress-track" aria-label="Pesanan selesai 100%">
          <div class="metric-progress-fill" style="width: 100%;"></div>
        </div>
      </div>
    </article>
  </section>

  <!-- 3. Grid Bawah: Grafik Porsi Mingguan & Total Penghasilan -->
  <div class="umkm-analytics-grid">
    
    <!-- Kartu Grafik Porsi Mingguan -->
    <section class="weekly-chart-card">
      <div class="chart-card-header">
        <h2 class="chart-card-title">Grafik Porsi Mingguan</h2>
        <button type="button" class="chart-filter-pill">
          <span>7 Hari Terakhir</span>
          <img src="{{ asset('img/vector-8.svg') }}" alt="Filter">
        </button>
      </div>

      <div class="chart-inner-surface">
        <div class="chart-canvas-wrapper">
          <!-- Sumbu Y -->
          <div class="chart-y-axis">
            <span>20</span>
            <span>10</span>
            <span>0</span>
          </div>

          <!-- Area Batang & Garis Grid -->
          <div class="chart-plot-area">
            <div class="chart-grid-lines">
              <div class="grid-line"></div>
              <div class="grid-line"></div>
              <div class="grid-line solid"></div>
            </div>

            <div class="chart-groups-row">
              <!-- Grup 1: Katering -->
              <div class="bar-category-group">
                <div class="bars-cluster">
                  <div class="bar-col">
                    <div class="bar-fill day-senin" style="height: 75%;"><span class="bar-num">15</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-selasa" style="height: 55%;"><span class="bar-num">11</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-rabu" style="height: 25%;"><span class="bar-num">5</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-kamis" style="height: 100%;"><span class="bar-num">20</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-jumat" style="height: 70%;"><span class="bar-num">14</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-sabtu" style="height: 55%;"><span class="bar-num">11</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-minggu" style="height: 95%;"><span class="bar-num">19</span></div>
                  </div>
                </div>
                <span class="category-label">Katering</span>
              </div>

              <!-- Grup 2: Night Flash Sale -->
              <div class="bar-category-group">
                <div class="bars-cluster">
                  <div class="bar-col">
                    <div class="bar-fill day-senin" style="height: 25%;"><span class="bar-num">5</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-selasa" style="height: 12%;"><span class="bar-num">2</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-rabu" style="height: 35%;"><span class="bar-num">7</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-kamis" style="height: 12%;"><span class="bar-num">2</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-jumat" style="height: 25%;"><span class="bar-num">5</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-sabtu" style="height: 20%;"><span class="bar-num">4</span></div>
                  </div>
                  <div class="bar-col">
                    <div class="bar-fill day-minggu" style="height: 25%;"><span class="bar-num">5</span></div>
                  </div>
                </div>
                <span class="category-label">Night Flash Sale</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Legenda Hari -->
        <div class="chart-legends">
          <div class="legend-item"><span class="legend-dot day-senin"></span><span>Senin</span></div>
          <div class="legend-item"><span class="legend-dot day-selasa"></span><span>Selasa</span></div>
          <div class="legend-item"><span class="legend-dot day-rabu"></span><span>Rabu</span></div>
          <div class="legend-item"><span class="legend-dot day-kamis"></span><span>Kamis</span></div>
          <div class="legend-item"><span class="legend-dot day-jumat"></span><span>Jumat</span></div>
          <div class="legend-item"><span class="legend-dot day-sabtu"></span><span>Sabtu</span></div>
          <div class="legend-item"><span class="legend-dot day-minggu"></span><span>Minggu</span></div>
        </div>
      </div>
    </section>

    <!-- Kartu Total Penghasilan -->
    <section class="income-summary-card">
      <div class="income-card-header">
        <div class="income-icon-box">
          <img src="{{ asset('img/icon-park-outline-funds.svg') }}" alt="Total Penghasilan">
        </div>
        <h2 class="income-card-title">Total Penghasilan</h2>
      </div>

      <div class="income-stats-list">
        <div class="income-stat-box">
          <span class="income-stat-label">TOTAL PENDAPATAN TERKUMPUL</span>
          <div class="income-stat-value">Rp 400.000</div>
        </div>

        <div class="income-stat-box">
          <span class="income-stat-label">PENJUALAN NIGHT FLASH SALE</span>
          <div class="income-stat-value">Rp 150.000</div>
        </div>
      </div>

      <a href="{{ url('/umkm/saldo') }}" class="btn-income-settings">
        <span>Saldo &amp; Pengaturan Rekening</span>
      </a>
    </section>

  </div>

</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('btnStoreStatus');
    const statusText = document.getElementById('storeStatusText');

    if (toggleBtn && statusText) {
      toggleBtn.addEventListener('click', function () {
        const isOpen = toggleBtn.classList.toggle('is-open');
        toggleBtn.setAttribute('aria-pressed', isOpen ? 'true' : 'false');
        statusText.textContent = isOpen ? 'Buka' : 'Tutup';
      });
    }
  });
</script>
@endpush