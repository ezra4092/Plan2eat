@extends('layouts.main')

@section('title', 'Langganan - Plan2Eat')
@section('page_pill', 'Beranda')

@section('content')
@php
    /**
     * Variabel kondisional dari Controller:
     * $subscriptionStatus : 'active' | 'holiday' | 'empty'
     * Bisa juga diuji via query string ?status=holiday atau ?status=empty
     */
    $status = $subscriptionStatus ?? request('status', 'active');
    //DATA DUMMY HEHEHEHEHEHHEHEHEH
    $order = $activeOrder ?? [
        'schedule_label' => 'Makan Pagi Terjadwal • Tiba 09:00 WIB',
        'mitra_name'     => 'Dapur Sifren',
        'menu_name'      => 'Nasi Rames Gembus',
        'portion_detail' => '& Tempe Goreng 2x • Porsi Personal',
        'badge_text'     => 'Pesanan Hari Ini',
        'image'          => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80',
        'progress_step'  => 3, // 1 = Prep, 2 = Masak, 3 = Siap
        'progress_label' => 'Pesanan Selesai',
        'chat_url'       => '#hubungi-mitra',
    ];

    $scheduleDays = $days ?? [
        [
            'date'        => '9',
            'day'         => 'Sun',
            'state'       => 'missed', // 'missed' | 'completed' | 'holiday' | 'upcoming'
            'selected'    => false,
            'mitra_label' => 'Dapur Hijau Organik • 0.8 km',
            'menu_name'   => 'Paket Sayur Lodeh & Tahu Bacem',
            'image'       => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
        ],
        [
            'date'        => '10',
            'day'         => 'Mon',
            'state'       => 'completed',
            'selected'    => true,
            'mitra_label' => 'Dapur Hijau Organik • 0.8 km',
            'menu_name'   => 'Nasi Rames Gembus',
            'image'       => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'date'        => '11',
            'day'         => 'Tue',
            'state'       => 'holiday',
            'selected'    => false,
            'mitra_label' => 'Dapur Sifren • 0.8 km',
            'menu_name'   => 'Libur Operasional Mitra',
            'image'       => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'date'        => '12',
            'day'         => 'Wed',
            'state'       => 'upcoming',
            'selected'    => false,
            'mitra_label' => 'Dapur Hijau Organik • 0.8 km',
            'menu_name'   => 'Nasi Merah Ayam Panggang Madu',
            'image'       => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=800&q=80',
        ],
        [
            'date'        => '13',
            'day'         => 'Thu',
            'state'       => 'upcoming',
            'selected'    => false,
            'mitra_label' => 'Dapur Hijau Organik • 0.8 km',
            'menu_name'   => 'Nasi Daun Jeruk Ikan Bakar',
            'image'       => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
        ],
        [
            'date'        => '14',
            'day'         => 'Fri',
            'state'       => 'upcoming',
            'selected'    => false,
            'mitra_label' => 'Dapur Hijau Organik • 0.8 km',
            'menu_name'   => 'Pepes Ayam Kemangi & Urap',
            'image'       => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
        ],
    ];

    $selectedDay = collect($scheduleDays)->firstWhere('selected', true) ?? $scheduleDays[1];
    $step = (int) ($order['progress_step'] ?? 3);
    $progressPercent = $step <= 1 ? 0 : ($step === 2 ? 50 : 100);
@endphp

