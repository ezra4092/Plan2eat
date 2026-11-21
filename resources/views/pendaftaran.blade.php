<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pendaftaran Mitra UMKM — Plan2Eat</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
  <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>
<body class="auth-shell">

<main id="umkmMitraStep" class="mitra-reg-page">
  <div class="mitra-reg-container">
    {{-- Top Bar --}}
    <header class="mitra-topbar">
      <a href="{{ route('login_page') }}" class="otp-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
      </a>

      <div class="otp-topbar-brand">
        <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat" style="height: 28px; width: auto;">
        <span class="otp-topbar-title">Pendaftaran Mitra</span>
      </div>
    </header>

    {{-- Banner Header & Progress 3 Tahap --}}
    <section class="mitra-hero-banner">
      <div class="mitra-zero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#05371F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
          <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
        </svg>
        <span>KEMITRAAN ZERO-WASTE</span>
      </div>

      <h1 class="mitra-hero-title">Registrasi Mitra Dapur</h1>
      <p class="mitra-hero-desc">
        Lengkapi profil warung untuk bergabung dalam rantai kuliner sirkular bersama ribuan pelanggan Plan2Eat.
      </p>

      {{-- Stepper Bar --}}
      <div class="mitra-stepper">
        <div class="mitra-step-item is-active">
          <div class="step-bar"></div>
          <span class="step-label">1. Profil</span>
        </div>
        <div class="mitra-step-item">
          <div class="step-bar"></div>
          <span class="step-label">2. Verifikasi</span>
        </div>
        <div class="mitra-step-item">
          <div class="step-bar"></div>
          <span class="step-label">3. Mulai Jualan</span>
        </div>
      </div>
    </section>

    {{-- Form Card Sage Green: 1. Profil Warung --}}
    <form action="{{ route('umkm.pendaftaran-store') }}" method="POST" class="mitra-form-wrapper">
      @csrf
      <section class="mitra-sage-card">
        <div class="mitra-section-head">
          <div class="mitra-store-icon-circle">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l2-5h14l2 5"/>
              <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/>
              <path d="M5 12v8h14v-8"/>
              <path d="M10 20v-5h4v5"/>
            </svg>
          </div>
          <div>
            <h2 class="mitra-section-title">1. Profil Warung</h2>
            <p class="mitra-section-sub">Identitas pemilik &amp; lokasi fisik usaha</p>
          </div>
        </div>

        {{-- Nama Lengkap Sesuai KTP --}}
        <div class="mitra-field-group">
          <label for="ktp_name" class="mitra-label">Nama Lengkap Sesuai KTP</label>
          <div class="mitra-input-box">
            <svg class="mitra-field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#555D56" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2"/>
              <circle cx="8" cy="12" r="2"/>
              <line x1="14" y1="10" x2="19" y2="10"/>
              <line x1="14" y1="14" x2="18" y2="14"/>
            </svg>
            <input type="text" id="ktp_name" name="ktp_name" class="mitra-input" placeholder="Contoh: Siti Rahmawati" value="{{ old('ktp_name', Auth::user()?->nama_lengkap) }}" required>
          </div>
        </div>

        {{-- Nama Warung / Dapur --}}
        <div class="mitra-field-group">
          <label for="store_name" class="mitra-label">Nama Warung / Dapur</label>
          <div class="mitra-input-box">
            <svg class="mitra-field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#555D56" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
              <path d="M7 2v20"/>
              <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
            </svg>
            <input type="text" id="store_name" name="store_name" class="mitra-input" placeholder="Contoh: Dapur Sehat Bu Siti" value="{{ old('store_name') }}" required>
          </div>
        </div>

        {{-- Nomor WhatsApp Usaha --}}
        <div class="mitra-field-group">
          <label for="whatsapp_number" class="mitra-label">Nomor WhatsApp Usaha</label>
          <div class="mitra-phone-split">
            <div class="mitra-country-prefix">
              {{-- Bendera Indonesia --}}
              <svg width="18" height="13" viewBox="0 0 18 13" fill="none" style="border-radius:2px;overflow:hidden;flex-shrink:0;">
                <rect width="18" height="6.5" fill="#E11D48"/>
                <rect y="6.5" width="18" height="6.5" fill="#FFFFFF"/>
              </svg>
              <span>+62</span>
            </div>
            <div class="mitra-phone-number-box">
              <input type="tel" id="whatsapp_number" name="whatsapp_number" class="mitra-input" placeholder="812-3456-7890" value="{{ old('whatsapp_number') }}" required>
            </div>
          </div>
        </div>

        {{-- Alamat Lengkap Pengambilan --}}
        <div class="mitra-field-group">
          <label for="pickup_address" class="mitra-label">Alamat Lengkap Pengambilan</label>
          <div class="mitra-textarea-box">
            <textarea id="pickup_address" name="pickup_address" rows="3" class="mitra-textarea" placeholder="Jl. Anggrek No. 14, RT 03/RW 02, Dramaga..." required>{{ old('pickup_address') }}</textarea>
          </div>
        </div>

        {{-- Pin Titik Jemput Kurir --}}
        <div class="mitra-field-group">
          <div class="mitra-pin-header">
            <span class="mitra-label">Pin Titik Jemput Kurir</span>
            <button type="button" class="btn-pin-accurate">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="12" r="8"/>
                <circle cx="12" cy="12" r="2.5"/>
                <line x1="12" y1="2" x2="12" y2="5"/>
                <line x1="12" y1="19" x2="12" y2="22"/>
                <line x1="2" y1="12" x2="5" y2="12"/>
                <line x1="19" y1="12" x2="22" y2="12"/>
              </svg>
              <span>Akurat</span>
            </button>
          </div>

          <div class="mitra-map-preview" role="region" aria-label="Peta Pin Titik Jemput Kurir">
            <div class="map-grid-pattern"></div>
            <span class="map-poi-tag poi-left">NuArt Sculpture Park</span>
            <span class="map-poi-tag poi-right">ITB Ganesha</span>
            <div class="map-center-pin">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="#05371F" stroke="#FFFFFF" stroke-width="1.8">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3" fill="#E8702A" stroke="none"/>
              </svg>
            </div>
          </div>
        </div>
      </section>

      {{-- Sticky / Bottom Action Bar --}}
      <footer class="mitra-bottom-bar">
        <div class="mitra-fee-info">
          <span class="mitra-fee-label">Biaya Pendaftaran</span>
          <span class="mitra-fee-value">GRATIS</span>
        </div>

        <button type="submit" class="btn-submit-mitra">
          <span>Kirim Pendaftaran</span>
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>
      </footer>
    </form>
  </div>
</main>

@include('partials.popup_notification')
</body>
</html>
