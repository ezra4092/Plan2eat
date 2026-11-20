@extends('layouts.main')

@section('title', 'Profil Dapur Mitra - Plan2Eat')
@section('page_badge', 'Dapur Mitra')
@section('body_class', 'page-mitra-profil')

@section('content')
<div class="mp-page-container">
    {{-- Notifikasi Flash & Error Validasi --}}
    @if (session('success'))
        <div style="background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13.5px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13.5px; font-weight: 600;">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13.5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="mitraProfileMainForm" action="{{ route('umkm.profil-update', $umkm->id_umkm) }}" method="POST" enctype="multipart/form-data" class="mp-main-form">
        @csrf
        @method('PUT')

        {{-- Hidden inputs yang disinkronkan dari Modal Edit Profil Warung --}}
        <input type="hidden" id="hiddenStoreName" name="nama_vendor" value="{{ old('nama_vendor', $umkm->nama_vendor) }}">
        <input type="hidden" id="hiddenOwnerName" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
        <input type="hidden" id="hiddenOwnerEmail" name="email" value="{{ old('email', $user->email) }}">
        <input type="hidden" id="hiddenStoreAddress" name="alamat_lengkap" value="{{ old('alamat_lengkap', $umkm->alamat_lengkap) }}">
        <input type="hidden" id="hiddenStoreDesc" name="deskripsi_toko" value="{{ old('deskripsi_toko', $umkm->deskripsi_toko) }}">
        <input type="file" id="modalAvatarFileInput" name="foto_toko" accept="image/*" hidden>

        {{-- 1. KARTU IDENTITAS WARUNG --}}
        <section class="mp-card mp-identity-card">
            <div class="mp-identity-top">
                <div class="mp-avatar-box">
                    <img
                        id="mainStoreAvatarImg"
                        src="{{ $umkm->foto_toko ? asset('storage/' . $umkm->foto_toko) : 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=240&q=80' }}"
                        alt="Foto {{ $umkm->nama_vendor }}"
                        class="mp-avatar-img"
                    >
                    @if ($umkm->status_verifikasi)
                        <span class="mp-avatar-verified" title="Mitra Terverifikasi">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </span>
                    @endif
                </div>

                <div class="mp-identity-info">
                    <div class="mp-title-row">
                        <h1 id="displayStoreName" class="mp-store-title">{{ old('nama_vendor', $umkm->nama_vendor) }}</h1>
                        <span class="mp-pill-verified">
                            {{ $umkm->status_verifikasi ? 'Mitra Terverifikasi' : 'Menunggu Verifikasi' }}
                        </span>
                    </div>

                    <p class="mp-owner-subtitle">
                        <span id="displayOwnerName">{{ old('nama_lengkap', $user->nama_lengkap) }}</span> (Pemilik) &bull;
                        <span id="displayOwnerEmail">{{ old('email', $user->email) }}</span>
                    </p>

                    @if (old('deskripsi_toko', $umkm->deskripsi_toko))
                        <p id="displayStoreDesc" style="font-size: 12.5px; color: #4B5563; margin: 4px 0 8px;">
                            {{ old('deskripsi_toko', $umkm->deskripsi_toko) }}
                        </p>
                    @else
                        <p id="displayStoreDesc" style="font-size: 12.5px; color: #9CA3AF; margin: 4px 0 8px; display: none;"></p>
                    @endif

                    <div class="mp-rating-row">
                        <span class="mp-pill-rating">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span>{{ number_format((float) ($umkm->rating ?? 0), 1) }} / 5.0</span>
                        </span>
                        <span class="mp-review-count">Saldo: Rp {{ number_format($umkm->saldo ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <hr class="mp-soft-divider">

            {{-- Baris Mitra Berkelanjutan & ID Mitra --}}
            <div class="mp-partner-meta-row">
                <div class="mp-eco-since">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                        <path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/>
                        <path d="m14 16-3 3 3 3"/>
                        <path d="M8.293 13.596 7.196 9.5 3.1 10.598"/>
                        <path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843"/>
                        <path d="m13.378 9.633 4.096 1.098 1.097-4.096"/>
                    </svg>
                    <span>Mitra Berkelanjutan Plan2Eat</span>
                </div>

                <span class="mp-partner-id-pill">ID: #UMKM-{{ str_pad((string) $umkm->id_umkm, 3, '0', STR_PAD_LEFT) }}</span>
            </div>

            {{-- Kotak Alamat Toko --}}
            <div class="mp-address-box">
                <span class="mp-address-pin">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </span>
                <p id="displayStoreAddress" class="mp-address-text">{{ old('alamat_lengkap', $umkm->alamat_lengkap) }}</p>
            </div>

            {{-- Tombol Buka Dialog Box Edit Profil Warung --}}
            <button type="button" id="btnOpenEditWarung" class="mp-btn-edit-warung">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
                <span>Edit Detail &amp; Foto Warung</span>
            </button>
        </section>

        {{-- GRID 2 KOLOM RESPONSIF (Desktop) / STACK (Mobile) --}}
        <div class="mp-sections-grid">
            {{-- KOLOM KIRI: JADWAL & JAM OPERASIONAL + MANAJEMEN TOKO --}}
            <div class="mp-col-stack">
                {{-- 2. JADWAL & JAM OPERASIONAL --}}
                <section class="mp-section-block">
                    <div class="mp-section-header">
                        <span class="mp-section-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </span>
                        <h2 class="mp-section-title">Jadwal &amp; Jam Operasional</h2>
                    </div>

                    <div class="mp-subcard-list">
                        @php
                            $currentStatus = old('status_operasional', $umkm->status_operasional ?? 'Buka');
                            $isStoreOpen = strtolower($currentStatus) === 'buka';
                            $jamBukaVal = old('jam_buka', \Carbon\Carbon::parse($umkm->jam_buka ?? '07:00')->format('H:i'));
                            $jamTutupVal = old('jam_tutup', \Carbon\Carbon::parse($umkm->jam_tutup ?? '21:00')->format('H:i'));
                        @endphp

                        {{-- Row 1: Status Buka Toko (Toggle ON / OFF) --}}
                        <div class="mp-white-row-card">
                            <div class="mp-row-left">
                                <span class="mp-cream-icon-box">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                </span>
                                <div class="mp-row-text">
                                    <span class="mp-row-title">Status Buka Toko</span>
                                    <span id="storeStatusSubtext" class="mp-row-sub">
                                        {{ $isStoreOpen ? 'Menerima Pesanan Katering' : 'Toko Sedang Tutup' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Toggle Switch ON/OFF --}}
                            <input type="hidden" id="hiddenStatusOperasional" name="status_operasional" value="{{ $isStoreOpen ? 'Buka' : 'Tutup' }}">
                            <button
                                type="button"
                                id="btnToggleStoreOpen"
                                role="switch"
                                aria-checked="{{ $isStoreOpen ? 'true' : 'false' }}"
                                class="mp-status-switch {{ $isStoreOpen ? 'is-on' : 'is-off' }}"
                            >
                                <span id="storeSwitchLabel" class="mp-switch-text">{{ $isStoreOpen ? 'BUKA' : 'TUTUP' }}</span>
                                <span class="mp-switch-knob"></span>
                            </button>
                        </div>

                        {{-- Row 2: Jam Buka Warung --}}
                        <div class="mp-white-row-card mp-hours-card">
                            <div class="mp-hours-main">
                                <div class="mp-row-left">
                                    <span class="mp-cream-icon-box">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </span>
                                    <div class="mp-row-text">
                                        <span class="mp-row-caption">Jam Buka Warung</span>
                                        <span class="mp-hours-value">
                                            <strong id="displayHoursRange">{{ $jamBukaVal }} - {{ $jamTutupVal }}</strong>
                                            <small>WIB</small>
                                        </span>
                                    </div>
                                </div>

                                <button type="button" id="btnToggleHoursEditor" class="mp-pill-action-btn">Ubah</button>
                            </div>

                            {{-- Drawer Ubah Jam Buka --}}
                            <div id="hoursEditorDrawer" class="mp-hours-drawer is-hidden">
                                <div class="mp-time-inputs">
                                    <input type="time" id="inputJamBuka" name="jam_buka" value="{{ $jamBukaVal }}" class="mp-mini-time-input" required>
                                    <span>s/d</span>
                                    <input type="time" id="inputJamTutup" name="jam_tutup" value="{{ $jamTutupVal }}" class="mp-mini-time-input" required>
                                    <span>WIB</span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Banner Surplus Food --}}
                        <div class="mp-cream-note-box">
                            <span class="mp-note-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </span>
                            <p class="mp-note-text">
                                Flash Sale aktif otomatis setiap hari pukul <strong>20.00 - 22.00 WIB</strong> untuk porsi berlebih dengan diskon hemat mahasiswa.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- 4. MANAJEMEN TOKO --}}
                <section class="mp-sage-panel">
                    <div class="mp-section-header">
                        <span class="mp-section-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </span>
                        <h2 class="mp-section-title">Manajemen Toko</h2>
                    </div>

                    <div class="mp-subcard-list">
                        <a href="{{ route('umkm.menu') }}" class="mp-white-row-card is-clickable">
                            <div class="mp-row-left">
                                <span class="mp-cream-icon-box">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="8" y1="6" x2="21" y2="6"/>
                                        <line x1="8" y1="12" x2="21" y2="12"/>
                                        <line x1="8" y1="18" x2="21" y2="18"/>
                                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                                    </svg>
                                </span>
                                <span class="mp-row-title">Manajemen Menu &amp; Stok</span>
                            </div>
                            <span class="mp-arrow-right">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </span>
                        </a>

                        <a href="{{ route('umkm.pesanan') }}" class="mp-white-row-card is-clickable">
                            <div class="mp-row-left">
                                <span class="mp-cream-icon-box">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </span>
                                <span class="mp-row-title">Manajemen Pesanan Katering</span>
                            </div>
                            <span class="mp-arrow-right">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                </section>
            </div>

            {{-- KOLOM KANAN: METODE PEMBAYARAN + TOMBOL SIMPAN & KELUAR --}}
            <div class="mp-col-stack">
                {{-- 3. METODE PEMBAYARAN --}}
                @php
                    $rawPayments = old('metode_pembayaran', $umkm->metode_pembayaran ?? ['QRIS']);
                    if (is_string($rawPayments)) {
                        $decoded = json_decode($rawPayments, true);
                        $activePayments = is_array($decoded) ? $decoded : [$rawPayments];
                    } else {
                        $activePayments = (array) $rawPayments;
                    }
                    $activeCount = count($activePayments);
                @endphp

                <section class="mp-section-block">
                    <div class="mp-section-header-between">
                        <div class="mp-section-header">
                            <span class="mp-section-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                                    <line x1="2" y1="10" x2="22" y2="10"/>
                                </svg>
                            </span>
                            <h2 class="mp-section-title">Metode Pembayaran</h2>
                        </div>

                        <span class="mp-pill-3-aktif" id="paymentActiveCountBadge">
                            <span class="dot"></span>
                            <span id="paymentActiveCountText">{{ $activeCount }} Aktif</span>
                        </span>
                    </div>

                    <div class="mp-payment-wrapper-card">
                        {{-- Header Accordion QRIS Merchant --}}
                        <button type="button" id="btnTogglePaymentAccordion" class="mp-payment-accordion-head" aria-expanded="true">
                            <div class="mp-payment-head-left">
                                <span class="mp-qris-logo-box">QRIS</span>
                                <div class="mp-payment-head-text">
                                    <div class="mp-payment-head-title">
                                        <span>Metode Pembayaran Dapur</span>
                                        <span class="mp-pill-utama">Utama</span>
                                    </div>
                                    <span class="mp-payment-head-sub">{{ $umkm->nama_vendor }} &bull; Multi-Metode</span>
                                </div>
                            </div>
                            <svg id="paymentChevronIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18 15 12 9 6 15"/>
                            </svg>
                        </button>

                        {{-- Body Pilihan Metode Pembayaran (Multi-Pilih Checkbox JSON) --}}
                        <div id="paymentAccordionBody" class="mp-payment-body">
                            <span class="mp-payment-caps">PILIH METODE PEMBAYARAN AKTIF (BISA LEBIH DARI SATU)</span>

                            <div class="mp-payment-options-list">
                                {{-- Opsi 1: QRIS --}}
                                @php $hasQris = in_array('QRIS', $activePayments) || in_array('qris', $activePayments); @endphp
                                <label class="mp-payment-option {{ $hasQris ? 'is-selected' : '' }}">
                                    <div class="mp-option-left">
                                        <input type="checkbox" name="metode_pembayaran[]" value="QRIS" class="mp-payment-checkbox" {{ $hasQris ? 'checked' : '' }} hidden>
                                        <span class="mp-custom-radio"></span>
                                        <div class="mp-option-info">
                                            <div class="mp-option-title-row">
                                                <strong>QRIS</strong>
                                                <span class="mp-pill-aktif-mini">{{ $hasQris ? 'Aktif' : 'Nonaktif' }}</span>
                                            </div>
                                            <span class="mp-option-sub">QRIS Statis &amp; Dinamis ({{ $umkm->nama_vendor }})</span>
                                        </div>
                                    </div>
                                </label>

                                {{-- Opsi 2: Transfer Bank --}}
                                @php $hasTransfer = in_array('Transfer Bank', $activePayments) || in_array('bca', $activePayments); @endphp
                                <label class="mp-payment-option {{ $hasTransfer ? 'is-selected' : '' }}">
                                    <div class="mp-option-left">
                                        <input type="checkbox" name="metode_pembayaran[]" value="Transfer Bank" class="mp-payment-checkbox" {{ $hasTransfer ? 'checked' : '' }} hidden>
                                        <span class="mp-custom-radio"></span>
                                        <div class="mp-option-info">
                                            <div class="mp-option-title-row">
                                                <strong>Transfer Bank</strong>
                                                <span class="mp-account-num">BCA / Mandiri</span>
                                            </div>
                                            <span class="mp-option-sub">a/n {{ $umkm->nama_vendor }}</span>
                                        </div>
                                    </div>
                                </label>

                                {{-- Opsi 3: Tunai --}}
                                @php $hasTunai = in_array('Tunai', $activePayments) || in_array('gopay', $activePayments); @endphp
                                <label class="mp-payment-option {{ $hasTunai ? 'is-selected' : '' }}">
                                    <div class="mp-option-left">
                                        <input type="checkbox" name="metode_pembayaran[]" value="Tunai" class="mp-payment-checkbox" {{ $hasTunai ? 'checked' : '' }} hidden>
                                        <span class="mp-custom-radio"></span>
                                        <div class="mp-option-info">
                                            <div class="mp-option-title-row">
                                                <strong>Tunai / COD</strong>
                                                <span class="mp-account-num">Bayar di Tempat</span>
                                            </div>
                                            <span class="mp-option-sub">a/n <span class="sync-owner-name">{{ $user->nama_lengkap }}</span></span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Info Banner Pencairan Saldo --}}
                    <div class="mp-cream-note-box">
                        <span class="mp-note-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                        </span>
                        <p class="mp-note-text">
                            Pencairan saldo pesanan katering dan donasi zero-waste akan disalurkan otomatis ke metode pembayaran aktif terpilih.
                        </p>
                    </div>
                </section>

                {{-- 5. TOMBOL SIMPAN PERUBAHAN, LOGOUT & FOOTER VERSI --}}
                <div class="mp-bottom-actions">
                    <button type="submit" class="mp-btn-save-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>

                    <button type="button" onclick="document.getElementById('mitraLogoutForm').submit();" class="mp-btn-logout-danger">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Keluar dari Akun Mitra</span>
                    </button>

                    <footer class="mp-version-footer">
                        <p class="mp-version-main">Plan2Eat Mitra v2.4.1 (Build 108)</p>
                        <p class="mp-version-sub">Circular Clean Tech Food System &bull; Zero-Waste Initiative</p>
                    </footer>
                </div>
            </div>
        </div>
    </form>

    {{-- Form Logout Terpisah --}}
    <form id="mitraLogoutForm" action="{{ route('umkm.logout') }}" method="POST" hidden>
        @csrf
    </form>
