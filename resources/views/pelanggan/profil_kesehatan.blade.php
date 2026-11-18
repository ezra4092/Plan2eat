@extends('layouts.main')

@section('title', 'Profil Kesehatan Pelanggan - Plan2Eat')
@section('page_badge', 'Profil Kesehatan')
@section('body_class', 'page-profile-kesehatan')

{{-- Header Hero Hijau Tua (Flush di Mobile, Card Banner di Desktop) --}}
@section('custom_header')
<section class="customer-profile-hero-wrap">
    <div class="customer-profile-hero">
        {{-- Baris Atas: Tombol Kembali & USR ID --}}
        <div class="profile-hero-topbar">
            <a href="{{ url('/beranda') }}" class="btn-hero-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>

            <span class="usr-id-pill">USR ID: {{ $user->usr_code ?? '#US-102' }}</span>
        </div>

        {{-- Bagian Tengah: Avatar, Tombol Ubah Username & Nama Pengguna --}}
        <div class="profile-hero-identity">
            <div class="hero-avatar-wrapper">
                <img
                    id="avatarPreviewImg"
                    src="{{ $user->avatar_url ?? asset('assets/images/avatar-default.png') }}"
                    alt="Foto Profil"
                    class="hero-avatar-photo"
                    onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=240&q=80';"
                >
                <label for="avatarFileInput" class="btn-avatar-camera" title="Ganti Foto Profil">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
            </div>

            <div class="hero-identity-text">
                <button type="button" id="btnFocusUsername" class="btn-pill-ubah-username">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    <span>Ubah Username</span>
                </button>

                <h1 id="heroUsernameDisplay" class="hero-username-title">
                    {{ old('username', $user->username ?? 'KudaJemping123') }}
                </h1>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
    $selectedPreferences = old('preferences', $user->preferences ?? ['no_seafood', 'nut_free', 'halal_only']);

    $preferenceItems = [
        [
            'key' => 'no_seafood',
            'title' => 'No Seafood',
            'desc' => 'Bebas ikan & udang',
            'icon' => 'ban',
        ],
        [
            'key' => 'nut_free',
            'title' => 'Nut-Free',
            'desc' => 'Alergi kacang-kacangan',
            'icon' => 'x',
        ],
        [
            'key' => 'halal_only',
            'title' => 'Halal Only',
            'desc' => '100% halal tersertifikasi',
            'icon' => 'halal',
        ],
        [
            'key' => 'low_calorie',
            'title' => 'Low Calorie',
            'desc' => '< 500 kkal / porsi',
            'icon' => 'flame',
        ],
        [
            'key' => 'vegetarian',
            'title' => 'Vegetarian',
            'desc' => 'Bebas daging & unggas',
            'icon' => 'leaf',
        ],
        [
            'key' => 'gluten_free',
            'title' => 'Gluten-Free',
            'desc' => 'Bebas gandum & tepung',
            'icon' => 'wheat',
        ],
    ];
@endphp

