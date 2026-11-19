@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Pembayaran Pesanan - Plan2Eat')
@section('page_badge', 'Pembayaran')

@push('styles')
<style>
  .pay-page-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 10px 16px 50px;
    font-family: var(--font-inter, sans-serif);
  }

  /* Step Wizard */
  .pay-steps-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
  }
  .pay-step-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #9CA3AF;
  }
  .pay-step-item.is-done {
    color: #10B981;
  }
  .pay-step-item.is-active {
    color: #16442B;
    font-weight: 700;
  }
  .pay-step-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #E5E7EB;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }
  .pay-step-item.is-done .pay-step-circle {
    background: #10B981;
    color: #FFFFFF;
  }
  .pay-step-item.is-active .pay-step-circle {
    background: #16442B;
    color: #FFFFFF;
  }
  .pay-step-sep {
    width: 32px;
    height: 2px;
    background: #E5E7EB;
  }
  .pay-step-sep.is-done {
    background: #10B981;
  }

  /* Common Card */
  .pay-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    padding: 22px;
    margin-bottom: 20px;
  }
  .pay-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #1F2937;
    margin: 0 0 16px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  /* Summary Row */
  .pay-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed #E5E7EB;
  }
  .pay-item-row:last-of-type {
    border-bottom: 1px solid #E5E7EB;
  }
  .pay-item-name {
    font-size: 14px;
    font-weight: 700;
    color: #1F2937;
  }
  .pay-item-meta {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
  }
  .pay-item-price {
    font-size: 14px;
    font-weight: 800;
    color: #1F2937;
  }
  .pay-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
  }
  .pay-total-amount {
    font-size: 20px;
    font-weight: 800;
    color: #16442B;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  /* Form Controls */
  .pay-form-group {
    margin-bottom: 16px;
  }
  .pay-form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
  }
  .pay-form-select, .pay-form-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #D1D5DB;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    color: #1F2937;
    background: #FFFFFF;
    outline: none;
    transition: all 0.2s;
  }
  .pay-form-select:focus, .pay-form-input:focus {
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
  }

  /* Payment Method Radio Cards */
  .pay-method-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 18px;
  }
  @media (max-width: 640px) {
    .pay-method-grid {
      grid-template-columns: 1fr;
    }
  }

  .pay-method-card {
    border: 2px solid #E5E7EB;
    border-radius: 14px;
    padding: 14px;
    cursor: pointer;
    background: #F9FAFB;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
  }
  .pay-method-card.is-selected {
    border-color: #16442B;
    background: #F0FDF4;
    box-shadow: 0 4px 12px rgba(22, 68, 43, 0.1);
  }
  .pay-method-card input[type="radio"] {
    accent-color: #16442B;
    width: 18px;
    height: 18px;
    cursor: pointer;
  }
  .pay-method-label {
    font-size: 13.5px;
    font-weight: 700;
    color: #1F2937;
  }

  /* Detail Box QRIS */
  .pay-detail-box {
    display: none;
    border-radius: 16px;
    padding: 20px;
    animation: fadeIn 0.25s ease;
  }
  .pay-detail-box.is-visible {
    display: block;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Box QRIS */
  .pay-qris-container {
    background: #FFFFFF;
    border: 2px solid #E5E7EB;
    border-radius: 18px;
    padding: 24px;
    text-align: center;
    max-width: 380px;
    margin: 0 auto;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
  }
  .pay-qris-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #1F2937;
    padding-bottom: 10px;
    margin-bottom: 16px;
  }
  .pay-qris-logo-text {
    font-weight: 900;
    font-size: 20px;
    letter-spacing: 1px;
    color: #DC2626;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .pay-qris-merchant-info {
    margin-bottom: 14px;
  }
  .pay-qris-merchant-info h4 {
    font-size: 15px;
    font-weight: 800;
    margin: 0;
    color: #1F2937;
  }
  .pay-qris-merchant-info span {
    font-size: 11px;
    color: #6B7280;
  }
  .pay-qr-code-box {
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 14px;
    padding: 16px;
    display: inline-block;
    margin-bottom: 12px;
  }
  .pay-qr-svg {
    width: 200px;
    height: 200px;
    display: block;
  }
  .pay-qris-footer-text {
    font-size: 11px;
    color: #6B7280;
    line-height: 1.4;
    margin: 0;
  }

  /* Box Transfer Bank */
  .pay-bank-box {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 20px;
  }
  .pay-bank-row {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  .pay-bank-name {
    font-size: 14px;
    font-weight: 800;
    color: #16442B;
  }
  .pay-bank-norek {
    font-size: 16px;
    font-weight: 800;
    font-family: monospace;
    letter-spacing: 1px;
    color: #1F2937;
  }
  .pay-btn-copy {
    background: #E8F5E9;
    border: none;
    color: #16442B;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
  }
  .pay-btn-copy:hover {
    background: #C8E6C9;
  }

  /* Box Tunai */
  .pay-cash-box {
    background: #FEF3C7;
    border: 1px solid #FCD34D;
    color: #92400E;
    border-radius: 14px;
    padding: 16px 20px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  /* Submit Buttons */
  .pay-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-top: 24px;
  }
  .pay-btn-back {
    padding: 11px 22px;
    border-radius: 999px;
    border: 1px solid #D1D5DB;
    background: #FFFFFF;
    color: #4B5563;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
  }
  .pay-btn-submit {
    padding: 14px 32px;
    border-radius: 999px;
    border: none;
    background: #16442B;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(22, 68, 43, 0.25);
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
  }
  .pay-btn-submit:hover {
    background: #0f301e;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(22, 68, 43, 0.35);
  }

  /* Modal Simulasi Pembayaran (Jeda 5 Detik) */
  .pay-modal-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .pay-modal-backdrop.is-open {
    display: flex;
  }

  .pay-modal-card {
    background: #FFFFFF;
    border-radius: 24px;
    padding: 32px 28px;
    max-width: 420px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    animation: popModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes popModal {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }

  .pay-spinner-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 5px solid #E5E7EB;
    border-top-color: #10B981;
    margin: 0 auto 20px;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .pay-success-icon {
    display: none;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #10B981;
    color: #FFFFFF;
    font-size: 36px;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }

  .pay-modal-title {
    font-size: 18px;
    font-weight: 800;
    color: #1F2937;
    margin: 0 0 6px;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .pay-modal-sub {
    font-size: 13px;
    color: #6B7280;
    margin: 0 0 20px;
  }

  /* Progress Bar 5 Detik */
  .pay-progress-track {
    width: 100%;
    height: 8px;
    background: #E5E7EB;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 16px;
  }
  .pay-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #10B981 0%, #059669 100%);
    border-radius: 999px;
    transition: width 0.1s linear;
  }

  .pay-countdown-pill {
    display: inline-block;
    background: #E8F5E9;
    color: #16442B;
    font-size: 12.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    margin-bottom: 20px;
  }

  .pay-btn-skip {
    background: none;
    border: 1px solid #D1D5DB;
    color: #6B7280;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
  }
  .pay-btn-skip:hover {
    background: #F3F4F6;
    color: #111827;
  }
</style>
@endpush

@section('content')
<div class="pay-page-wrap">

  <!-- Step Wizard Indicator -->
  <div class="pay-steps-bar">
    <div class="pay-step-item is-done">
      <div class="pay-step-circle"><i class="fa-solid fa-check"></i></div>
      <span>Keranjang</span>
    </div>
    <div class="pay-step-sep is-done"></div>
    <div class="pay-step-item is-active">
      <div class="pay-step-circle">2</div>
      <span>Pembayaran</span>
    </div>
    <div class="pay-step-sep"></div>
    <div class="pay-step-item">
      <div class="pay-step-circle">3</div>
      <span>Selesai</span>
    </div>
  </div>

  @if(session('error'))
    <div style="background: #FEF2F2; color: #991B1B; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 13.5px; border: 1px solid #FECACA;">
      <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background: #FEF2F2; color: #991B1B; padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 13.5px; border: 1px solid #FECACA;">
      <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="payOrderForm" action="{{ route('pelanggan.pesanan-create') }}" method="POST">
    @csrf
    <input type="hidden" name="id_umkm" value="{{ $umkm->id_umkm }}">
    <input type="hidden" name="tgl_mulai" value="{{ $cart['tgl_mulai'] ?? now()->toDateString() }}">
    @if(!empty($cart['tgl_selesai']))
      <input type="hidden" name="tgl_selesai" value="{{ $cart['tgl_selesai'] }}">
    @endif
    <input type="hidden" name="status_pesanan" value="persiapan">

    <!-- 1. Ringkasan Pesanan Card -->
    <div class="pay-card">
      <div class="pay-card-title">
        <span><i class="fa-solid fa-clipboard-list" style="color: #10B981;"></i> Ringkasan Pesanan</span>
        <span style="font-size: 12px; font-weight: normal; color: #6B7280;">Dapur: <strong>{{ $umkm->nama_vendor }}</strong></span>
      </div>

      <div>
        @foreach($cart['items'] as $index => $item)
          @php $subtotal = $item['harga_satuan'] * $item['kuantitas']; @endphp

          <!-- Hidden inputs pesanan -->
          <input type="hidden" name="items[{{ $index }}][id_menu]" value="{{ $item['id_menu'] }}">
          <input type="hidden" name="items[{{ $index }}][kuantitas]" value="{{ $item['kuantitas'] }}">
          <input type="hidden" name="items[{{ $index }}][waktu]" value="{{ $item['waktu'] }}">
          @if(!empty($item['id_flash_sale']))
            <input type="hidden" name="items[{{ $index }}][id_flash_sale]" value="{{ $item['id_flash_sale'] }}">
          @endif

          <div class="pay-item-row">
            <div>
              <div class="pay-item-name">{{ $item['nama_makanan'] }}</div>
              <div class="pay-item-meta">
                {{ $item['kuantitas'] }}x @ Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                &bull; <i class="fa-regular fa-clock" style="color: #10B981;"></i> Jam makan: <strong>{{ $item['waktu'] }} WIB</strong>
              </div>
            </div>
            <div class="pay-item-price">
              Rp {{ number_format($subtotal, 0, ',', '.') }}
            </div>
          </div>
        @endforeach

        <div class="pay-total-row">
          <span style="font-size: 14px; font-weight: 700; color: #374151;">Total Pembayaran</span>
          <span class="pay-total-amount">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    <!-- 2. Detail Pengiriman Card -->
    <div class="pay-card">
      <div class="pay-card-title">
        <span><i class="fa-solid fa-truck-fast" style="color: #10B981;"></i> Pengiriman &amp; Jadwal</span>
      </div>

      <div class="pay-form-group">
        <label for="metode_pengiriman" class="pay-form-label">Metode Pengiriman</label>
        <select name="metode_pengiriman" id="metode_pengiriman" class="pay-form-select" required>
          <option value="Antar Kurir">Antar Kurir ke Alamat Saya</option>
          <option value="Ambil di Warung">Ambil Sendiri di Warung / Dapur (Self Pick-up)</option>
        </select>
      </div>

      <div class="pay-form-group" style="margin-bottom: 0;">
        <label for="jadwal_pengiriman" class="pay-form-label">Jadwal Pengantaran Pertama</label>
        <input type="datetime-local"
          name="jadwal_pengiriman"
          id="jadwal_pengiriman"
          class="pay-form-input"
          value="{{ ($cart['tgl_mulai'] ?? now()->toDateString()) . 'T' . ($cart['items'][0]['waktu'] ?? '12:00') }}"
          required>
      </div>
    </div>

    <!-- 3. Metode Pembayaran Card -->
    <div class="pay-card">
      <div class="pay-card-title">
        <span><i class="fa-solid fa-wallet" style="color: #10B981;"></i> Pilih Metode Pembayaran</span>
      </div>

      <!-- Radio cards -->
      <div class="pay-method-grid">
        <label class="pay-method-card is-selected" id="optQRIS" onclick="selectMethod('QRIS')">
          <input type="radio" name="metode_pembayaran" value="QRIS" checked>
          <i class="fa-solid fa-qrcode" style="font-size: 20px; color: #16442B;"></i>
          <div>
            <div class="pay-method-label">QRIS</div>
            <small style="font-size: 11px; color: #6B7280;">E-Wallet &amp; m-Banking</small>
          </div>
        </label>

        <label class="pay-method-card" id="optBank" onclick="selectMethod('Transfer Bank')">
          <input type="radio" name="metode_pembayaran" value="Transfer Bank">
          <i class="fa-solid fa-building-columns" style="font-size: 20px; color: #16442B;"></i>
          <div>
            <div class="pay-method-label">Transfer Bank</div>
            <small style="font-size: 11px; color: #6B7280;">BCA, Mandiri, BRI</small>
          </div>
        </label>

        <label class="pay-method-card" id="optTunai" onclick="selectMethod('Tunai')">
          <input type="radio" name="metode_pembayaran" value="Tunai">
          <i class="fa-solid fa-money-bill-wave" style="font-size: 20px; color: #16442B;"></i>
          <div>
            <div class="pay-method-label">Tunai / COD</div>
            <small style="font-size: 11px; color: #6B7280;">Bayar saat tiba</small>
          </div>
        </label>
      </div>

      <!-- Container Tampilan QRIS (Fake QR Code) -->
      <div id="boxQRIS" class="pay-detail-box is-visible">
        <div class="pay-qris-container">
          <div class="pay-qris-header">
            <span class="pay-qris-logo-text">QRIS</span>
            <span style="font-size: 11px; font-weight: 800; color: #1F2937;">GPN NASIONAL</span>
          </div>

          <div class="pay-qris-merchant-info">
            <h4>PLAN2EAT - {{ $umkm->nama_vendor }}</h4>
            <span>NMID: ID1020261118001 &bull; Katering Sehat</span>
          </div>

          <!-- Fake QR SVG -->
          <div class="pay-qr-code-box">
            <svg class="pay-qr-svg" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="200" height="200" fill="white"/>
              <!-- Marker Top Left -->
              <rect x="15" y="15" width="45" height="45" rx="6" fill="#16442B"/>
              <rect x="23" y="23" width="29" height="29" rx="3" fill="white"/>
              <rect x="29" y="29" width="17" height="17" rx="2" fill="#16442B"/>

              <!-- Marker Top Right -->
              <rect x="140" y="15" width="45" height="45" rx="6" fill="#16442B"/>
              <rect x="148" y="23" width="29" height="29" rx="3" fill="white"/>
              <rect x="154" y="29" width="17" height="17" rx="2" fill="#16442B"/>

              <!-- Marker Bottom Left -->
              <rect x="15" y="140" width="45" height="45" rx="6" fill="#16442B"/>
              <rect x="23" y="148" width="29" height="29" rx="3" fill="white"/>
              <rect x="29" y="154" width="17" height="17" rx="2" fill="#16442B"/>

              <!-- Fake Matrix Modules -->
              <rect x="70" y="20" width="10" height="10" fill="#16442B"/>
              <rect x="90" y="20" width="20" height="10" fill="#16442B"/>
              <rect x="120" y="20" width="10" height="10" fill="#16442B"/>
              <rect x="70" y="40" width="30" height="10" fill="#16442B"/>
              <rect x="110" y="40" width="20" height="10" fill="#16442B"/>

              <rect x="20" y="70" width="20" height="10" fill="#16442B"/>
              <rect x="50" y="70" width="10" height="10" fill="#16442B"/>
              <rect x="70" y="70" width="60" height="10" fill="#16442B"/>
              <rect x="140" y="70" width="40" height="10" fill="#16442B"/>

              <rect x="20" y="90" width="10" height="20" fill="#16442B"/>
              <rect x="40" y="90" width="20" height="10" fill="#16442B"/>
              <rect x="140" y="90" width="20" height="20" fill="#16442B"/>
              <rect x="170" y="90" width="10" height="10" fill="#16442B"/>

              <!-- Center Logo Box -->
              <rect x="80" y="80" width="40" height="40" rx="8" fill="#10B981"/>
              <circle cx="100" cy="100" r="12" fill="white"/>
              <path d="M96 100L99 103L105 97" stroke="#16442B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

              <!-- Bottom Matrix -->
              <rect x="70" y="140" width="20" height="20" fill="#16442B"/>
              <rect x="100" y="140" width="40" height="10" fill="#16442B"/>
              <rect x="150" y="140" width="30" height="20" fill="#16442B"/>
              <rect x="70" y="170" width="40" height="10" fill="#16442B"/>
              <rect x="120" y="160" width="20" height="20" fill="#16442B"/>
            </svg>
          </div>

          <div style="font-size: 14px; font-weight: 800; color: #16442B; margin-bottom: 6px;">
            Total: Rp {{ number_format($totalHarga, 0, ',', '.') }}
          </div>
          <p class="pay-qris-footer-text">
            Buka aplikasi m-Banking atau E-Wallet apa saja (GoPay, OVO, Dana, ShopeePay, BCA, dll.), lalu arahkan kamera ke kode QR di atas.
          </p>
        </div>
      </div>

      <!-- Container Tampilan Transfer Bank -->
      <div id="boxBank" class="pay-detail-box">
        <div class="pay-bank-box">
          <h4 style="font-size: 14px; font-weight: 800; color: #1F2937; margin: 0 0 12px;">
            Daftar Nomor Rekening Tujuan:
          </h4>

          <div class="pay-bank-row">
            <div>
              <div class="pay-bank-name"><i class="fa-solid fa-building-columns"></i> Bank Central Asia (BCA)</div>
              <div class="pay-bank-norek" id="rekBCA">8801 2345 6789 001</div>
              <small style="color: #6B7280;">a.n. PT PLAN2EAT MITRA SEHAT</small>
            </div>
            <button type="button" class="pay-btn-copy" onclick="copyRek('880123456789001', this)">
              <i class="fa-regular fa-copy"></i> Salin
            </button>
          </div>

          <div class="pay-bank-row">
            <div>
              <div class="pay-bank-name"><i class="fa-solid fa-building-columns"></i> Bank Mandiri</div>
              <div class="pay-bank-norek" id="rekMandiri">1330 0987 6543 210</div>
              <small style="color: #6B7280;">a.n. PT PLAN2EAT MITRA SEHAT</small>
            </div>
            <button type="button" class="pay-btn-copy" onclick="copyRek('133009876543210', this)">
              <i class="fa-regular fa-copy"></i> Salin
            </button>
          </div>

          <div style="background: #ECFDF5; border: 1px solid #A7F3D0; padding: 10px 14px; border-radius: 10px; font-size: 12px; color: #065F46;">
            <i class="fa-solid fa-circle-check"></i> Transfer tepat sebesar <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong> agar pesanan terverifikasi otomatis.
          </div>
        </div>
      </div>

      <!-- Container Tampilan Tunai -->
      <div id="boxTunai" class="pay-detail-box">
        <div class="pay-cash-box">
          <i class="fa-solid fa-hand-holding-dollar" style="font-size: 24px;"></i>
          <div>
            <strong>Pembayaran Tunai di Tempat:</strong><br>
            Siapkan uang pas sebesar <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong> untuk diserahkan ke kurir saat makanan tiba.
          </div>
        </div>
      </div>
    </div>

    <!-- Actions Bar -->
    <div class="pay-actions-bar">
      <a href="{{ route('pelanggan.keranjang') }}" class="pay-btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
      </a>

      <!-- Tombol Trigger Pembayaran dengan Simulasi Jeda 5 Detik -->
      <button type="button" id="btnTriggerPay" class="pay-btn-submit" onclick="startSimulatedPayment()">
        <span>Konfirmasi &amp; Bayar Sekarang</span>
        <i class="fa-solid fa-shield-check"></i>
      </button>
    </div>
  </form>

</div>

<!-- MODAL SIMULASI PEMBAYARAN OTOMATIS (Jeda 5 Detik) -->
<div class="pay-modal-backdrop" id="simModal">
  <div class="pay-modal-card">
    <div class="pay-spinner-circle" id="paySpinner"></div>
    <div class="pay-success-icon" id="paySuccessIcon">
      <i class="fa-solid fa-check"></i>
    </div>

    <h3 class="pay-modal-title" id="simTitle">Memverifikasi Pembayaran...</h3>
    <p class="pay-modal-sub" id="simSub">
      Mohon tunggu sebentar, sistem sedang mendeteksi konfirmasi pembayaran Anda...
    </p>

    <!-- Progress Bar 5 Detik -->
    <div class="pay-progress-track">
      <div class="pay-progress-bar" id="simProgressBar"></div>
    </div>

    <div class="pay-countdown-pill" id="simCountdownPill">
      Verifikasi otomatis dalam <span id="secCount">5</span> detik...
    </div>

    <div>
      <button type="button" class="pay-btn-skip" onclick="finishPaymentNow()">
        <i class="fa-solid fa-forward-step"></i> Saya Sudah Bayar (Lewati)
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function selectMethod(method) {
    document.querySelectorAll('.pay-method-card').forEach(c => c.classList.remove('is-selected'));
    document.querySelectorAll('.pay-detail-box').forEach(b => b.classList.remove('is-visible'));

    if (method === 'QRIS') {
      document.getElementById('optQRIS').classList.add('is-selected');
      document.getElementById('boxQRIS').classList.add('is-visible');
    } else if (method === 'Transfer Bank') {
      document.getElementById('optBank').classList.add('is-selected');
      document.getElementById('boxBank').classList.add('is-visible');
    } else if (method === 'Tunai') {
      document.getElementById('optTunai').classList.add('is-selected');
      document.getElementById('boxTunai').classList.add('is-visible');
    }
  }

  function copyRek(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin!';
      btn.style.background = '#10B981';
      btn.style.color = '#FFFFFF';
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.style.background = '';
        btn.style.color = '';
      }, 2000);
    });
  }

  // ==========================================
  // SIMULASI PEMBAYARAN DENGAN JEDA 5 DETIK
  // ==========================================
  let simInterval = null;
  let remainingSeconds = 5;

  function startSimulatedPayment() {
    const modal = document.getElementById('simModal');
    modal.classList.add('is-open');

    remainingSeconds = 5;
    document.getElementById('secCount').textContent = remainingSeconds;
    const pBar = document.getElementById('simProgressBar');
    pBar.style.width = '0%';

    let progress = 0;
    const totalDuration = 5000; // 5000 ms = 5 detik
    const intervalTime = 100;
    const step = 100 / (totalDuration / intervalTime);

    simInterval = setInterval(() => {
      progress += step;
      if (progress > 100) progress = 100;
      pBar.style.width = progress + '%';

      const secLeft = Math.ceil((100 - progress) / 20);
      document.getElementById('secCount').textContent = Math.max(1, secLeft);

      if (progress >= 100) {
        clearInterval(simInterval);
        finishPaymentNow();
      }
    }, intervalTime);
  }

  function finishPaymentNow() {
    if (simInterval) clearInterval(simInterval);

    // Ubah tampilan modal menjadi sukses
    document.getElementById('paySpinner').style.display = 'none';
    const sIcon = document.getElementById('paySuccessIcon');
    sIcon.style.display = 'flex';

    document.getElementById('simTitle').textContent = 'Pembayaran Lunas!';
    document.getElementById('simTitle').style.color = '#10B981';
    document.getElementById('simSub').textContent = 'Pembayaran berhasil diverifikasi. Membuat pesanan katering Anda...';
    document.getElementById('simCountdownPill').style.display = 'none';
    document.getElementById('simProgressBar').style.width = '100%';

    // Submit form setelah sedikit delay agar animasi checkmark terlihat
    setTimeout(() => {
      document.getElementById('payOrderForm').submit();
    }, 700);
  }
