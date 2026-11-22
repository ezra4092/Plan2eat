@extends('layouts.main', ['activeMenu' => 'laporan'])

@section('title', 'Laporan Keuangan & Transaksi - Plan2Eat Admin')
@section('page_badge', 'Laporan Platform')

@push('styles')
<style>
  .lap-split-card {
    background: #FFFFFF;
    border: 1px solid #EAE6D7;
    border-radius: 18px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .lap-split-head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .lap-split-title {
    margin: 0;
    font-family: var(--font-sans);
    font-size: 16px;
    font-weight: 800;
    color: #1F2937;
  }

  .lap-split-sub {
    font-size: 13px;
    color: #6B7280;
  }

  .lap-progress-bar {
    height: 12px;
    width: 100%;
    border-radius: 999px;
    background: #F3EFE0;
    overflow: hidden;
    display: flex;
  }

  .lap-bar-paket {
    background: #16442B;
    height: 100%;
    transition: width 0.3s ease;
  }

  .lap-bar-satuan {
    background: #E8922D;
    height: 100%;
    transition: width 0.3s ease;
  }

  .lap-compare-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
  }

  .lap-type-box {
    background: #FAF8F0;
    border: 1px solid #EAE4D0;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .lap-type-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }

  .lap-type-name {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-sans);
    font-size: 14px;
    font-weight: 700;
    color: #1F2937;
  }

  .lap-type-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .lap-type-pct {
    font-family: var(--font-sans);
    font-size: 14px;
    font-weight: 800;
    color: #16442B;
  }

  .lap-type-count {
    font-family: var(--font-sans);
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
  }

  .lap-type-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding-top: 8px;
    border-top: 1px dashed #DED8C4;
    font-size: 12.5px;
    color: #4B5563;
  }

  .lap-type-stats strong {
    display: block;
    margin-top: 2px;
    color: #16442B;
    font-size: 13.5px;
  }

  @media (max-width: 720px) {
    .lap-compare-grid {
      grid-template-columns: 1fr;
    }

    .lap-split-card {
      padding: 16px;
    }
  }
</style>
@endpush

@section('content')
@php
  $activePeriode = $periode ?? 'semua';
  $periodeChips = [
      'semua' => 'Semua Waktu',
      'hari_ini' => 'Hari Ini',
      '7_hari' => '7 Hari Terakhir',
      'bulan_ini' => 'Bulan Ini',
  ];
@endphp