<div class="customer-profile-container">
    <form id="healthProfileForm" action="{{ url('/profil/kesehatan') }}" method="POST" enctype="multipart/form-data" class="customer-profile-form">
        @csrf
        @method('PUT')

        {{-- Input File Foto Tersembunyi (Terhubung ke ikon kamera di Hero) --}}
        <input type="file" id="avatarFileInput" name="avatar" accept="image/*" hidden>

        <div class="customer-profile-grid">
            {{-- KOLOM KIRI (Desktop) / BAGIAN ATAS (Mobile) --}}
            <div class="profile-col-stack">
                {{-- 1. Kartu Informasi Dasar Akun --}}
                <section class="profile-white-card">
                    <div class="profile-card-head">
                        <div class="head-title-group">
                            <span class="soft-mint-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/>
                                </svg>
                            </span>
                            <h2 class="profile-card-title">Informasi Dasar Akun</h2>
                        </div>
                    </div>

                    {{-- Field Username --}}
                    <div class="profile-field-block">
                        <div class="profile-label-row">
                            <label for="usernameInput" class="profile-field-label">Username</label>
                            <span id="usernameCounter" class="char-counter-text">14/30</span>
                        </div>

                        <div class="cream-input-box">
                            <input
                                type="text"
                                id="usernameInput"
                                name="username"
                                maxlength="30"
                                value="{{ old('username', $user->username ?? 'KudaJemping123') }}"
                                class="cream-text-input"
                                required
                            >
                            <button type="button" class="btn-inline-edit" onclick="document.getElementById('usernameInput').focus();" aria-label="Edit Username">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Field Alamat --}}
                    <div class="profile-field-block">
                        <div class="profile-label-row">
                            <label for="addressInput" class="profile-field-label">Alamat</label>
                            <button type="button" class="btn-map-pin-link">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>Atur Titik Peta</span>
                            </button>
                        </div>

                        <div class="cream-address-box">
                            <span class="address-pin-prefix">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </span>
                            <textarea
                                id="addressInput"
                                name="alamat"
                                rows="2"
                                class="cream-address-textarea"
                            >{{ old('alamat', $user->alamat ?? 'Jl. Katulistiwa Barat no. 13 (Samping kanan Fotocopy)') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- 2. Kartu Akun --}}
                <section class="profile-white-card">
                    <div class="profile-card-head">
                        <div class="head-title-group">
                            <span class="soft-mint-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <circle cx="12" cy="10" r="2.5"/>
                                    <path d="M8.5 16c.8-1.5 2-2.2 3.5-2.2s2.7.7 3.5 2.2"/>
                                </svg>
                            </span>
                            <h2 class="profile-card-title">Akun</h2>
                        </div>

                        <span class="badge-account-active">
                            <span class="active-dot"></span>
                            Aktif
                        </span>
                    </div>

                    <div class="account-info-group">
                        {{-- Nama Pemilik --}}
                        <div class="account-info-row">
                            <div class="account-row-left">
                                <span class="account-row-icon">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </span>
                                <div class="account-row-text">
                                    <span class="account-mini-label">NAMA PEMILIK</span>
                                    <span class="account-main-value">{{ $user->name ?? 'Ahmad Anzay' }}</span>
                                </div>
                            </div>
                            <span class="account-lock-icon" title="Data Terverifikasi">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                        </div>

                        {{-- No. WhatsApp --}}
                        <div class="account-info-row">
                            <div class="account-row-left">
                                <span class="account-row-icon is-mint">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                </span>
                                <div class="account-row-text">
                                    <span class="account-mini-label">NO. WHATSAPP</span>
                                    <span class="account-main-value">{{ $user->phone ?? '+62 812-2222-2222' }}</span>
                                </div>
                            </div>
                            <span class="badge-wa-connected">Terhubung</span>
                        </div>

                        {{-- Email Terdaftar --}}
                        <div class="account-info-row">
                            <div class="account-row-left">
                                <span class="account-row-icon">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </span>
                                <div class="account-row-text">
                                    <span class="account-mini-label">EMAIL TERDAFTAR</span>
                                    <span class="account-main-value">{{ $user->email ?? 'bakedatkedut@gmail.com' }}</span>
                                </div>
                            </div>
                            <span class="email-verified-check" title="Email Terverifikasi">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </section>

                {{-- 4. Kartu Batas Budget Harian (Ditampilkan di Kolom Kiri pada Desktop) --}}
                <section class="profile-white-card desktop-budget-slot">
                    <div class="budget-card-top">
                        <div class="budget-left-group">
                            <span class="budget-wallet-box">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/>
                                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/>
                                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                                </svg>
                            </span>
                            <div class="budget-info-col">
                                <span class="budget-mini-label">BATAS BUDGET HARIAN</span>
                                <div class="budget-amount-row">
                                    <span id="budgetDisplayValue" class="budget-amount-text">
                                        Rp {{ number_format((int) old('daily_budget', $user->daily_budget ?? 35000), 0, ',', '.') }}
                                    </span>
                                    <span class="budget-unit-text">/ hari</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="btnToggleBudgetEdit" class="btn-budget-ubah">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                            <span>Ubah</span>
                        </button>
                    </div>

                    {{-- Panel Input Edit Budget (Muncul saat tombol "Ubah" diklik) --}}
                    <div id="budgetEditDrawer" class="budget-edit-drawer is-hidden">
                        <div class="cream-input-box">
                            <span class="currency-prefix">Rp</span>
                            <input
                                type="number"
                                id="dailyBudgetInput"
                                name="daily_budget"
                                min="10000"
                                step="1000"
                                value="{{ old('daily_budget', $user->daily_budget ?? 35000) }}"
                                class="cream-text-input"
                            >
                        </div>
                        <div class="budget-preset-row">
                            <button type="button" class="btn-budget-preset" data-budget="25000">Rp 25.000</button>
                            <button type="button" class="btn-budget-preset" data-budget="35000">Rp 35.000</button>
                            <button type="button" class="btn-budget-preset" data-budget="50000">Rp 50.000</button>
                        </div>
                    </div>

                    <div class="budget-helper-footer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                        <span>Membantu sistem merekomendasikan bahan masakan sesuai kantong.</span>
                    </div>
                </section>
            </div>

            {{-- KOLOM KANAN (Desktop) / BAGIAN TENGAH-BAWAH (Mobile) --}}
            <div class="profile-col-stack">
                {{-- 3. Kartu Personalisasi Menu / Pilih Preferensi Makan --}}
                <section class="profile-white-card">
                    <div class="menu-pref-header">
                        <span class="pill-personalisasi-menu">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                <path d="M7 2v20"/>
                                <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                            </svg>
                            <span>Personalisasi Menu</span>
                        </span>

                        <h2 class="menu-pref-title">Pilih Preferensi Makan</h2>
                        <p class="menu-pref-subtitle">Sesuaikan menu harianmu untuk porsi presisi tanpa sisa.</p>
                    </div>

                    <div class="pref-selection-bar">
                        <span class="pref-section-caps">PILIHAN POLA MAKAN &amp; ALERGI</span>
                        <span id="selectedPrefCounter" class="pill-selected-count">{{ count($selectedPreferences) }} Dipilih</span>
                    </div>

                    {{-- Grid 6 Kartu Pola Makan & Alergi --}}
                    <div class="profile-pref-grid">
                        @foreach($preferenceItems as $item)
                            @php
                                $isChecked = in_array($item['key'], $selectedPreferences);
                            @endphp
                            <label class="profile-pref-card {{ $isChecked ? 'is-selected' : '' }}">
                                <input
                                    type="checkbox"
                                    name="preferences[]"
                                    value="{{ $item['key'] }}"
                                    class="profile-pref-checkbox"
                                    {{ $isChecked ? 'checked' : '' }}
                                >

                                <div class="profile-pref-top">
                                    <span class="profile-pref-icon">
                                        @if($item['icon'] === 'ban')
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                                            </svg>
                                        @elseif($item['icon'] === 'x')
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"/>
                                                <line x1="6" y1="6" x2="18" y2="18"/>
                                            </svg>
                                        @elseif($item['icon'] === 'halal')
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                <polyline points="9 12 11 14 15 10"/>
                                            </svg>
                                        @elseif($item['icon'] === 'flame')
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                                            </svg>
                                        @elseif($item['icon'] === 'leaf')
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                                                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                                            </svg>
                                        @else
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 22 22 2"/>
                                                <path d="M9.5 9.5 12 7l4 4-2.5 2.5"/>
                                                <path d="M14 5l3-3 4 4-3 3"/>
                                                <path d="M5 14l-3 3 4 4 3-3"/>
                                            </svg>
                                        @endif
                                    </span>

                                    <span class="profile-pref-check">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </span>
                                </div>

                                <div class="profile-pref-body">
                                    <h3 class="profile-pref-title">{{ $item['title'] }}</h3>
                                    <p class="profile-pref-desc">{{ $item['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Program / Jenis Diet Khusus --}}
                    <div class="profile-field-block">
                        <label for="dietProgramInput" class="profile-field-label">Program / Jenis Diet Khusus</label>
                        <div class="cream-input-box">
                            <input
                                type="text"
                                id="dietProgramInput"
                                name="diet_program"
                                value="{{ old('diet_program', $user->diet_program ?? '') }}"
                                placeholder="- - -"
                                class="cream-text-input"
                            >
                        </div>
                        <span class="field-helper-caption">Kosongkan jika tidak sedang menjalani diet khusus.</span>
                    </div>

                    {{-- Catatan Rasa & Porsi --}}
                    <div class="profile-field-block">
                        <label for="tasteNotesInput" class="profile-field-label">Catatan Rasa &amp; Porsi</label>
                        <div class="cream-textarea-box">
                            <textarea
                                id="tasteNotesInput"
                                name="taste_notes"
                                rows="3"
                                placeholder="Contoh: Suka pedas, kurangi minyak goreng"
                                class="cream-notes-textarea"
                            >{{ old('taste_notes', $user->taste_notes ?? 'Suka pedas, kurangi minyak goreng') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- 5. Tombol Aksi Utama (Simpan, Reset, Logout) --}}
                <div class="profile-actions-stack">
                    <button type="submit" class="btn-save-profile">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>

                    <button type="button" id="btnResetHealthProfile" class="btn-reset-profile">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                        </svg>
                        <span>Reset Seluruh Profil Kesehatan</span>
                    </button>

                    <button type="button" onclick="document.getElementById('logoutForm').submit();" class="btn-logout-profile">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Form Logout Terpisah --}}
    <form id="logoutForm" action="{{ url('/logout') }}" method="POST" hidden>
        @csrf
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live Counter & Sync Username
    const usernameInput = document.getElementById('usernameInput');
    const usernameCounter = document.getElementById('usernameCounter');
    const heroUsernameDisplay = document.getElementById('heroUsernameDisplay');
    const btnFocusUsername = document.getElementById('btnFocusUsername');

    function syncUsername() {
        if (!usernameInput) return;
        const len = usernameInput.value.length;
        if (usernameCounter) usernameCounter.textContent = len + '/30';
        if (heroUsernameDisplay) {
            heroUsernameDisplay.textContent = usernameInput.value.trim() || 'Pengguna';
        }
    }

    if (usernameInput) {
        syncUsername();
        usernameInput.addEventListener('input', syncUsername);
    }

    if (btnFocusUsername && usernameInput) {
        btnFocusUsername.addEventListener('click', function () {
            usernameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            usernameInput.focus();
            usernameInput.select();
        });
    }

    // 2. Preview Foto Profil saat Upload
    const avatarFileInput = document.getElementById('avatarFileInput');
    const avatarPreviewImg = document.getElementById('avatarPreviewImg');
    if (avatarFileInput && avatarPreviewImg) {
        avatarFileInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (file) {
                avatarPreviewImg.src = URL.createObjectURL(file);
            }
        });
    }

    // 3. Toggle Preferensi Pola Makan & Alergi + Counter "X Dipilih"
    const prefCheckboxes = document.querySelectorAll('.profile-pref-checkbox');
    const selectedPrefCounter = document.getElementById('selectedPrefCounter');

    function updatePreferenceState() {
        let count = 0;
        prefCheckboxes.forEach(function (cb) {
            const card = cb.closest('.profile-pref-card');
            if (cb.checked) {
                count++;
                if (card) card.classList.add('is-selected');
            } else {
                if (card) card.classList.remove('is-selected');
            }
        });
        if (selectedPrefCounter) {
            selectedPrefCounter.textContent = count + ' Dipilih';
        }
    }

    prefCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', updatePreferenceState);
    });
    updatePreferenceState();

    // 4. Toggle & Live Format Batas Budget Harian
    const btnToggleBudgetEdit = document.getElementById('btnToggleBudgetEdit');
    const budgetEditDrawer = document.getElementById('budgetEditDrawer');
    const dailyBudgetInput = document.getElementById('dailyBudgetInput');
    const budgetDisplayValue = document.getElementById('budgetDisplayValue');
    const presetButtons = document.querySelectorAll('.btn-budget-preset');

    function formatRupiah(num) {
        const val = parseInt(num, 10) || 0;
        return 'Rp ' + val.toLocaleString('id-ID');
    }

    if (btnToggleBudgetEdit && budgetEditDrawer) {
        btnToggleBudgetEdit.addEventListener('click', function () {
            budgetEditDrawer.classList.toggle('is-hidden');
            if (!budgetEditDrawer.classList.contains('is-hidden') && dailyBudgetInput) {
                dailyBudgetInput.focus();
            }
        });
    }

    if (dailyBudgetInput && budgetDisplayValue) {
        dailyBudgetInput.addEventListener('input', function () {
            budgetDisplayValue.textContent = formatRupiah(dailyBudgetInput.value);
        });
    }

    presetButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const val = btn.getAttribute('data-budget');
            if (dailyBudgetInput && budgetDisplayValue) {
                dailyBudgetInput.value = val;
                budgetDisplayValue.textContent = formatRupiah(val);
            }
        });
    });

    // 5. Reset Seluruh Profil Kesehatan
    const btnResetHealthProfile = document.getElementById('btnResetHealthProfile');
    if (btnResetHealthProfile) {
        btnResetHealthProfile.addEventListener('click', function () {
            prefCheckboxes.forEach(function (cb) {
                cb.checked = false;
            });
            updatePreferenceState();

            const dietInput = document.getElementById('dietProgramInput');
            const notesInput = document.getElementById('tasteNotesInput');
            if (dietInput) dietInput.value = '';
            if (notesInput) notesInput.value = '';
        });
    }
});
</script>
@endpush