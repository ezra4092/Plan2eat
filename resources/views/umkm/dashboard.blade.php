@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Beranda UMKM - Plan2Eat')
@section('page_badge', 'Dapur Mitra')

@section('content')
@php
  $umkmSuspended = (bool) ($isSuspended ?? false);
  $isStoreOpen = ! $umkmSuspended && strtolower((string) ($umkm->status_operasional ?? 'Tutup')) === 'buka';
  $toggleStatusUrl = route('umkm.profil-status', $umkm->id_umkm);
  $nextRange = ($range ?? '7_days') === '7_days' ? '30_days' : '7_days';
  $rangeLabel = ($range ?? '7_days') === '30_days' ? '30 Hari Terakhir' : '7 Hari Terakhir';
@endphp

<div class="umkm-dashboard">

  <!-- 1. Hero Card Status Toko -->
  <section class="umkm-hero-card">
    <div class="umkm-hero-top">
      <h1 class="umkm-hero-title">Halo, {{ $umkm->nama_vendor ?? 'UMKM' }}!</h1>

      <button
        type="button"
        class="store-status-toggle {{ $isStoreOpen ? 'is-open' : '' }}"
        id="btnStoreStatus"
        data-url="{{ $toggleStatusUrl }}"
        aria-pressed="{{ $isStoreOpen ? 'true' : 'false' }}"
        @if ($umkmSuspended) disabled style="opacity: 0.65; cursor: not-allowed;" title="Toko tidak dapat dibuka selama masa penangguhan 3 hari" @endif
      >
        <span class="status-dot"></span>
        <span class="status-text" id="storeStatusText">{{ $umkmSuspended ? 'Tutup (Ditangguhkan)' : ($isStoreOpen ? 'Buka' : 'Tutup') }}</span>
      </button>
    </div>

    <p class="umkm-hero-subtitle">
      @if (($porsiPerluDiproses ?? 0) > 0)
        Kamu memiliki <strong>{{ $porsiPerluDiproses }} porsi</strong> pesanan yang perlu disiapkan
      @else
        Belum ada pesanan aktif yang perlu disiapkan saat ini
      @endif
    </p>

    <div class="umkm-hero-actions">
      <a href="{{ route('umkm.menu') }}" class="btn-umkm-hero">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
          <path d="M7 2v20"/>
          <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
        </svg>
        <span>Atur Menu</span>
      </a>
      <a href="{{ route('umkm.pesanan') }}" class="btn-umkm-hero">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span>Lihat Pesanan Masuk</span>
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
          <i class="fa-solid fa-people-line"></i>
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">{{ $pelangganAktif ?? 0 }}</div>
        <div class="metric-trend">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1E6B3A" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
            <polyline points="17 6 23 6 23 12"/>
          </svg>
          <span>{{ $pelangganMingguIni ?? 0 }} transaksi 7 hr</span>
        </div>
      </div>
    </article>

    <!-- Metrik 2: Perlu diproses -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Perlu diproses</span>
        <div class="metric-icon-circle icon-amber">
          <i class="fa-solid fa-spinner"></i>
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">{{ $pesananPerluDiproses ?? 0 }}</div>
        <div class="metric-progress-track" aria-label="Progres pesanan diproses">
          <div class="metric-progress-fill" style="width: {{ $progressDiproses ?? 0 }}%;"></div>
        </div>
      </div>
    </article>

    <!-- Metrik 3: Rating Warung -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Rating Warung</span>
        <div class="metric-icon-circle icon-amber">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value-group">
          <span class="metric-value">{{ number_format((float) ($ratingWarung ?? 0), 1) }}</span>
          <span class="metric-value-sub">/ 5</span>
        </div>
        <span class="metric-caption">{{ $totalUlasan ?? 0 }} Ulasan Pelanggan</span>
      </div>
    </article>

    <!-- Metrik 4: Pesanan Selesai -->
    <article class="metric-card">
      <div class="metric-header">
        <span class="metric-title">Pesanan Selesai</span>
        <div class="metric-icon-circle icon-mint">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#16442B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
      </div>
      <div class="metric-body">
        <div class="metric-value">{{ $pesananSelesai ?? 0 }}</div>
        <div class="metric-progress-track" aria-label="Persentase pesanan selesai">
          <div class="metric-progress-fill" style="width: {{ $progressSelesai ?? 0 }}%;"></div>
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
        <a href="{{ route('umkm.dashboard', ['range' => $nextRange]) }}" class="chart-filter-pill" style="text-decoration: none;" title="Ubah rentang waktu">
          <span>{{ $rangeLabel }}</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </a>
      </div>

      <div class="chart-inner-surface">
        <div class="chart-canvas-wrapper">
          <!-- Sumbu Y -->
          <div class="chart-y-axis">
            <span>{{ $chartMax ?? 10 }}</span>
            <span>{{ $chartMid ?? 5 }}</span>
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
                  @foreach ($chartDays as $day)
                    @php
                      $val = (int) ($kateringPerDay[$day['key']] ?? 0);
                      $pct = $chartMax > 0 ? round(($val / $chartMax) * 100) : 0;
                      $heightStyle = $val > 0 ? max(10, min(100, $pct)) . '%' : '4px';
                    @endphp
                    <div class="bar-col">
                      <div class="bar-fill day-{{ $day['key'] }}" style="height: {{ $heightStyle }};"><span class="bar-num">{{ $val }}</span></div>
                    </div>
                  @endforeach
                </div>
                <span class="category-label">Katering</span>
              </div>

              <!-- Grup 2: Night Flash Sale -->
              <div class="bar-category-group">
                <div class="bars-cluster">
                  @foreach ($chartDays as $day)
                    @php
                      $val = (int) ($flashSalePerDay[$day['key']] ?? 0);
                      $pct = $chartMax > 0 ? round(($val / $chartMax) * 100) : 0;
                      $heightStyle = $val > 0 ? max(10, min(100, $pct)) . '%' : '4px';
                    @endphp
                    <div class="bar-col">
                      <div class="bar-fill day-{{ $day['key'] }}" style="height: {{ $heightStyle }};"><span class="bar-num">{{ $val }}</span></div>
                    </div>
                  @endforeach
                </div>
                <span class="category-label">Night Flash Sale</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Legenda Hari -->
        <div class="chart-legends">
          @foreach ($chartDays as $day)
            <div class="legend-item"><span class="legend-dot day-{{ $day['key'] }}"></span><span>{{ $day['label'] }}</span></div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- Kartu Total Penghasilan -->
    <section class="income-summary-card">
      <div class="income-card-header">
        <div class="income-icon-box">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16442B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
          </svg>
        </div>
        <h2 class="income-card-title">Total Penghasilan</h2>
      </div>

      <div class="income-stats-list">
        <div class="income-stat-box">
          <span class="income-stat-label">TOTAL PENDAPATAN TERKUMPUL</span>
          <div class="income-stat-value">Rp {{ number_format((float) ($totalPendapatan ?? 0), 0, ',', '.') }}</div>
        </div>

        <div class="income-stat-box">
          <span class="income-stat-label">PENJUALAN NIGHT FLASH SALE</span>
          <div class="income-stat-value">Rp {{ number_format((float) ($pendapatanFlashSale ?? 0), 0, ',', '.') }}</div>
        </div>
      </div>

      <a href="{{ route('umkm.laporan_keuangan') }}" class="btn-income-settings">
        <span>Saldo &amp; Pencairan Dana</span>
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
    const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content;

    if (!toggleBtn || !statusText) return;

    toggleBtn.addEventListener('click', async function () {
      const currentlyOpen = toggleBtn.classList.contains('is-open');
      const nextStatus = currentlyOpen ? 'Tutup' : 'Buka';
      const url = toggleBtn.dataset.url;

      toggleBtn.disabled = true;

      try {
        const res = await fetch(url, {
          method: 'PATCH',
          credentials: 'same-origin',
          headers: {
            'X-CSRF-TOKEN': csrfToken || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ status_operasional: nextStatus })
        });

        const data = await res.json();
        if (!res.ok || !data.success) {
          throw new Error(data.message || 'Gagal mengubah status toko');
        }

        const isNowOpen = (data.status_operasional || '').toLowerCase() === 'buka';
        toggleBtn.classList.toggle('is-open', isNowOpen);
        toggleBtn.setAttribute('aria-pressed', isNowOpen ? 'true' : 'false');
        statusText.textContent = isNowOpen ? 'Buka' : 'Tutup';

        if (typeof window.showAppPopup === 'function') {
          window.showAppPopup({
            type: 'success',
            title: 'Status Toko Diperbarui',
            message: data.message || ('Status warung diubah menjadi ' + statusText.textContent)
          });
        }
      } catch (err) {
        if (typeof window.showAppPopup === 'function') {
          window.showAppPopup({
            type: 'error',
            title: 'Gagal Memperbarui',
            message: err.message || 'Tidak dapat menyimpan status operasional toko.'
          });
        }
      } finally {
        toggleBtn.disabled = false;
      }
    });
  });
</script>
@endpush
