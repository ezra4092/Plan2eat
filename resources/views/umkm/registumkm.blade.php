@extends('layouts.main')

@section('title', 'Pendaftaran Mitra UMKM — Plan2Eat')
@section('hide_nav', true)
@section('body_class', 'auth-shell')

@section('content')
@php
  $isMitraStep = request('step') === 'mitra';
@endphp

{{-- =======================================================================
     TAHAP 1: DAFTAR AKUN BARU UMKM (Regist Page UMKM)
     ======================================================================= --}}
<main id="umkmAccountStep" class="login-page-user {{ $isMitraStep ? 'is-hidden' : '' }}">
  <section class="authentication">
    {{-- Brand Header Plan2Eat --}}
    <div class="brand-header">
      <div class="plan2eat-brand-lockup" aria-label="Plan2Eat - Plan It, Order It, Irit">
        <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="7" width="40" height="36" rx="10" fill="#05371F"/>
          <rect x="13" y="3" width="4" height="8" rx="2" fill="#05371F"/>
          <rect x="29" y="3" width="4" height="8" rx="2" fill="#05371F"/>
          <rect x="14.2" y="4.2" width="1.6" height="5.6" rx="0.8" fill="#FFFFFF"/>
          <rect x="30.2" y="4.2" width="1.6" height="5.6" rx="0.8" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="11.5" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="6.8" fill="#E8702A"/>
        </svg>
        <div class="brand-wordmark-col">
          <div class="brand-wordmark">
            <span class="word-dark">Plan</span><span class="word-orange">2</span><span class="word-dark">Eat</span>
          </div>
          <span class="brand-tagline">PLAN IT, ORDER IT, IRIT</span>
        </div>
      </div>
    </div>

    {{-- Heading --}}
    <div class="auth-heading">
      <h1 class="text-wrapper">Daftar Akun Baru</h1>
      <p class="subtitle">Silakan masukkan detail akun Anda di bawah ini</p>
    </div>

    {{-- Form Daftar Akun UMKM --}}
    <form class="auth-form" action="{{ url('/otpumkm') }}" method="GET">
      <div class="form-group-custom">
        <label for="umkm_name" class="label">Nama Lengkap</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="text" id="umkm_name" name="name" class="container-2" placeholder="Nama lengkap" value="{{ old('name') }}" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_email" class="label">Email</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="3"/>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="email" id="umkm_email" name="email" class="container-2" placeholder="joe@gmail.com" value="{{ old('email') }}" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_password" class="label">Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="password" id="umkm_password" name="password" class="container-2" placeholder="***************" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="umkm_password_confirmation" class="label">Konfirmasi Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input type="password" id="umkm_password_confirmation" name="password_confirmation" class="container-2" placeholder="***************" required>
          </div>
        </div>
      </div>

      <button type="submit" class="action-primary">
        <span class="text-wrapper-4">Lanjutkan</span>
      </button>
    </form>

    <div class="auth-card-bottom-line"></div>
  </section>

  <div class="container-wrapper" style="flex-direction: column; gap: 6px;">
    <p class="belum-punya-akun">
      Sudah Punya Akun?
      <a href="{{ url('/') }}" class="text-wrapper-5">Login</a>
    </p>
    <p class="belum-punya-akun">
      <a href="?step=mitra" class="text-wrapper-5" id="switchToMitraForm">Lanjut isi Profil Warung &rarr;</a>
    </p>
  </div>
</main>

{{-- =======================================================================
     TAHAP 2: PENDAFTARAN MITRA DAPUR — 1. PROFIL WARUNG (Pendaftaran Mitra)
     ======================================================================= --}}
<main id="umkmMitraStep" class="mitra-reg-page {{ $isMitraStep ? '' : 'is-hidden' }}">
  <div class="mitra-reg-container">
    {{-- Top Bar --}}
    <header class="mitra-topbar">
      <a href="?step=akun" class="otp-back-btn" id="backToAkunStep" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
      </a>

      <div class="otp-topbar-brand">
        <svg width="28" height="28" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="6" width="40" height="37" rx="10" fill="#05371F"/>
          <rect x="13" y="2" width="4" height="8" rx="2" fill="#FFFFFF"/>
          <rect x="29" y="2" width="4" height="8" rx="2" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="11" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="6.5" fill="#E8702A"/>
        </svg>
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
          <span class="step-label">2. Operasi</span>
        </div>
        <div class="mitra-step-item">
          <div class="step-bar"></div>
          <span class="step-label">3. Finansial</span>
        </div>
      </div>
    </section>

    {{-- Form Card Sage Green: 1. Profil Warung --}}
    <form action="{{ url('/umkm/profil') }}" method="GET" class="mitra-form-wrapper">
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
            <input type="text" id="ktp_name" name="ktp_name" class="mitra-input" placeholder="Contoh: Siti Rahmawati" required>
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
            <input type="text" id="store_name" name="store_name" class="mitra-input" placeholder="Contoh: Dapur Sehat Bu Siti" required>
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
              <input type="tel" id="whatsapp_number" name="whatsapp_number" class="mitra-input" placeholder="812-3456-7890" required>
            </div>
          </div>
        </div>

        {{-- Alamat Lengkap Pengambilan --}}
        <div class="mitra-field-group">
          <label for="pickup_address" class="mitra-label">Alamat Lengkap Pengambilan</label>
          <div class="mitra-textarea-box">
            <textarea id="pickup_address" name="pickup_address" rows="3" class="mitra-textarea" placeholder="Jl. Anggrek No. 14, RT 03/RW 02, Sukajadi..." required></textarea>
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
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const akunStep = document.getElementById('umkmAccountStep');
    const mitraStep = document.getElementById('umkmMitraStep');
    const switchToMitra = document.getElementById('switchToMitraForm');
    const backToAkun = document.getElementById('backToAkunStep');

    if (switchToMitra) {
      switchToMitra.addEventListener('click', (e) => {
        e.preventDefault();
        akunStep.classList.add('is-hidden');
        mitraStep.classList.remove('is-hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    if (backToAkun) {
      backToAkun.addEventListener('click', (e) => {
        e.preventDefault();
        mitraStep.classList.add('is-hidden');
        akunStep.classList.remove('is-hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  });
</script>
@endpush