</div>
@endsection

{{-- DIALOG BOX (MODAL) EDIT PROFIL WARUNG --}}
@push('modals')
<div id="editWarungModal" class="mp-modal-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="editWarungModalTitle">
    <div class="mp-modal-card">
        {{-- Header Modal --}}
        <div class="mp-modal-header">
            <div class="mp-modal-title-group">
                <span class="mp-modal-icon-badge">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </span>
                <div>
                    <h3 id="editWarungModalTitle" class="mp-modal-title">Edit Profil Warung</h3>
                    <p class="mp-modal-subtitle">Perbarui informasi warung, pemilik, dan kontak resmi dapur mitra</p>
                </div>
            </div>

            <button type="button" id="btnCloseEditModalX" class="mp-modal-close-btn" aria-label="Tutup Dialog">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <hr class="mp-modal-divider">

        {{-- Body Form Modal --}}
        <div class="mp-modal-body">
            {{-- Upload Foto Warung --}}
            <div class="mp-modal-photo-row">
                <img
                    id="modalAvatarPreview"
                    src="{{ $umkm->foto_toko ? asset('storage/' . $umkm->foto_toko) : 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=240&q=80' }}"
                    alt="Preview Foto Warung"
                    class="mp-modal-avatar-thumb"
                >
                <div class="mp-modal-photo-info">
                    <span class="mp-modal-photo-label">Foto Profil Warung</span>
                    <button type="button" onclick="document.getElementById('modalAvatarFileInput').click();" class="mp-btn-change-photo">
                        Ganti Foto Warung
                    </button>
                </div>
            </div>

            {{-- 1. Nama Warung / Vendor --}}
            <div class="mp-modal-field">
                <label for="modalNamaVendor" class="mp-modal-label">Nama Warung / Dapur</label>
                <div class="mp-modal-input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    <input type="text" id="modalNamaVendor" class="mp-modal-input" placeholder="Masukkan nama warung" required>
                </div>
            </div>

            {{-- 2. Nama Pemilik --}}
            <div class="mp-modal-field">
                <label for="modalNamaPemilik" class="mp-modal-label">Nama Pemilik</label>
                <div class="mp-modal-input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <input type="text" id="modalNamaPemilik" class="mp-modal-input" placeholder="Masukkan nama pemilik warung" required>
                </div>
            </div>

            {{-- 3. Email Pemilik --}}
            <div class="mp-modal-field">
                <label for="modalEmailPemilik" class="mp-modal-label">Email Akun</label>
                <div class="mp-modal-input-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input type="email" id="modalEmailPemilik" class="mp-modal-input" placeholder="contoh@email.com" required>
                </div>
            </div>

            {{-- 4. Alamat Toko --}}
            <div class="mp-modal-field">
                <label for="modalAlamatToko" class="mp-modal-label">Alamat Lengkap Toko</label>
                <div class="mp-modal-textarea-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <textarea id="modalAlamatToko" rows="2" class="mp-modal-textarea" placeholder="Masukkan alamat lengkap warung" required></textarea>
                </div>
            </div>

            {{-- 5. Deskripsi Toko --}}
            <div class="mp-modal-field">
                <label for="modalDeskripsiToko" class="mp-modal-label">Deskripsi Singkat Toko (Opsional)</label>
                <div class="mp-modal-textarea-wrap">
                    <textarea id="modalDeskripsiToko" rows="2" class="mp-modal-textarea" placeholder="Ceritakan keunggulan dapur Anda"></textarea>
                </div>
            </div>
        </div>

        {{-- Footer Paling Bawah Dialog Box: Batal & Simpan ke Database --}}
        <div class="mp-modal-footer">
            <button type="button" id="btnCancelEditWarung" class="mp-modal-btn-cancel">Batal</button>
            <button type="button" id="btnConfirmEditWarung" class="mp-modal-btn-confirm">Simpan Perubahan</button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('mitraProfileMainForm');

    // =========================================================================
    // 1. TOGGLE ON / OFF STATUS BUKA TOKO (LANGSUNG SIMPAN KE DATABASE)
    // =========================================================================
    const btnToggleStoreOpen = document.getElementById('btnToggleStoreOpen');
    const hiddenStatusOperasional = document.getElementById('hiddenStatusOperasional');
    const storeSwitchLabel = document.getElementById('storeSwitchLabel');
    const storeStatusSubtext = document.getElementById('storeStatusSubtext');
    const statusToggleUrl = @json(route('umkm.profil-status', $umkm->id_umkm));
    const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || @json(csrf_token());

    function applyStoreStatusUI(isOpen) {
        hiddenStatusOperasional.value = isOpen ? 'Buka' : 'Tutup';
        btnToggleStoreOpen.classList.toggle('is-on', isOpen);
        btnToggleStoreOpen.classList.toggle('is-off', !isOpen);
        btnToggleStoreOpen.setAttribute('aria-checked', isOpen ? 'true' : 'false');

        if (storeSwitchLabel) {
            storeSwitchLabel.textContent = isOpen ? 'BUKA' : 'TUTUP';
        }
        if (storeStatusSubtext) {
            storeStatusSubtext.textContent = isOpen
                ? 'Menerima Pesanan Katering'
                : 'Toko Sedang Tutup';
        }
    }

    if (btnToggleStoreOpen && hiddenStatusOperasional) {
        btnToggleStoreOpen.addEventListener('click', async function () {
            const isCurrentlyOpen = hiddenStatusOperasional.value.toLowerCase() === 'buka';
            const nextIsOpen = !isCurrentlyOpen;
            const targetStatus = nextIsOpen ? 'Buka' : 'Tutup';

            applyStoreStatusUI(nextIsOpen);
            btnToggleStoreOpen.disabled = true;

            try {
                const res = await fetch(statusToggleUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        _method: 'PATCH',
                        status_operasional: targetStatus,
                    }),
                });

                if (!res.ok) {
                    throw new Error('Gagal memperbarui status');
                }
            } catch (err) {
                applyStoreStatusUI(isCurrentlyOpen);
                alert('Gagal menyimpan status buka toko ke database.');
            } finally {
                btnToggleStoreOpen.disabled = false;
            }
        });
    }

    // =========================================================================
    // 2. DIALOG BOX (MODAL) EDIT PROFIL WARUNG
    // =========================================================================
    const modal = document.getElementById('editWarungModal');
    const btnOpenModal = document.getElementById('btnOpenEditWarung');
    const btnCloseModalX = document.getElementById('btnCloseEditModalX');
    const btnCancelModal = document.getElementById('btnCancelEditWarung');
    const btnConfirmModal = document.getElementById('btnConfirmEditWarung');

    // Hidden inputs pada form utama
    const hiddenStoreName = document.getElementById('hiddenStoreName');
    const hiddenOwnerName = document.getElementById('hiddenOwnerName');
    const hiddenOwnerEmail = document.getElementById('hiddenOwnerEmail');
    const hiddenStoreAddress = document.getElementById('hiddenStoreAddress');
    const hiddenStoreDesc = document.getElementById('hiddenStoreDesc');

    // Input fields di dalam dialog box
    const modalNamaVendor = document.getElementById('modalNamaVendor');
    const modalNamaPemilik = document.getElementById('modalNamaPemilik');
    const modalEmailPemilik = document.getElementById('modalEmailPemilik');
    const modalAlamatToko = document.getElementById('modalAlamatToko');
    const modalDeskripsiToko = document.getElementById('modalDeskripsiToko');

    function openEditWarungModal() {
        if (!modal) return;
        modalNamaVendor.value = hiddenStoreName.value;
        modalNamaPemilik.value = hiddenOwnerName.value;
        modalEmailPemilik.value = hiddenOwnerEmail.value;
        modalAlamatToko.value = hiddenStoreAddress.value;
        modalDeskripsiToko.value = hiddenStoreDesc.value;

        modal.classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
        modalNamaVendor.focus();
    }

    function closeEditWarungModal() {
        if (!modal) return;
        modal.classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    if (btnOpenModal) btnOpenModal.addEventListener('click', openEditWarungModal);
    if (btnCloseModalX) btnCloseModalX.addEventListener('click', closeEditWarungModal);
    if (btnCancelModal) btnCancelModal.addEventListener('click', closeEditWarungModal);

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeEditWarungModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('is-hidden')) {
            closeEditWarungModal();
        }
    });

    // Konfirmasi & Langsung Simpan Perubahan ke Database
    if (btnConfirmModal) {
        btnConfirmModal.addEventListener('click', function () {
            const newVendor = modalNamaVendor.value.trim();
            const newOwner = modalNamaPemilik.value.trim();
            const newEmail = modalEmailPemilik.value.trim();
            const newAddress = modalAlamatToko.value.trim();
            const newDesc = modalDeskripsiToko.value.trim();

            if (!newVendor || !newOwner || !newEmail || !newAddress) {
                alert('Mohon lengkapi Nama Warung, Nama Pemilik, Email, dan Alamat Lengkap.');
                return;
            }

            hiddenStoreName.value = newVendor;
            hiddenOwnerName.value = newOwner;
            hiddenOwnerEmail.value = newEmail;
            hiddenStoreAddress.value = newAddress;
            hiddenStoreDesc.value = newDesc;

            // Langsung submit form utama agar perubahan tersimpan ke database
            if (mainForm) {
                mainForm.submit();
            }
        });
    }

    // Preview Foto Warung saat dipilih
    const modalAvatarFileInput = document.getElementById('modalAvatarFileInput');
    const modalAvatarPreview = document.getElementById('modalAvatarPreview');
    const mainStoreAvatarImg = document.getElementById('mainStoreAvatarImg');

    if (modalAvatarFileInput) {
        modalAvatarFileInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (file) {
                const previewUrl = URL.createObjectURL(file);
                if (modalAvatarPreview) modalAvatarPreview.src = previewUrl;
                if (mainStoreAvatarImg) mainStoreAvatarImg.src = previewUrl;
            }
        });
    }

    // =========================================================================
    // 3. UBAH JAM BUKA & MULTI-PILIHAN METODE PEMBAYARAN
    // =========================================================================
    const btnToggleHoursEditor = document.getElementById('btnToggleHoursEditor');
    const hoursEditorDrawer = document.getElementById('hoursEditorDrawer');
    const inputJamBuka = document.getElementById('inputJamBuka');
    const inputJamTutup = document.getElementById('inputJamTutup');
    const displayHoursRange = document.getElementById('displayHoursRange');

    if (btnToggleHoursEditor && hoursEditorDrawer) {
        btnToggleHoursEditor.addEventListener('click', function () {
            hoursEditorDrawer.classList.toggle('is-hidden');
        });
    }

    function syncHoursDisplay() {
        if (displayHoursRange && inputJamBuka && inputJamTutup) {
            displayHoursRange.textContent = (inputJamBuka.value || '07:00') + ' - ' + (inputJamTutup.value || '21:00');
        }
    }
    if (inputJamBuka) inputJamBuka.addEventListener('input', syncHoursDisplay);
    if (inputJamTutup) inputJamTutup.addEventListener('input', syncHoursDisplay);

    // Accordion Metode Pembayaran
    const btnTogglePaymentAccordion = document.getElementById('btnTogglePaymentAccordion');
    const paymentAccordionBody = document.getElementById('paymentAccordionBody');
    const paymentChevronIcon = document.getElementById('paymentChevronIcon');

    if (btnTogglePaymentAccordion && paymentAccordionBody) {
        btnTogglePaymentAccordion.addEventListener('click', function () {
            const isHidden = paymentAccordionBody.classList.toggle('is-hidden');
            btnTogglePaymentAccordion.setAttribute('aria-expanded', !isHidden ? 'true' : 'false');
            if (paymentChevronIcon) {
                paymentChevronIcon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    }

    // Highlight & Count Checkbox Metode Pembayaran Aktif
    const paymentCheckboxes = document.querySelectorAll('.mp-payment-checkbox');
    const paymentActiveCountText = document.getElementById('paymentActiveCountText');

    function updatePaymentUI() {
        let count = 0;
        paymentCheckboxes.forEach(function (cb) {
            const opt = cb.closest('.mp-payment-option');
            if (cb.checked) {
                count++;
                if (opt) opt.classList.add('is-selected');
            } else {
                if (opt) opt.classList.remove('is-selected');
            }
        });
        if (paymentActiveCountText) {
            paymentActiveCountText.textContent = count + ' Aktif';
        }
    }

    paymentCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            // Pastikan minimal 1 metode pembayaran tetap terpilih
            const anyChecked = Array.from(paymentCheckboxes).some(item => item.checked);
            if (!anyChecked) {
                cb.checked = true;
                alert('Minimal harus ada 1 metode pembayaran yang aktif.');
            }
            updatePaymentUI();
        });
    });
});
</script>
@endpush
