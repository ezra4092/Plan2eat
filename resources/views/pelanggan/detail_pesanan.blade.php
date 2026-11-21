@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan . ' - Plan2Eat')
@section('page_badge', 'Detail Pesanan')

@push('styles')
<style>
  .detail-page-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 10px 16px 50px;
    font-family: var(--font-inter, sans-serif);
  }

  /* Step Wizard */
  .detail-steps-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
  }
  .detail-step-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #10B981;
  }
  .detail-step-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #10B981;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }
  .detail-step-sep {
    width: 32px;
    height: 2px;
    background: #10B981;
  }

  .detail-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    padding: 26px;
    margin-bottom: 20px;
  }

  .detail-success-banner {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: #065F46;
  }
  .detail-success-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #10B981;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }

  .detail-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 12px;
  }
  .detail-title h2 {
    font-size: 18px;
    font-weight: 800;
    color: #1F2937;
    margin: 2px 0 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .detail-badge-status {
    background: #E8F5E9;
    color: #16442B;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .detail-badge-status::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10B981;
  }

  .detail-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
  }
  @media (max-width: 640px) {
    .detail-meta-grid {
      grid-template-columns: 1fr;
    }
  }

  .detail-meta-item span {
    font-size: 12px;
    color: #6B7280;
    display: block;
    margin-bottom: 2px;
  }
  .detail-meta-item strong {
    font-size: 13.5px;
    color: #1F2937;
    font-weight: 700;
  }

  .detail-menu-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #E5E7EB;
  }
  .detail-menu-name {
    font-size: 14px;
    font-weight: 700;
    color: #1F2937;
  }
  .detail-menu-sub {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
  }
  .detail-menu-price {
    font-size: 14px;
    font-weight: 800;
    color: #16442B;
  }

  .detail-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 18px;
  }
  .detail-total-amount {
    font-size: 20px;
    font-weight: 800;
    color: #16442B;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .detail-btn-home {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 30px;
    border-radius: 999px;
    background: #16442B;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
  }
  .detail-btn-home:hover {
    background: #0f301e;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(22, 68, 43, 0.25);
  }
</style>
@endpush

@section('content')
<div class="detail-page-wrap">

  <!-- Step Wizard Indicator (Selesai) -->
  <div class="detail-steps-bar">
    <div class="detail-step-item">
      <div class="detail-step-circle"><i class="fa-solid fa-check"></i></div>
      <span>Keranjang</span>
    </div>
    <div class="detail-step-sep"></div>
    <div class="detail-step-item">
      <div class="detail-step-circle"><i class="fa-solid fa-check"></i></div>
      <span>Pembayaran</span>
    </div>
    <div class="detail-step-sep"></div>
    <div class="detail-step-item">
      <div class="detail-step-circle"><i class="fa-solid fa-check"></i></div>
      <span>Pesanan Selesai</span>
    </div>
  </div>

  <!-- Success Notification -->
  <div class="detail-success-banner">
    <div class="detail-success-icon">
      <i class="fa-solid fa-check"></i>
    </div>
    <div>
      <h3 style="margin: 0; font-size: 15px; font-weight: 800;">Pesanan Berhasil Dibuat &amp; Pembayaran Lunas!</h3>
      <p style="margin: 2px 0 0; font-size: 12.5px; opacity: 0.9;">
        Dapur mitra sedang mempersiapkan menu katering sehat Anda sesuai jadwal pengantaran yang telah ditentukan.
      </p>
    </div>
  </div>

  <div class="detail-card">
    <div class="detail-header-row">
      <div class="detail-title">
        <span style="font-size: 11.5px; color: #6B7280; font-weight: 700; text-transform: uppercase;">ID Pesanan #{{ $pesanan->id_pesanan }}</span>
        <h2>{{ $pesanan->umkm->nama_vendor ?? 'Mitra Plan2Eat' }}</h2>
      </div>
      <div class="detail-badge-status">
        Status: {{ $pesanan->status_pesanan }}
      </div>
    </div>

    <!-- Info Meta Pengiriman & Pembayaran -->
    <div class="detail-meta-grid">
      <div class="detail-meta-item">
        <span>Metode Pembayaran:</span>
        <strong><i class="fa-solid fa-wallet" style="color: #10B981;"></i> {{ $pesanan->metode_pembayaran }} (LUNAS)</strong>
      </div>
      <div class="detail-meta-item">
        <span>Metode Pengiriman:</span>
        <strong><i class="fa-solid fa-truck" style="color: #10B981;"></i> {{ $pesanan->metode_pengiriman }}</strong>
      </div>
      <div class="detail-meta-item">
        <span>Tanggal Mulai:</span>
        <strong><i class="fa-regular fa-calendar" style="color: #10B981;"></i> {{ \Carbon\Carbon::parse($pesanan->tgl_mulai)->format('d M Y') }}</strong>
      </div>
      <div class="detail-meta-item">
        <span>Jadwal Pengantaran Pertama:</span>
        <strong>
          <i class="fa-regular fa-clock" style="color: #10B981;"></i>
          {{ $pesanan->jadwal_pengiriman ? \Carbon\Carbon::parse($pesanan->jadwal_pengiriman)->format('d M Y, H:i') . ' WIB' : '-' }}
        </strong>
      </div>
    </div>

    <!-- Rincian Menu Dipesan -->
    <h3 style="font-size: 14px; font-weight: 800; color: #1F2937; margin: 0 0 10px;">
      Rincian Menu &amp; Jam Makan
    </h3>

    @foreach($pesanan->detailPesanan as $detail)
      <div class="detail-menu-row">
        <div>
          <div class="detail-menu-name">{{ $detail->menu->nama_makanan ?? 'Menu Katering' }}</div>
          <div class="detail-menu-sub">
            Jumlah: <strong>{{ $detail->kuantitas }}x</strong> &bull;
            Jam Makan: <strong><i class="fa-regular fa-clock" style="color: #10B981;"></i> {{ $detail->waktu ?? '-' }} WIB</strong>
          </div>
        </div>
        <div class="detail-menu-price">
          Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
        </div>
      </div>
    @endforeach

    @php
      $subtotalDetail = $pesanan->detailPesanan->sum('subtotal');
      $biayaAdminDetail = (float) ($pesanan->biaya_admin ?? max(0, (float) $pesanan->total_pembayaran - $subtotalDetail));
    @endphp
    <div style="border-top: 1px dashed #E5E7EB; padding-top: 10px; margin-top: 6px; display: flex; flex-direction: column; gap: 5px; font-size: 13px; color: #4B5563;">
      <div style="display: flex; justify-content: space-between;">
        <span>Subtotal Menu</span>
        <strong>Rp {{ number_format($subtotalDetail, 0, ',', '.') }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Biaya Admin (5%)</span>
        <strong>Rp {{ number_format($biayaAdminDetail, 0, ',', '.') }}</strong>
      </div>
    </div>

    <div class="detail-total-row">
      <span style="font-size: 14px; font-weight: 700; color: #374151;">Total Pembayaran</span>
      <span class="detail-total-amount">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</span>
    </div>
  </div>

  <div style="text-align: center; margin-top: 20px;">
    <a href="{{ route('pelanggan.beranda') }}" class="detail-btn-home">
      <i class="fa-solid fa-house"></i>
      <span>Kembali ke Beranda</span>
    </a>
  </div>

</div>
@endsection
