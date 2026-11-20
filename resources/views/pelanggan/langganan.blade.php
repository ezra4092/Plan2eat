@extends('layouts.main', ['activeMenu' => 'langganan'])

@section('title', 'Langganan - Plan2Eat')
@section('page_badge', 'Langganan')

@push('styles')
<style>
  /* Override 7 kolom kalender sesuai rentang 7 hari */
  .lgn-calendar-grid.is-7days {
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
  }

  @media (min-width: 768px) {
    .lgn-calendar-grid.is-7days {
      gap: 8px;
    }
  }

  /* List card jadwal harian saat tanggal diklik */
  .lgn-day-schedule-wrap {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .lgn-day-schedule-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 700;
    color: #16442B;
    padding: 0 4px;
  }

  .lgn-day-item-card {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-left: 4px solid #16442B;
    border-radius: 14px;
    padding: 14px 16px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .lgn-day-item-main {
    font-family: var(--font-sans);
    font-weight: 700;
    font-size: 14.5px;
    color: #1F2937;
    margin: 0 0 4px;
  }

  .lgn-day-item-sub {
    font-size: 12px;
    color: #6B7280;
    margin: 0;
  }

  .lgn-source-badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    background: #E8F5E9;
    color: #16442B;
  }

  .lgn-source-badge.is-plan {
    background: #FEF3C7;
    color: #92400E;
  }

  .lgn-no-plan-card {
    background: #FFFFFF;
    border: 1.5px dashed #C7D3C9;
    border-radius: 18px;
    padding: 28px 18px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
  }

  .lgn-no-plan-card p {
    margin: 0;
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
  }

  .lgn-btn-meal-plan {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #16442B;
    color: #FFFFFF;
    padding: 10px 22px;
    border-radius: 999px;
    font-family: var(--font-sans);
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(22, 68, 43, 0.2);
    transition: background-color 0.2s, transform 0.12s;
  }

  .lgn-btn-meal-plan:hover {
    background: #0F301E;
    color: #FFFFFF;
    transform: translateY(-1px);
  }

  /* Riwayat Pesanan Table Card */
  .lgn-history-section {
    grid-column: 1 / -1;
    margin-top: 8px;
  }

  .lgn-history-card {
    background: #FFFFFF;
    border-radius: 22px;
    padding: 20px;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
    border: 1px solid #ECECEC;
  }

  .lgn-table-responsive {
    width: 100%;
    overflow-x: auto;
    margin-top: 14px;
  }

  .lgn-history-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 640px;
    font-family: var(--font-inter);
  }

  .lgn-history-table th {
    background: #F4F7F4;
    color: #16442B;
    font-family: var(--font-sans);
    font-size: 12.5px;
    font-weight: 700;
    text-align: left;
    padding: 12px 14px;
    border-bottom: 1.5px solid #E5E7EB;
  }

  .lgn-history-table th:first-child {
    border-top-left-radius: 10px;
  }

  .lgn-history-table th:last-child {
    border-top-right-radius: 10px;
    text-align: center;
  }

  .lgn-history-table td {
    padding: 13px 14px;
    font-size: 13.5px;
    color: #374151;
    border-bottom: 1px solid #F3F4F6;
    vertical-align: middle;
  }

  .lgn-history-table tr:last-child td {
    border-bottom: none;
  }

  .lgn-history-table td:last-child {
    text-align: center;
  }

  .lgn-btn-reorder {
    background: #16442B;
    color: #FFFFFF;
    border: none;
    border-radius: 999px;
    padding: 7px 16px;
    font-family: var(--font-sans);
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .lgn-btn-reorder:hover {
    background: #0F301E;
    transform: translateY(-1px);
  }
</style>
@endpush

@section('content')
@php
    $status = $subscriptionStatus ?? 'empty';
    $order = $activeOrder ?? null;
    $scheduleDays = $days ?? [];
    $selectedDay = collect($scheduleDays)->firstWhere('selected', true) ?? ($scheduleDays[0] ?? null);
    $step = (int) ($order['progress_step'] ?? 1);
    $progressPercent = $step <= 1 ? 0 : ($step === 2 ? 50 : 100);
@endphp

<div class="lgn-page">
    {{-- Notifikasi Flash --}}
    @if (session('success') || session('error'))
        <div style="grid-column: 1 / -1;">
            @if (session('success'))
                <div style="background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; padding: 12px 16px; border-radius: 10px; margin-bottom: 12px; font-size: 13.5px; font-weight: 600;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; padding: 12px 16px; border-radius: 10px; margin-bottom: 12px; font-size: 13.5px; font-weight: 600;">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    {{-- =====================================================================
         BAGIAN 1: STATUS PESANAN HARI INI
         ===================================================================== --}}
    <section class="lgn-section lgn-status-section">
        <h2 class="lgn-section-title">Status Pesanan</h2>

        @if ($status === 'empty' || !$order)
            {{-- KONDISI 1: Belum memesan makanan apapun --}}
            <div class="lgn-status-card is-empty">
                <div class="lgn-mint-banner">
                    Anda belum memesan makanan apapun saat ini
                </div>
                <a href="{{ route('pelanggan.mitra') }}" class="lgn-btn-add-order">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    <span>Tambahkan Pesanan</span>
                </a>
            </div>

        @elseif ($status === 'holiday')
            {{-- KONDISI 2: Toko / Mitra sedang tutup --}}
            <div class="lgn-status-card is-holiday">
                <div class="lgn-holiday-pill">
                    Mitra tutup
                </div>
            </div>

        @else
            {{-- KONDISI 3: Pesanan hari ini aktif --}}
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
                        onerror="this.src='https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=400&q=80';"
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

                {{-- Tombol Detail Pesanan --}}
                <div class="lgn-contact-wrap">
                    <a href="{{ $order['detail_url'] }}" class="lgn-btn-contact">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        <span>Lihat Detail Pesanan</span>
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- =====================================================================
         BAGIAN 2: DETAIL PEMESANAN & KALENDER 7 HARI
         ===================================================================== --}}
    <section class="lgn-section lgn-detail-section">
        <div class="lgn-detail-header">
            <h2 class="lgn-section-title">Detail Pemesanan</h2>
            <a href="#riwayatPesananSection" class="lgn-see-all">
                <span>Lihat Riwayat</span>
                <span aria-hidden="true">&gt;</span>
            </a>
        </div>

        {{-- Strip Kalender 7 Hari (Hari Ini + 6 Hari Ke Depan) --}}
        <div class="lgn-calendar-grid is-7days" id="lgnCalendarGrid">
            @foreach ($scheduleDays as $item)
                <button
                    type="button"
                    class="lgn-day-card is-{{ $item['state'] }} {{ !empty($item['selected']) ? 'is-selected' : '' }}"
                    data-full-date="{{ $item['full_date'] }}"
                    data-label-date="{{ $item['label_date'] }}"
                    data-is-holiday="{{ !empty($item['is_holiday']) ? '1' : '0' }}"
                    data-items='@json($item['items'])'
                >
                    <span class="lgn-day-num">{{ $item['date'] }}</span>
                    <span class="lgn-day-name">{{ $item['day'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- Card Jadwal / Meal Plan untuk Tanggal yang Dipilih --}}
        <div id="lgnDayScheduleWrap" class="lgn-day-schedule-wrap">
            <div class="lgn-day-schedule-header">
                <span id="lgnSelectedDateLabel">
                    Jadwal Tanggal {{ $selectedDay['label_date'] ?? now()->format('d M Y') }}
                </span>
                <a href="{{ route('pelanggan.meal_plan') }}" style="font-size: 12px; color: #16442B; text-decoration: none; font-weight: 700;">
                    Kelola Meal Plan &rarr;
                </a>
            </div>

            <div id="lgnDayCardsContainer" style="display: flex; flex-direction: column; gap: 10px;">
                @if (!empty($selectedDay['items']))
                    @foreach ($selectedDay['items'] as $dItem)
                        <div class="lgn-day-item-card">
                            <div>
                                <p class="lgn-day-item-main">{{ $dItem['menu_nama'] }} - {{ $dItem['waktu'] }}</p>
                                <p class="lgn-day-item-sub">{{ $dItem['mitra'] }} &bull; {{ $dItem['kuantitas'] }}x Porsi</p>
                            </div>
                            <span class="lgn-source-badge {{ $dItem['source'] === 'Meal Plan' ? 'is-plan' : '' }}">
                                {{ $dItem['source'] }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="lgn-no-plan-card">
                        <p>Belum ada pesanan atau meal plan di tanggal ini.</p>
                        <a href="{{ route('pelanggan.meal_plan', ['tanggal' => $selectedDay['full_date'] ?? now()->toDateString()]) }}" class="lgn-btn-meal-plan">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Meal Plan</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- =====================================================================
         BAGIAN 3: RIWAYAT PESANAN PELANGGAN (TABEL + PESAN LAGI)
         ===================================================================== --}}
    <section class="lgn-section lgn-history-section" id="riwayatPesananSection">
        <div class="lgn-history-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                <h2 class="lgn-section-title" style="margin: 0;">Riwayat Pesanan</h2>
                <span style="font-size: 12.5px; color: #6B7280;">Total: {{ count($riwayatPesanan ?? []) }} pesanan</span>
            </div>

            <div class="lgn-table-responsive">
                <table class="lgn-history-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Menu</th>
                            <th>Nama Mitra</th>
                            <th>Tanggal</th>
                            <th>Total Belanja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatPesanan ?? [] as $idx => $ord)
                            @php
                                $menuNames = $ord->detailPesanan
                                    ->map(fn ($d) => ($d->menu->nama_makanan ?? 'Menu') . ' (' . $d->kuantitas . 'x)')
                                    ->implode(', ');
                            @endphp
                            <tr>
                                <td><strong>{{ $idx + 1 }}</strong></td>
                                <td>{{ $menuNames ?: '-' }}</td>
                                <td>{{ $ord->umkm->nama_vendor ?? 'Mitra Plan2Eat' }}</td>
                                <td>{{ $ord->tgl_mulai ? \Carbon\Carbon::parse($ord->tgl_mulai)->format('d M Y') : '-' }}</td>
                                <td><strong>Rp {{ number_format((float) $ord->total_pembayaran, 0, ',', '.') }}</strong></td>
                                <td>
                                    <form action="{{ route('pelanggan.pesanan-reorder', $ord->id_pesanan) }}" method="POST" style="margin: 0; display: inline-block;">
                                        @csrf
                                        <button type="submit" class="lgn-btn-reorder">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 4 23 10 17 10"/>
                                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                                            </svg>
                                            <span>Pesan lagi</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #6B7280; padding: 28px 16px;">
                                    Belum ada riwayat pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dayCards = document.querySelectorAll('#lgnCalendarGrid .lgn-day-card');
    const selectedDateLabel = document.getElementById('lgnSelectedDateLabel');
    const cardsContainer = document.getElementById('lgnDayCardsContainer');
    const mealPlanBaseUrl = @json(route('pelanggan.meal_plan'));

    if (!dayCards.length || !cardsContainer) return;

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }

    dayCards.forEach(function (card) {
        card.addEventListener('click', function () {
            dayCards.forEach(c => c.classList.remove('is-selected'));
            card.classList.add('is-selected');

            const fullDate = card.dataset.fullDate;
            const labelDate = card.dataset.labelDate;
            const isHoliday = card.dataset.isHoliday === '1';
            let items = [];
            try {
                items = JSON.parse(card.dataset.items || '[]');
            } catch (e) {
                items = [];
            }

            if (selectedDateLabel) {
                selectedDateLabel.textContent = 'Jadwal Tanggal ' + labelDate;
            }

            if (items.length > 0) {
                let html = '';
                if (isHoliday) {
                    html += '<div style="background: #FEF3C7; color: #92400E; padding: 10px 14px; border-radius: 10px; font-size: 12.5px; font-weight: 700;">Mitra tutup pada tanggal ini</div>';
                }
                items.forEach(function (it) {
                    const isPlan = it.source === 'Meal Plan';
                    html += `
                        <div class="lgn-day-item-card">
                            <div>
                                <p class="lgn-day-item-main">${escapeHtml(it.menu_nama)} - ${escapeHtml(it.waktu)}</p>
                                <p class="lgn-day-item-sub">${escapeHtml(it.mitra)} &bull; ${escapeHtml(String(it.kuantitas || 1))}x Porsi</p>
                            </div>
                            <span class="lgn-source-badge ${isPlan ? 'is-plan' : ''}">${escapeHtml(it.source)}</span>
                        </div>
                    `;
                });
                cardsContainer.innerHTML = html;
            } else {
                const targetUrl = mealPlanBaseUrl + '?tanggal=' + encodeURIComponent(fullDate);
                cardsContainer.innerHTML = `
                    <div class="lgn-no-plan-card">
                        <p>Belum ada pesanan atau meal plan di tanggal ini.</p>
                        <a href="${targetUrl}" class="lgn-btn-meal-plan">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Buat Meal Plan</span>
                        </a>
                    </div>
                `;
            }
        });
    });
});
</script>
@endpush
