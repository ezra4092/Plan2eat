@extends('layouts.main')

@php
  $activeMenu = 'saldo';
@endphp

@section('title', 'Keuangan Mitra - Plan2Eat')
@section('page_badge', 'Keuangan Mitra')
@section('body_class', 'page-umkm-keuangan')

@section('content')
<div class="keu-page">
  {{-- 1. Header Banner Keuangan + Tombol Cetak Laporan --}}
  <section class="keu-hero-card">
    <div class="keu-hero-text">
      <span class="keu-hero-pill">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
          <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
        </svg>
        <span>Keuangan Mitra Plan2eat</span>
      </span>
      <h1 class="keu-hero-title">Saldo &amp; Pencairan Dana</h1>
      <p class="keu-hero-sub">Pembayaran pelanggan diproses melalui Plan2Eat (biaya admin 5%) dan ditahan sementara hingga Anda mencairkannya.</p>
    </div>

    <div class="keu-hero-actions">
      <a
        href="{{ route('umkm.laporan_keuangan-cetak', array_filter(['tanggal_awal' => $tglAwal, 'tanggal_akhir' => $tglAkhir, 'status' => $status])) }}"
        class="keu-btn-print"
      >
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polyline points="6 9 6 2 18 2 18 9"/>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
          <rect x="6" y="14" width="12" height="8"/>
        </svg>
        <span>Cetak Laporan Keuangan</span>
      </a>
    </div>
  </section>

  {{-- 2. Grid Utama (Mobile: Saldo -> Log -> Info Plan2Eat; Desktop: 2 Kolom) --}}
  <div class="keu-grid">
    {{-- Kartu Ringkasan Saldo + Saldo Tertahan + Cairkan Dana --}}
    <section class="keu-sage-card keu-area-saldo" aria-label="Ringkasan Saldo UMKM">
      <div class="keu-balance-hero">
        <div class="keu-balance-top">
          <span class="keu-balance-label">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="2" y="5" width="20" height="14" rx="2"/>
              <path d="M16 12h4"/>
            </svg>
            <span>TOTAL SALDO UMKM</span>
          </span>

          <button
            type="button"
            id="keuToggleBalanceBtn"
            class="keu-eye-btn"
            aria-label="Sembunyikan atau tampilkan nominal saldo"
            aria-pressed="false"
          >
            <svg id="keuEyeIconShow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg id="keuEyeIconHide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>
        </div>

        <div class="keu-balance-amount-row">
          <span class="keu-currency">Rp</span>
          <strong
            id="keuTotalSaldoText"
            class="keu-balance-value js-keu-maskable"
            data-original="{{ number_format($totalSaldo, 0, ',', '.') }}"
            data-masked="•••••••"
          >{{ number_format($totalSaldo, 0, ',', '.') }}</strong>
        </div>

        <div class="keu-sync-pill">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="23 4 23 10 17 10"/>
            <polyline points="1 20 1 14 7 14"/>
            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
          </svg>
          <span>Gabungan saldo milik UMKM dan saldo tertahan pesanan.</span>
        </div>
      </div>

      {{-- Split Card: Saldo Milik UMKM & Saldo Tertahan --}}
      <div class="keu-split-cards">
        <div class="keu-sub-card">
          <div class="keu-sub-label">
            <span class="keu-dot is-green"></span>
            <span>Saldo Milik UMKM</span>
          </div>
          <strong
            class="keu-sub-amount js-keu-maskable"
            data-original="Rp {{ number_format($saldoUmkm, 0, ',', '.') }}"
            data-masked="Rp ••••••"
          >Rp {{ number_format($saldoUmkm, 0, ',', '.') }}</strong>
          <span class="keu-sub-note">Sudah masuk ke saldo toko</span>
        </div>

        <div class="keu-sub-card">
          <div class="keu-sub-label">
            <span class="keu-dot is-brown"></span>
            <span>Saldo Tertahan</span>
          </div>
          <strong
            class="keu-sub-amount js-keu-maskable"
            data-original="Rp {{ number_format($saldoTertahan, 0, ',', '.') }}"
            data-masked="Rp ••••••"
          >Rp {{ number_format($saldoTertahan, 0, ',', '.') }}</strong>
          <span class="keu-sub-note">{{ $jumlahPesananTertahan }} pesanan belum dicairkan</span>
        </div>
      </div>

      {{-- Tombol Cairkan Dana --}}
      <form action="{{ route('umkm.laporan_keuangan-cairkan') }}" method="POST" style="margin: 0;">
        @csrf
        <button
          type="submit"
          class="keu-btn-disburse"
          {{ $saldoTertahan <= 0 ? 'disabled' : '' }}
          data-confirm="Cairkan seluruh saldo tertahan sebesar Rp {{ number_format($saldoTertahan, 0, ',', '.') }} ke Saldo UMKM Anda?"
          data-confirm-title="Cairkan Saldo Tertahan"
          data-confirm-ok="Ya, Cairkan"
          data-confirm-danger="false"
        >
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 2v20"/>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
          <span>
            {{ $saldoTertahan > 0 ? 'Cairkan Dana (Rp ' . number_format($saldoTertahan, 0, ',', '.') . ')' : 'Belum Ada Saldo Tertahan' }}
          </span>
        </button>
      </form>
    </section>

    {{-- Log Uang Masuk --}}
    <section class="keu-sage-card keu-log-card keu-area-log" aria-label="Log Uang Masuk">
      <div class="keu-log-header">
        <h2 class="keu-log-title">Log Uang Masuk</h2>

        <button type="button" id="keuToggleRangeBtn" class="keu-btn-range">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <span>Rentang</span>
        </button>
      </div>

      {{-- Filter Drawer (Rentang Tanggal & Status) --}}
      @php
        $hasFilterActive = ! empty($tglAwal) || ! empty($tglAkhir) || ($status && $status !== 'semua');
      @endphp
      <form
        id="keuFilterDrawer"
        action="{{ route('umkm.laporan_keuangan') }}"
        method="GET"
        class="keu-filter-drawer {{ $hasFilterActive ? '' : 'is-hidden' }}"
      >
        <div class="keu-filter-fields">
          <div class="keu-filter-field">
            <label for="tanggal_awal">Dari Tanggal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ $tglAwal }}">
          </div>
          <div class="keu-filter-field">
            <label for="tanggal_akhir">Sampai Tanggal</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tglAkhir }}">
          </div>
          <div class="keu-filter-field">
            <label for="status">Status</label>
            <select id="status" name="status">
              <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
              <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="berlangsung" {{ $status === 'berlangsung' ? 'selected' : '' }}>Sedang Berjalan</option>
            </select>
          </div>
        </div>

        <div class="keu-filter-actions">
          <a href="{{ route('umkm.laporan_keuangan') }}" class="keu-btn-reset">Reset</a>
          <button type="submit" class="keu-btn-apply">Terapkan Filter</button>
        </div>
      </form>

      <div class="keu-log-list">
        @forelse ($pesanan as $item)
          @php
            $isFlashSale = $item->detailPesanan->contains(fn ($detail) => ! is_null($detail->id_flash_sale));
            $totalPorsi = max(1, (int) $item->detailPesanan->sum('kuantitas'));
            $statusLower = strtolower((string) $item->status_pesanan);
            $netNominal = (float) ($item->biaya_admin ?? 0) > 0
                ? max(0.0, (float) $item->total_pembayaran - (float) $item->biaya_admin)
                : ((float) $item->detailPesanan->sum('subtotal') ?: (float) $item->total_pembayaran);

            $dateText = '-';
            if ($item->tgl_mulai) {
                if ($item->tgl_mulai->isToday()) {
                    $dateText = 'Hari ini, ' . $item->tgl_mulai->format('H:i') . ' WIB';
                } elseif ($item->tgl_mulai->isYesterday()) {
                    $dateText = 'Kemarin, ' . $item->tgl_mulai->format('H:i') . ' WIB';
                } else {
                    $dateText = $item->tgl_mulai->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
            }

            if ($statusLower === 'dibatalkan') {
                $statusBadgeText = 'Dibatalkan';
                $statusBadgeClass = 'is-cancelled';
            } elseif ($item->dana_dicairkan) {
                $statusBadgeText = 'Sudah Dicairkan';
                $statusBadgeClass = 'is-settled';
            } else {
                $statusBadgeText = 'Tertahan';
                $statusBadgeClass = 'is-pending';
            }
          @endphp

          <article class="keu-log-item">
            <div class="keu-log-item-left">
              <span class="keu-log-icon {{ $isFlashSale ? 'is-flash' : 'is-catering' }}" aria-hidden="true">
                @if ($isFlashSale)
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                  </svg>
                @else
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11h18"/>
                    <path d="M5 11a7 7 0 0 1 14 0"/>
                    <path d="M4 15h16"/>
                    <path d="M5 15v2a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-2"/>
                  </svg>
                @endif
              </span>

              <div class="keu-log-info">
                <strong>{{ $isFlashSale ? 'Night Flash Rescue' : 'Pesanan Katering' }}</strong>
                <span class="keu-log-portion">{{ $totalPorsi }} Porsi &bull; {{ $item->metode_pembayaran ?: 'Plan2Eat' }}</span>
                <span class="keu-log-time">{{ $dateText }}</span>
              </div>
            </div>

            <div class="keu-log-item-right">
              <strong class="keu-log-amount {{ $statusLower === 'dibatalkan' ? 'is-cancelled' : '' }}">
                {{ $statusLower === 'dibatalkan' ? '' : '+' }}Rp {{ number_format($netNominal, 0, ',', '.') }}
              </strong>
              <span class="keu-log-status {{ $statusBadgeClass }}">{{ $statusBadgeText }}</span>
            </div>
          </article>
        @empty
          <div class="keu-log-empty">
            <p>Belum ada transaksi uang masuk untuk rentang/status yang dipilih.</p>
          </div>
        @endforelse
      </div>
    </section>

    {{-- Banner Pembayaran Terpusat Plan2Eat --}}
    <div class="keu-admin-banner keu-area-admin">
      <span class="keu-admin-badge-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2l2.2 1.7 2.8-.3 1.1 2.6 2.4 1.5-.3 2.8L22 12l-1.8 2.2.3 2.8-2.4 1.5-1.1 2.6-2.8-.3L12 22l-2.2-1.7-2.8.3-1.1-2.6-2.4-1.5.3-2.8L2 12l1.8-2.2-.3-2.8 2.4-1.5 1.1-2.6 2.8.3L12 2zm-1.1 13.4l5.2-5.2-1.4-1.4-3.8 3.8-1.8-1.8-1.4 1.4 3.2 3.2z"/>
        </svg>
      </span>
      <div class="keu-admin-banner-text">
        <strong>Pembayaran Terpusat Plan2Eat (Biaya Admin 5%)</strong>
        <p>Setiap pembayaran pelanggan diterima melalui sistem Plan2Eat, lalu saldo bersih milik mitra dapat Anda cairkan kapan saja lewat tombol Cairkan Dana.</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Toggle Sembunyikan / Tampilkan Saldo
  const toggleBalanceBtn = document.getElementById('keuToggleBalanceBtn');
  const eyeShow = document.getElementById('keuEyeIconShow');
  const eyeHide = document.getElementById('keuEyeIconHide');
  const maskableEls = document.querySelectorAll('.js-keu-maskable');
  let isMasked = false;

  if (toggleBalanceBtn) {
    toggleBalanceBtn.addEventListener('click', function () {
      isMasked = !isMasked;
      toggleBalanceBtn.setAttribute('aria-pressed', isMasked ? 'true' : 'false');
      maskableEls.forEach(function (el) {
        el.textContent = isMasked ? el.dataset.masked : el.dataset.original;
      });
      if (eyeShow && eyeHide) {
        eyeShow.style.display = isMasked ? 'none' : 'block';
        eyeHide.style.display = isMasked ? 'block' : 'none';
      }
    });
  }

  // Toggle Filter Drawer Rentang Tanggal
  const toggleRangeBtn = document.getElementById('keuToggleRangeBtn');
  const filterDrawer = document.getElementById('keuFilterDrawer');
  if (toggleRangeBtn && filterDrawer) {
    toggleRangeBtn.addEventListener('click', function () {
      filterDrawer.classList.toggle('is-hidden');
    });
  }
});
</script>
@endpush