<div class="lgn-page {{ $status === 'empty' ? 'is-empty-layout' : '' }}">
    {{-- =====================================================================
         BAGIAN 1: STATUS PESANAN
         ===================================================================== --}}
    <section class="lgn-section lgn-status-section">
        <h2 class="lgn-section-title">Status Pesanan</h2>

        @if ($status === 'empty')
            {{-- KONDISI 3: Belum memesan makanan apapun --}}
            <div class="lgn-status-card is-empty">
                <div class="lgn-mint-banner">
                    Anda belum memesan makanan apapun saat ini
                </div>
                <a href="{{ url('/mitra') }}" class="lgn-btn-add-order">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <span>Tambahkan Pesanan</span>
                </a>
            </div>

        @elseif ($status === 'holiday')
            {{-- KONDISI 2: Mitra sedang libur --}}
            <div class="lgn-status-card is-holiday">
                <div class="lgn-holiday-pill">
                    Mitranya sedang libur fren
                </div>
            </div>

        @else
            {{-- KONDISI 1: Pesanan aktif & terjadwal --}}
            <div class="lgn-status-card is-active">
                {{-- Pill Jadwal Pengantaran --}}
                <div class="lgn-schedule-pill">
                    <span class="lgn-green-dot"></span>
                    <span>{{ $order['schedule_label'] }}</span>
                </div>

                {{-- Info Menu Pesanan Hari Ini --}}
                <div class="lgn-order-product">
                    <img
                        src="{{ $order['image'] }}"
                        alt="{{ $order['menu_name'] }}"
                        class="lgn-product-thumb"
                    >
                    <div class="lgn-product-info">
                        <div class="lgn-vendor-row">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l1.5-5h15L21 9"/>
                                <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/>
                                <path d="M5 12v8h14v-8"/>
                            </svg>
                            <span>{{ $order['mitra_name'] }}</span>
                        </div>
                        <h3 class="lgn-dish-title">{{ $order['menu_name'] }}</h3>
                        <p class="lgn-dish-meta">{{ $order['portion_detail'] }}</p>
                        <span class="lgn-today-badge">{{ $order['badge_text'] }}</span>
                    </div>
                </div>

                {{-- Box Putih Progress (Prep -> Masak -> Siap) --}}
                <div class="lgn-progress-box">
                    <div class="lgn-progress-head">
                        <span class="lgn-progress-title">Progress</span>
                        <span class="lgn-progress-status">{{ $order['progress_label'] }}</span>
                    </div>

                    <div class="lgn-stepper" style="--lgn-progress: {{ $progressPercent }};">
                        <div class="lgn-stepper-track">
                            <span class="lgn-stepper-fill"></span>
                        </div>

                        <div class="lgn-step {{ $step >= 1 ? 'is-done' : '' }}">
                            <span class="lgn-step-circle">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                            <span class="lgn-step-label">Prep</span>
                        </div>

                        <div class="lgn-step {{ $step >= 2 ? 'is-done' : '' }}">
                            <span class="lgn-step-circle">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                            <span class="lgn-step-label">Masak</span>
                        </div>

                        <div class="lgn-step {{ $step >= 3 ? 'is-done' : '' }}">
                            <span class="lgn-step-circle">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                            <span class="lgn-step-label">Siap</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Hubungi Mitra --}}
                <div class="lgn-contact-wrap">
                    <a href="{{ $order['chat_url'] }}" class="lgn-btn-contact">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <span>Hubungi Mitra</span>
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- =====================================================================
         BAGIAN 2: DETAIL PEMESANAN & JADWAL HARIAN
         ===================================================================== --}}
    <section class="lgn-section lgn-detail-section">
        <div class="lgn-detail-header">
            <h2 class="lgn-section-title">Detail Pemesanan</h2>
            <a href="{{ url('/langganan/riwayat') }}" class="lgn-see-all">
                <span>Lihat Semua</span>
                <span aria-hidden="true">&gt;</span>
            </a>
        </div>

        @if ($status === 'empty')
            {{-- KONDISI 3: Pesanan masih kosong --}}
            <div class="lgn-empty-state">
                <p>Yahhh pesanan masih kosong...</p>
            </div>

        @else
            {{-- Strip Kalender 6 Hari (9 Sun - 14 Fri) --}}
            <div class="lgn-calendar-grid" id="lgnCalendarGrid">
                @foreach ($scheduleDays as $item)
                    <button
                        type="button"
                        class="lgn-day-card is-{{ $item['state'] }} {{ !empty($item['selected']) && $status !== 'holiday' ? 'is-selected' : '' }}"
                        data-state="{{ $item['state'] }}"
                        data-mitra="{{ $item['mitra_label'] }}"
                        data-menu="{{ $item['menu_name'] }}"
                        data-image="{{ $item['image'] }}"
                    >
                        <span class="lgn-day-num">{{ $item['date'] }}</span>
                        <span class="lgn-day-name">{{ $item['day'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Konten Bawah Kalender (Berubah sesuai kondisi libur vs menu aktif) --}}
            <div id="lgnHolidayEmptyBox" class="lgn-empty-state {{ $status === 'holiday' ? '' : 'is-hidden' }}">
                <p>Mitra nya lagi libur fren :DD</p>
            </div>

            <div id="lgnMenuShowcaseBox" class="lgn-menu-showcase {{ $status === 'holiday' ? 'is-hidden' : '' }}">
                <img
                    id="lgnShowcaseImg"
                    src="{{ $selectedDay['image'] }}"
                    alt="{{ $selectedDay['menu_name'] }}"
                    class="lgn-showcase-img"
                >
                <div class="lgn-showcase-gradient"></div>

                <span class="lgn-menu-top-badge">Menu Untuk Hari Ini</span>

                <div class="lgn-showcase-bottom">
                    <div class="lgn-showcase-text">
                        <p id="lgnShowcaseMitra" class="lgn-showcase-mitra">{{ $selectedDay['mitra_label'] }}</p>
                        <h3 id="lgnShowcaseTitle" class="lgn-showcase-title">{{ $selectedDay['menu_name'] }}</h3>
                    </div>
                    <span class="lgn-eco-pill">Eco Packaging</span>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dayCards = document.querySelectorAll('#lgnCalendarGrid .lgn-day-card');
    const holidayBox = document.getElementById('lgnHolidayEmptyBox');
    const showcaseBox = document.getElementById('lgnMenuShowcaseBox');
    const showcaseImg = document.getElementById('lgnShowcaseImg');
    const showcaseMitra = document.getElementById('lgnShowcaseMitra');
    const showcaseTitle = document.getElementById('lgnShowcaseTitle');

    if (!dayCards.length) return;

    dayCards.forEach(function (card) {
        card.addEventListener('click', function () {
            dayCards.forEach(c => c.classList.remove('is-selected'));
            card.classList.add('is-selected');

            const state = card.dataset.state;
            if (state === 'holiday') {
                if (showcaseBox) showcaseBox.classList.add('is-hidden');
                if (holidayBox) holidayBox.classList.remove('is-hidden');
            } else {
                if (holidayBox) holidayBox.classList.add('is-hidden');
                if (showcaseBox) showcaseBox.classList.remove('is-hidden');
                if (showcaseImg && card.dataset.image) showcaseImg.src = card.dataset.image;
                if (showcaseMitra && card.dataset.mitra) showcaseMitra.textContent = card.dataset.mitra;
                if (showcaseTitle && card.dataset.menu) showcaseTitle.textContent = card.dataset.menu;
            }
        });
    });
});
</script>
@endpush