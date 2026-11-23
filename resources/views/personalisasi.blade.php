<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Personalization | Plan2Eat</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="..\images\favicon.ico">

    <!-- Stylesheets -->
    <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>
<body class="page-personalization">

@php
    $profilData = $profil ?? null;
    $budgetValue = old('batas_budget', $profilData?->batas_budget);

    $defaultPrefs = ['no_seafood', 'nut_free', 'halal_only'];
    $savedPrefs = [];
    if ($profilData) {
        $alergiList = is_array($profilData->riwayat_alergi) ? $profilData->riwayat_alergi : [];
        $dietStr = (string) ($profilData->jenis_diet ?? '');
        if (in_array('Seafood', $alergiList, true)) {
            $savedPrefs[] = 'no_seafood';
        }
        if (in_array('Kacang-kacangan', $alergiList, true)) {
            $savedPrefs[] = 'nut_free';
        }
        if (in_array('Gluten', $alergiList, true)) {
            $savedPrefs[] = 'gluten_free';
        }
        if (str_contains($dietStr, 'Halal')) {
            $savedPrefs[] = 'halal_only';
        }
        if (str_contains($dietStr, 'Low Calorie')) {
            $savedPrefs[] = 'low_calorie';
        }
        if (str_contains($dietStr, 'Vegetarian')) {
            $savedPrefs[] = 'vegetarian';
        }
    }

    $selectedPrefs = old('preferences', $profilData ? $savedPrefs : $defaultPrefs);
    if (! is_array($selectedPrefs)) {
        $selectedPrefs = [];
    }
@endphp

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

<main>
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
            {{-- Card 1: No Seafood --}}
            <label class="preference-card {{ in_array('no_seafood', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="no_seafood" class="preference-checkbox" {{ in_array('no_seafood', $selectedPrefs, true) ? 'checked' : '' }}>
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

            {{-- Card 2: Nut-Free --}}
            <label class="preference-card {{ in_array('nut_free', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="nut_free" class="preference-checkbox" {{ in_array('nut_free', $selectedPrefs, true) ? 'checked' : '' }}>
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

            {{-- Card 3: Halal Only --}}
            <label class="preference-card {{ in_array('halal_only', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="halal_only" class="preference-checkbox" {{ in_array('halal_only', $selectedPrefs, true) ? 'checked' : '' }}>
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
            <label class="preference-card {{ in_array('low_calorie', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="low_calorie" class="preference-checkbox" {{ in_array('low_calorie', $selectedPrefs, true) ? 'checked' : '' }}>
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
            <label class="preference-card {{ in_array('vegetarian', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="vegetarian" class="preference-checkbox" {{ in_array('vegetarian', $selectedPrefs, true) ? 'checked' : '' }}>
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
            <label class="preference-card {{ in_array('gluten_free', $selectedPrefs, true) ? 'is-selected' : '' }}">
                <input type="checkbox" name="preferences[]" value="gluten_free" class="preference-checkbox" {{ in_array('gluten_free', $selectedPrefs, true) ? 'checked' : '' }}>
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

        {{-- 4. Pengaturan Batas Budget Harian --}}
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
                                {{ $budgetValue ? 'Rp ' . number_format((int) $budgetValue, 0, ',', '.') : 'Belum diatur' }}
                            </span>
                            <span id="budgetUnitText" class="budget-unit-text {{ $budgetValue ? '' : 'is-hidden' }}">/ hari</span>
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
                        name="batas_budget"
                        min="0"
                        step="1000"
                        placeholder="Contoh: 35000"
                        value="{{ $budgetValue }}"
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
                <span>Membantu sistem merekomendasikan menu makanan sesuai budget harianmu.</span>
            </div>
        </section>

        {{-- 5. Sticky Bottom Action Bar --}}
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
</main>

@include('partials.popup_notification')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.preference-card').forEach(function (card) {
            const checkbox = card.querySelector('.preference-checkbox');
            if (!checkbox) {
                return;
            }

            card.classList.toggle('is-selected', checkbox.checked);
            checkbox.addEventListener('change', function () {
                card.classList.toggle('is-selected', checkbox.checked);
            });
        });

        const btnToggleBudgetEdit = document.getElementById('btnToggleBudgetEdit');
        const budgetEditDrawer = document.getElementById('budgetEditDrawer');
        const dailyBudgetInput = document.getElementById('dailyBudgetInput');
        const budgetDisplayValue = document.getElementById('budgetDisplayValue');
        const budgetUnitText = document.getElementById('budgetUnitText');
        const presetButtons = document.querySelectorAll('.btn-budget-preset');

        function updateBudgetDisplay(rawValue) {
            const val = parseInt(rawValue, 10);
            if (!val || val <= 0) {
                if (budgetDisplayValue) {
                    budgetDisplayValue.textContent = 'Belum diatur';
                }
                if (budgetUnitText) {
                    budgetUnitText.classList.add('is-hidden');
                }
                return;
            }

            if (budgetDisplayValue) {
                budgetDisplayValue.textContent = 'Rp ' + val.toLocaleString('id-ID');
            }
            if (budgetUnitText) {
                budgetUnitText.classList.remove('is-hidden');
            }
        }

        if (btnToggleBudgetEdit && budgetEditDrawer) {
            btnToggleBudgetEdit.addEventListener('click', function () {
                budgetEditDrawer.classList.toggle('is-hidden');
                if (!budgetEditDrawer.classList.contains('is-hidden') && dailyBudgetInput) {
                    dailyBudgetInput.focus();
                }
            });
        }

        if (dailyBudgetInput) {
            dailyBudgetInput.addEventListener('input', function () {
                updateBudgetDisplay(dailyBudgetInput.value);
            });
        }

        presetButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const val = btn.getAttribute('data-budget');
                if (dailyBudgetInput) {
                    dailyBudgetInput.value = val;
                    updateBudgetDisplay(val);
                }
            });
        });
    });
</script>

</body>
</html>