</script>
@endpush


@push('scripts')
<script>
  function selectMethod(method) {
    document.querySelectorAll('.pay-method-card').forEach(c => c.classList.remove('is-selected'));
    document.querySelectorAll('.pay-detail-box').forEach(b => b.classList.remove('is-visible'));

    if (method === 'QRIS') {
      document.getElementById('optQRIS').classList.add('is-selected');
      document.getElementById('boxQRIS').classList.add('is-visible');
    } else if (method === 'Transfer Bank') {
      document.getElementById('optBank').classList.add('is-selected');
      document.getElementById('boxBank').classList.add('is-visible');
    } else if (method === 'Tunai') {
      document.getElementById('optTunai').classList.add('is-selected');
      document.getElementById('boxTunai').classList.add('is-visible');
    }
  }

  function copyRek(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin!';
      btn.style.background = '#10B981';
      btn.style.color = '#FFFFFF';
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.style.background = '';
        btn.style.color = '';
      }, 2000);
    });
  }

  // ==========================================
  // SIMULASI PEMBAYARAN DENGAN JEDA 5 DETIK
  // ==========================================
  let simInterval = null;
  let remainingSeconds = 5;

  function startSimulatedPayment() {
    const modal = document.getElementById('simModal');
    modal.classList.add('is-open');

    remainingSeconds = 5;
    document.getElementById('secCount').textContent = remainingSeconds;
    const pBar = document.getElementById('simProgressBar');
    pBar.style.width = '0%';

    let progress = 0;
    const totalDuration = 5000; // 5000 ms = 5 detik
    const intervalTime = 100;
    const step = 100 / (totalDuration / intervalTime);

    simInterval = setInterval(() => {
      progress += step;
      if (progress > 100) progress = 100;
      pBar.style.width = progress + '%';

      const secLeft = Math.ceil((100 - progress) / 20);
      document.getElementById('secCount').textContent = Math.max(1, secLeft);

      if (progress >= 100) {
        clearInterval(simInterval);
        finishPaymentNow();
      }
    }, intervalTime);
  }

  function finishPaymentNow() {
    if (simInterval) clearInterval(simInterval);

    // Ubah tampilan modal menjadi sukses
    document.getElementById('paySpinner').style.display = 'none';
    const sIcon = document.getElementById('paySuccessIcon');
    sIcon.style.display = 'flex';

    document.getElementById('simTitle').textContent = 'Pembayaran Lunas!';
    document.getElementById('simTitle').style.color = '#10B981';
    document.getElementById('simSub').textContent = 'Pembayaran berhasil diverifikasi. Membuat pesanan katering Anda...';
    document.getElementById('simCountdownPill').style.display = 'none';
    document.getElementById('simProgressBar').style.width = '100%';

    // Submit form setelah sedikit delay agar animasi checkmark terlihat
    setTimeout(() => {
      document.getElementById('payOrderForm').submit();
    }, 700);
  }
</script>
@endpush