<div class="aum-page">
  <header class="aum-page-header">
    <div class="aum-title-row" style="justify-content: space-between;">
      <div>
        <h1 class="aum-page-title">Laporan Transaksi &amp; Pendapatan</h1>
        <p class="aum-page-subtitle">Ringkasan GMV pesanan selesai, pendapatan bersih platform, dan tipe pemesanan.</p>
      </div>

      <a href="{{ route('admin.laporan.csv', ['periode' => $activePeriode]) }}"
         class="dm-btn-primary"
         style="text-decoration: none; white-space: nowrap;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        <span>Unduh CSV</span>
      </a>
    </div>

    <div class="aum-filter-chips" role="group" aria-label="Filter periode laporan">
      @foreach ($periodeChips as $key => $label)
        <a href="{{ route('admin.laporan', ['periode' => $key]) }}"
           class="aum-chip {{ $activePeriode === $key ? 'is-active' : '' }}"
           style="text-decoration: none; display: inline-flex; align-items: center;">
          {{ $label }}
        </a>
      @endforeach
    </div>
  </header>

  {{-- 1. 4 KPI Utama --}}
  <div class="aum-kpi-grid-4">
    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Total GMV (Selesai)</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value" style="font-size: 22px;">Rp {{ number_format((float) $totalGmv, 0, ',', '.') }}</div>
      <span style="font-size: 12px; color: #6B7280;">Total nilai transaksi selesai</span>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Pendapatan Aplikasi</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value" style="font-size: 22px; color: #16442B;">Rp {{ number_format((float) $pendapatanAplikasi, 0, ',', '.') }}</div>
      <span style="font-size: 12px; color: #6B7280;">Pemasukan bersih biaya admin</span>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Order Selesai</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value">{{ number_format((int) $totalOrder, 0, ',', '.') }}</div>
      <span style="font-size: 12px; color: #6B7280;">Transaksi tuntas</span>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Rata-rata / Order</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value" style="font-size: 22px;">Rp {{ number_format((float) $rataRataOrder, 0, ',', '.') }}</div>
      <span style="font-size: 12px; color: #6B7280;">Nilai rata-rata pesanan</span>
    </article>
  </div>

  {{-- 2. Perbandingan Tipe Order --}}
  <section class="lap-split-card" aria-label="Perbandingan Tipe Order">
    <div class="lap-split-head">
      <div>
        <h2 class="lap-split-title">Perbandingan Tipe Order</h2>
        <span class="lap-split-sub">Paket katering (Meal Plan) vs pembelian satuan / harian (status selesai)</span>
      </div>
      <span class="aum-panel-count">{{ $totalOrder }} transaksi selesai</span>
    </div>

    <div class="lap-progress-bar" aria-label="Proporsi tipe order">
      <div class="lap-bar-paket" style="width: {{ $totalOrder > 0 ? $paketPct : 50 }}%;" title="Paket Katering {{ $paketPct }}%"></div>
      <div class="lap-bar-satuan" style="width: {{ $totalOrder > 0 ? $satuanPct : 50 }}%;" title="Satuan / Harian {{ $satuanPct }}%"></div>
    </div>

    <div class="lap-compare-grid">
      <div class="lap-type-box">
        <div class="lap-type-head">
          <span class="lap-type-name">
            <span class="lap-type-dot" style="background: #16442B;"></span>
            <span>Paket Katering (Meal Plan)</span>
          </span>
          <span class="lap-type-pct">{{ number_format((float) $paketPct, 1, ',', '.') }}%</span>
        </div>
        <div class="lap-type-count">{{ number_format((int) $paketCount, 0, ',', '.') }} pesanan</div>
        <div class="lap-type-stats">
          <div>
            Total GMV
            <strong>Rp {{ number_format((float) $paketGmv, 0, ',', '.') }}</strong>
          </div>
          <div>
            Biaya Admin
            <strong>Rp {{ number_format((float) $paketAdmin, 0, ',', '.') }}</strong>
          </div>
        </div>
      </div>

      <div class="lap-type-box">
        <div class="lap-type-head">
          <span class="lap-type-name">
            <span class="lap-type-dot" style="background: #E8922D;"></span>
            <span>Pembelian Satuan / Harian</span>
          </span>
          <span class="lap-type-pct" style="color: #B45309;">{{ number_format((float) $satuanPct, 1, ',', '.') }}%</span>
        </div>
        <div class="lap-type-count">{{ number_format((int) $satuanCount, 0, ',', '.') }} pesanan</div>
        <div class="lap-type-stats">
          <div>
            Total GMV
            <strong>Rp {{ number_format((float) $satuanGmv, 0, ',', '.') }}</strong>
          </div>
          <div>
            Biaya Admin
            <strong>Rp {{ number_format((float) $satuanAdmin, 0, ',', '.') }}</strong>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 3. Tabel Rekap per Mitra UMKM --}}
  <section class="aum-panel-card">
    <div class="aum-panel-head">
      <h2 class="aum-panel-title">Rekapitulasi per Mitra UMKM</h2>
      <span class="aum-panel-count">{{ $rekapUmkm->count() }} mitra</span>
    </div>

    <div class="aum-table-responsive">
      <table class="aum-table">
        <thead>
          <tr>
            <th>Mitra UMKM</th>
            <th>Paket Katering</th>
            <th>Satuan / Harian</th>
            <th>Total Order</th>
            <th>Total GMV</th>
            <th>Pendapatan Aplikasi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($rekapUmkm as $row)
            @php
              $words = preg_split('/\s+/', trim((string) $row->nama_vendor)) ?: ['UM'];
              $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
            @endphp
            <tr>
              <td>
                <div class="aum-biz-cell">
                  <span class="aum-initials-badge">{{ $initials }}</span>
                  <div class="aum-biz-text">
                    <strong>{{ $row->nama_vendor }}</strong>
                  </div>
                </div>
              </td>
              <td>{{ number_format((int) $row->jumlah_paket, 0, ',', '.') }}</td>
              <td>{{ number_format((int) $row->jumlah_satuan, 0, ',', '.') }}</td>
              <td><strong>{{ number_format((int) $row->total_order, 0, ',', '.') }}</strong></td>
              <td>Rp {{ number_format((float) $row->total_gmv, 0, ',', '.') }}</td>
              <td><strong style="color: #16442B;">Rp {{ number_format((float) $row->total_biaya_admin, 0, ',', '.') }}</strong></td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 28px; color: #6B7280;">
                Belum ada transaksi berstatus selesai pada periode ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  {{-- 4. Rincian Transaksi Selesai Terbaru --}}
  <section class="aum-panel-card">
    <div class="aum-panel-head" style="flex-wrap: wrap; gap: 12px;">
      <h2 class="aum-panel-title">Rincian Transaksi Selesai Terbaru</h2>
      <div class="aum-search-box" style="max-width: 280px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input type="text" id="lapTrxSearch" class="aum-search-input" placeholder="Cari order, pelanggan, UMKM...">
      </div>
    </div>

    <div class="aum-table-responsive">
      <table class="aum-table" id="lapTrxTable">
        <thead>
          <tr>
            <th>ID Pesanan</th>
            <th>Pelanggan</th>
            <th>Mitra UMKM</th>
            <th>Tipe Order</th>
            <th>Total GMV</th>
            <th>Biaya Admin</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($daftarTransaksi as $trx)
            @php
              $isPaket = ! empty($trx->id_plan);
              $kode = '#P2E-' . str_pad((string) $trx->id_pesanan, 4, '0', STR_PAD_LEFT);
            @endphp
            <tr data-search="{{ strtolower($kode . ' ' . $trx->nama_pelanggan . ' ' . $trx->nama_vendor) }}">
              <td class="aum-trx-id">{{ $kode }}</td>
              <td>{{ $trx->nama_pelanggan }}</td>
              <td>{{ $trx->nama_vendor }}</td>
              <td>
                <span class="aum-status-pill {{ $isPaket ? 'is-active' : 'is-pending' }}">
                  {{ $isPaket ? 'Paket Katering' : 'Satuan / Harian' }}
                </span>
              </td>
              <td>Rp {{ number_format((float) $trx->total_pembayaran, 0, ',', '.') }}</td>
              <td><strong style="color: #16442B;">Rp {{ number_format((float) $trx->biaya_admin, 0, ',', '.') }}</strong></td>
              <td>{{ $trx->tgl_mulai ? \Carbon\Carbon::parse($trx->tgl_mulai)->translatedFormat('d M Y') : '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; padding: 28px; color: #6B7280;">
                Belum ada rincian transaksi selesai.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('lapTrxSearch');
  const table = document.getElementById('lapTrxTable');
  if (!input || !table) return;

  input.addEventListener('input', function () {
    const q = input.value.trim().toLowerCase();
    table.querySelectorAll('tbody tr[data-search]').forEach(function (tr) {
      const match = q === '' || (tr.dataset.search || '').includes(q);
      tr.style.display = match ? '' : 'none';
    });
  });
});
</script>
@endpush
