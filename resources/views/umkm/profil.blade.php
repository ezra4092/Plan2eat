@extends('layouts.main', ['activeMenu' => 'profil', 'role' => 'umkm'])

@section('title', 'Profil UMKM - Plan2Eat')
@section('page_badge', 'Dapur Mitra')

@section('content')
<div class="umkm-profile-page">

  <!-- 1. Kartu Identitas Warung -->
  <section class="store-profile-card" aria-label="Identitas Warung">
    <div class="store-profile-main">
      <div class="store-profile-top">
        <!-- Foto Profil + Badge Centang Hijau -->
        <div class="store-avatar-wrap">
          <img class="store-avatar-img" src="{{ asset('img/profile-umkm.jpg') }}" alt="Warteg Sifren">
          <span class="store-verified-badge" title="Mitra Terverifikasi">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </span>
        </div>

        <!-- Detail Nama Warung, Pemilik & Rating -->
        <div class="store-info-col">
          <div class="store-name-row">
            <h1 class="store-name">Warteg Sifren</h1>
            <span class="badge-verified-partner">Mitra Terverifikasi</span>
          </div>

          <p class="store-owner">Pak Sifren (Pemilik)</p>

          <div class="store-rating-row">
            <span class="badge-rating">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
              </svg>
              <span>4.8 / 5.0</span>
            </span>
            <span class="review-count">142 Ulasan Mahasiswa</span>
          </div>
        </div>
      </div>

      <!-- Alamat Warung -->
      <div class="store-address-row">
        <svg class="address-pin-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <span>Jl. Kumbang No. 12, Sekitar Kampus IPB Cilibende</span>
      </div>
    </div>

    <!-- Tombol Edit Profil Warung -->
    <div class="store-profile-action">
      <a href="{{ url('/umkm/profil/edit') }}" class="btn-edit-store">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
        </svg>
        <span>Edit Profil Warung</span>
      </a>
    </div>
  </section>

  <!-- Grid 2 Kolom di Desktop (1 Kolom di Mobile) -->
  <div class="umkm-profile-grid">

    <!-- 2. Kartu Manajemen Toko (Sage Surface) -->
    <section class="store-management-card" aria-label="Manajemen Toko">
      <div class="profile-section-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <h2 class="profile-section-title">Manajemen Toko</h2>
      </div>

      <div class="management-list">
        <!-- Item 1: Status Buka Toko + Switch BUKA -->
        <div class="management-item-card">
          <div class="management-item-left">
            <div class="profile-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
            </div>
            <div class="management-item-text">
              <span class="management-item-title">Status Buka Toko</span>
              <span class="management-item-sub" id="storeSwitchSub">Menerima Pesanan Katering</span>
            </div>
          </div>

          <button type="button" class="store-switch-btn is-open" id="btnProfileStoreSwitch" aria-pressed="true">
            <span class="switch-label" id="storeSwitchLabel">BUKA</span>
            <span class="switch-thumb"></span>
          </button>
        </div>

        <!-- Item 2: Manajemen Menu -->
        <a href="{{ url('/umkm/manajemen/menu') }}" class="management-item-card is-link">
          <div class="management-item-left">
            <div class="profile-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 11h16a1 1 0 0 1 1 1v.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 12.5V12a1 1 0 0 1 1-1z"></path>
                <path d="M5 11a7 7 0 0 1 14 0"></path>
                <path d="M5 14v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2"></path>
              </svg>
            </div>
            <div class="management-item-text">
              <span class="management-item-title">Manajemen Menu</span>
            </div>
          </div>

          <span class="item-arrow-pill" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </span>
        </a>

        <!-- Item 3: Manajemen Pesanan -->
        <a href="{{ url('/umkm/manajemen/pesanan') }}" class="management-item-card is-link">
          <div class="management-item-left">
            <div class="profile-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                <line x1="9" y1="12" x2="15" y2="12"></line>
                <line x1="9" y1="16" x2="15" y2="16"></line>
              </svg>
            </div>
            <div class="management-item-text">
              <span class="management-item-title">Manajemen Pesanan</span>
            </div>
          </div>

          <span class="item-arrow-pill" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </span>
        </a>
      </div>
    </section>

    <!-- 3. Kartu Jadwal & Jam Operasional -->
    <section class="store-schedule-card" aria-label="Jadwal dan Jam Operasional">
      <div class="profile-section-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <h2 class="profile-section-title">Jadwal &amp; Jam Operasional</h2>
      </div>

      <div class="schedule-list">
        <!-- Jadwal 1: Jam Buka Warung -->
        <div class="schedule-item-card">
          <div class="schedule-item-left">
            <div class="profile-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
            </div>
            <div class="schedule-item-info">
              <span class="schedule-item-label">Jam Buka Warung</span>
              <div class="schedule-time-row">
                <span class="schedule-time-val">06.00 - 20.00</span>
                <span class="schedule-time-tz">WIB</span>
              </div>
            </div>
          </div>

          <button type="button" class="btn-schedule-edit">Ubah</button>
        </div>

        <!-- Jadwal 2: Jam Persiapan Masak H-1 -->
        <div class="schedule-item-card">
          <div class="schedule-item-left">
            <div class="profile-icon-box">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 13h12v3a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4v-3z"></path>
                <path d="M18 13l3-2"></path>
                <path d="M9 5v3"></path>
                <path d="M12 4v4"></path>
                <path d="M15 5v3"></path>
              </svg>
            </div>
            <div class="schedule-item-info">
              <span class="schedule-item-label">Jam Persiapan Masak</span>
              <div class="schedule-time-row">
                <span class="schedule-time-val">01.00 - 05.00</span>
                <span class="schedule-time-tz">WIB</span>
              </div>
              <span class="schedule-item-note">Batas konfirmasi pesanan masuk untuk menu besok</span>
            </div>
          </div>

          <button type="button" class="btn-schedule-edit">Ubah</button>
        </div>
      </div>
    </section>

  </div>

</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const switchBtn   = document.getElementById('btnProfileStoreSwitch');
    const switchLabel = document.getElementById('storeSwitchLabel');
    const switchSub   = document.getElementById('storeSwitchSub');

    if (switchBtn && switchLabel && switchSub) {
      switchBtn.addEventListener('click', function () {
        const isOpen = switchBtn.classList.toggle('is-open');
        switchBtn.setAttribute('aria-pressed', isOpen ? 'true' : 'false');
        switchLabel.textContent = isOpen ? 'BUKA' : 'TUTUP';
        switchSub.textContent   = isOpen ? 'Menerima Pesanan Katering' : 'Pesanan Sementara Ditutup';
      });
    }
  });
</script>
@endpush