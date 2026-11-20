@extends('layouts.main')

@section('title', 'Personalization - Plan2Eat')
@section('body_class', 'page-personalization')

{{-- Header Khusus Halaman Personalization --}}
@section('custom_header')
<header class="personalization-header">
    <div class="personalization-header-inner">
        <a href="{{ url()->previous() }}" class="btn-back-nav" aria-label="Kembali">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="personalization-brand-badge" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="5" width="16" height="15" rx="4"/>
                <path d="M8 3v3M16 3v3"/>
                <circle cx="12" cy="12.5" r="2.5"/>
            </svg>
        </div>

        <h1 class="personalization-page-title">Personalization</h1>
    </div>
</header>
@endsection

@section('content')
<form action="{{ route('pelanggan.personalisasi-store') }}" method="POST" class="personalization-container" id="personalizationForm">
    @csrf

    {{-- 1. Hero Card: Pilih Preferensi Makan --}}
    <section class="personalization-hero-card">
        <div class="badge-personalization-menu">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                <path d="M7 2v20"/>
                <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
            </svg>
            <span>Personalisasi Menu</span>
        </div>

        <h2 class="personalization-hero-title">Pilih Preferensi Makan</h2>
        <p class="personalization-hero-subtitle">Sesuaikan menu harianmu untuk porsi tanpa sisa</p>

        {{-- Dekorasi Watermark Daun di Kanan Bawah --}}
        <svg class="hero-leaf-watermark" width="88" height="88" viewBox="0 0 88 88" fill="none" aria-hidden="true">
            <path d="M22 66C22 40 42 22 70 22C70 50 52 66 22 66Z" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M22 66L54 36" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
            <path d="M46 64V80" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
        </svg>
    </section>

    {{-- 2. Info Banner: Alergi & Gaya Hidup --}}
    <section class="allergy-info-banner" role="region" aria-label="Informasi Alergi dan Gaya Hidup">
        <div class="allergy-banner-left">
            <div class="allergy-icon-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M12 8v6M9 11h6"/>
                </svg>
            </div>
            <div class="allergy-text-group">
                <h3 class="allergy-title">Alergi &amp; Gaya Hidup</h3>
                <p class="allergy-subtitle">Bahan pemicu akan otomatis dieliminasi</p>
            </div>
        </div>

        <button type="button" class="btn-allergy-info" title="Bahan pemicu alergi otomatis disaring dari rekomendasi menu" aria-label="Detail informasi alergi">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 11v5"/>
                <circle cx="12" cy="8" r="0.8" fill="currentColor"/>
            </svg>
        </button>
    </section>

    {{-- 3. Grid Kartu Pilihan Preferensi --}}
    <section class="preference-grid" aria-label="Daftar Preferensi Makan">
        {{-- Card 1: No Seafood (Aktif Default) --}}
        <label class="preference-card is-selected">
            <input type="checkbox" name="preferences[]" value="no_seafood" class="preference-checkbox" checked>
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="10" rx="2"/>
                        <path d="M7 9c2.5-2 6-2 8.5 0L18 7v4l-2.5-2c-2.5 2-6 2-8.5 0z"/>
                        <path d="M5 17h14M6 20h12"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">No Seafood</h4>
                <p class="preference-desc">Bebas ikan &amp; udang</p>
            </div>
        </label>

        {{-- Card 2: Nut-Free (Aktif Default) --}}
        <label class="preference-card is-selected">
            <input type="checkbox" name="preferences[]" value="nut_free" class="preference-checkbox" checked>
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8"/>
                        <line x1="8.5" y1="12" x2="15.5" y2="12"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">Nut-Free</h4>
                <p class="preference-desc">Alergi kacang-kacangan</p>
            </div>
        </label>

        {{-- Card 3: Halal Only (Aktif Default) --}}
        <label class="preference-card is-selected">
            <input type="checkbox" name="preferences[]" value="halal_only" class="preference-checkbox" checked>
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l2.2 1.8 2.8-.3.9 2.7 2.5 1.3-.9 2.7.9 2.7-2.5 1.3-.9 2.7-2.8-.3L12 21l-2.2-1.8-2.8.3-.9-2.7-2.5-1.3.9-2.7-.9-2.7 2.5-1.3.9-2.7 2.8.3L12 3z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">Halal Only</h4>
                <p class="preference-desc">100% Halal tersertifikasi</p>
            </div>
        </label>

        {{-- Card 4: Low Calorie --}}
        <label class="preference-card">
            <input type="checkbox" name="preferences[]" value="low_calorie" class="preference-checkbox">
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2c.5 3.5-2 5.5-2 8.5a4 4 0 0 0 8 0c0-2.5-1.5-4.5-3-6.5.5 2-1 3.5-2 3.5-1.2 0-2-1.2-1-5.5z"/>
                        <path d="M8.5 11.5A5.5 5.5 0 1 0 17.5 16"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">Low Calorie</h4>
                <p class="preference-desc">&lt; 500 kkal / porsi</p>
            </div>
        </label>

        {{-- Card 5: Vegetarian --}}
        <label class="preference-card">
            <input type="checkbox" name="preferences[]" value="vegetarian" class="preference-checkbox">
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18C6 10 11 5 19 5c0 8-5 13-13 13z"/>
                        <path d="M5 19l9-9"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">Vegetarian</h4>
                <p class="preference-desc">Bebas daging &amp; unggas</p>
            </div>
        </label>

        {{-- Card 6: Bebas Gluten --}}
        <label class="preference-card">
            <input type="checkbox" name="preferences[]" value="gluten_free" class="preference-checkbox">
            <div class="preference-card-top">
                <div class="preference-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="6" r="1.6"/>
                        <circle cx="8" cy="9" r="1.6"/>
                        <circle cx="16" cy="9" r="1.6"/>
                        <circle cx="12" cy="12" r="1.6"/>
                        <circle cx="8" cy="15" r="1.6"/>
                        <circle cx="16" cy="15" r="1.6"/>
                        <circle cx="12" cy="18" r="1.6"/>
                    </svg>
                </div>
                <span class="preference-check-circle" aria-hidden="true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
            </div>
            <div class="preference-card-body">
                <h4 class="preference-title">Bebas Gluten</h4>
                <p class="preference-desc">Tanpa gandum &amp; tepung</p>
            </div>
        </label>
    </section>

    {{-- 4. Sticky Bottom Action Bar --}}
    <div class="personalization-footer-bar">
        <div class="personalization-footer-inner">
            <button type="submit" class="btn-personalization-next">
                <span>Lanjut</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
            <p class="personalization-helper-text">Masukan preferensi. Dapat diubah di pengaturan akun.</p>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.preference-card').forEach(function (card) {
        const checkbox = card.querySelector('.preference-checkbox');
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            card.classList.toggle('is-selected', checkbox.checked);
        });
    });
</script>
@endpush