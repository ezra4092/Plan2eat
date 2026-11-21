@extends('layouts.main')

@section('title', 'Dashboard Admin — Plan2Eat')
@section('breadcrumb', 'Dashboard')

@section('content')
@php
    // Periode aktif default atau dari query ?periode=...
    $activePeriod = request('periode', $periode ?? '7_hari');
    if (!in_array($activePeriod, ['7_hari', '1_bulan', '4_bulan'])) {
        $activePeriod = '7_hari';
    }

    // Dataset Kondisional sesuai Gambar 1 (7 hari terakhir), Gambar 2 (1 bulan terakhir), & 4 bulan terakhir
    $periodDatasets = $datasets ?? [
        '7_hari' => [
            'label'             => '7 hari terakhir',
            'total_pengguna'    => '12.480',
            'mitra_aktif'       => '248',
            'total_pesanan'     => '1.286',
            'transaksi_label'   => 'Transaksi 7 hari terakhir',
            'transaksi_nilai'   => 'Rp 32,4 jt',
            'chart_subtitle'    => 'Sebanyak 1.286 pesanan',
            'bars' => [
                ['label' => 'Sen', 'value' => 620,  'peak' => false],
                ['label' => 'Sel', 'value' => 920,  'peak' => false],
                ['label' => 'Rab', 'value' => 750,  'peak' => false],
                ['label' => 'Kam', 'value' => 1150, 'peak' => false],
                ['label' => 'Jum', 'value' => 980,  'peak' => false],
                ['label' => 'Sab', 'value' => 1420, 'peak' => true],
                ['label' => 'Min', 'value' => 680,  'peak' => false],
            ],
        ],
        '1_bulan' => [
            'label'             => '1 bulan terakhir',
            'total_pengguna'    => '27.964',
            'mitra_aktif'       => '253',
            'total_pesanan'     => '3.205',
            'transaksi_label'   => 'Transaksi 1 bulan terakhir',
            'transaksi_nilai'   => 'Rp 74,2 jt',
            'chart_subtitle'    => 'Sebanyak 3.205 pesanan',
            'bars' => [
                ['label' => 'Minggu ke-1', 'value' => 900,  'peak' => false],
                ['label' => 'Minggu ke-2', 'value' => 760,  'peak' => false],
                ['label' => 'Minggu ke-3', 'value' => 1410, 'peak' => true],
                ['label' => 'Minggu ke-4', 'value' => 700,  'peak' => false],
            ],
        ],
        '4_bulan' => [
            'label'             => '4 bulan terakhir',
            'total_pengguna'    => '64.120',
            'mitra_aktif'       => '271',
            'total_pesanan'     => '12.840',
            'transaksi_label'   => 'Transaksi 4 bulan terakhir',
            'transaksi_nilai'   => 'Rp 298,5 jt',
            'chart_subtitle'    => 'Sebanyak 12.840 pesanan',
            'bars' => [
                ['label' => 'Agu', 'value' => 840,  'peak' => false],
                ['label' => 'Sep', 'value' => 1050, 'peak' => false],
                ['label' => 'Okt', 'value' => 1440, 'peak' => true],
                ['label' => 'Nov', 'value' => 1190, 'peak' => false],
            ],
        ],
    ];

    $currentData = $periodDatasets[$activePeriod];
    $maxScale = 1500;

    // Data Item Perlu Ditinjau
    $verifikasiMitraCount = $verifikasiMitraCount ?? 12;
    $laporanPenggunaCount = $laporanPenggunaCount ?? 8;

    // Data Tabel Laporan Aduan (Sesuai Gambar 1, 2, & Detail Modal Gambar 3)
    $laporanList = $laporanAduan ?? [
        [
            'id'              => '#P2E-1286',
            'slug'            => 'P2E-1286',
            'pelanggan'       => 'Ahmad Asqzy',
            'pelanggan_badge' => 'Konsumen Terverifikasi',
            'mitra'           => 'Dapur Hijau Organik',
            'mitra_kategori'  => 'Katering Sehat & Organik',
            'status'          => 'Dalam Investigasi',
            'tanggal'         => '14 Nov 2026, 14:35 WIB',
            'subjek'          => 'Pesanan Salad Organik tidak sesuai spesifikasi dan kemasan rusak saat diterima',
        ],
        [
            'id'              => '#P2E-1285',
            'slug'            => 'P2E-1285',
            'pelanggan'       => 'Siti Rahma',
            'pelanggan_badge' => 'Konsumen Terverifikasi',
            'mitra'           => 'Warung Sehat Bu Ari',
            'mitra_kategori'  => 'Katering Harian & Diet',
            'status'          => 'Dalam Investigasi',
            'tanggal'         => '14 Nov 2026, 11:20 WIB',
            'subjek'          => 'Keterlambatan pengiriman makan siang melebihi batas waktu estimasi 45 menit',
        ],
        [
            'id'              => '#P2E-1284',
            'slug'            => 'P2E-1284',
            'pelanggan'       => 'Budi Santoso',
            'pelanggan_badge' => 'Konsumen Terverifikasi',
            'mitra'           => 'Salad Segar Bogor',
            'mitra_kategori'  => 'Healthy Bowl & Salad',
            'status'          => 'Menunggu Tanggapan Mitra',
            'tanggal'         => '13 Nov 2026, 19:05 WIB',
            'subjek'          => 'Saus dressing tidak disertakan dalam paket langganan makan malam',
        ],
        [
            'id'              => '#P2E-1283',
            'slug'            => 'P2E-1283',
            'pelanggan'       => 'Dewi Lestari',
            'pelanggan_badge' => 'Konsumen Terverifikasi',
            'mitra'           => 'Dapur Hijau Organik',
            'mitra_kategori'  => 'Katering Sehat & Organik',
            'status'          => 'Dalam Investigasi',
            'tanggal'         => '13 Nov 2026, 13:10 WIB',
            'subjek'          => 'Porsi protein pada paket High-Protein tidak sesuai informasi kalori di aplikasi',
        ],
    ];

    // Kondisional jika modal dibuka langsung lewat parameter URL ?detail=P2E-1286
    $requestedDetail = request('detail');
    $initialModalReport = collect($laporanList)->firstWhere('slug', $requestedDetail) ?? $laporanList[0];
    $isModalInitiallyOpen = !empty($requestedDetail);

    // Data Aktivitas Terbaru
    $activities = $aktivitasTerbaru ?? [
        [
            'type'  => 'mint',
            'icon'  => 'store',
            'title' => 'Mitra baru mendaftar',
            'time'  => '5 menit lalu',
        ],
        [
            'type'  => 'cream',
            'icon'  => 'utensils',
            'title' => 'Menu menunggu moderasi',
            'time'  => '20 menit lalu',
        ],
        [
            'type'  => 'cream',
            'icon'  => 'flag',
            'title' => 'Laporan pesanan masuk',
            'time'  => '1 jam lalu',
        ],
        [
            'type'  => 'mint',
            'icon'  => 'wallet',
            'title' => 'Pesanan dana diterima',
            'time'  => '2 jam lalu',
        ],
    ];
@endphp

<div class="admin-dashboard">
    {{-- Header Judul & Kontrol Filter / Unduh Laporan --}}
    <div class="admin-page-header">
        <h1 class="admin-page-title">Dashboard Admin</h1>

        <div class="admin-header-controls">
            {{-- Dropdown Filter Periode --}}
            <div class="admin-period-dropdown" id="adminPeriodDropdown">
                <button type="button"
                        class="btn-period-trigger"
                        id="periodTriggerBtn"
                        aria-haspopup="listbox"
                        aria-expanded="false">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                    <span id="periodTriggerLabel">{{ $currentData['label'] }}</span>
                </button>

                <ul class="admin-period-menu is-hidden" id="periodDropdownMenu" role="listbox">
                    <li>
                        <button type="button"
                                class="period-option-btn {{ $activePeriod === '7_hari' ? 'is-selected' : '' }}"
                                data-period="7_hari">
                            <span class="period-dot"></span>
                            <span>7 hari terakhir</span>
                        </button>
                    </li>
                    <li>
                        <button type="button"
                                class="period-option-btn {{ $activePeriod === '1_bulan' ? 'is-selected' : '' }}"
                                data-period="1_bulan">
                            <span class="period-dot"></span>
                            <span>1 bulan terakhir</span>
                        </button>
                    </li>
                    <li>
                        <button type="button"
                                class="period-option-btn {{ $activePeriod === '4_bulan' ? 'is-selected' : '' }}"
                                data-period="4_bulan">
                            <span class="period-dot"></span>
                            <span>4 bulan terakhir</span>
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Tombol Unduh Laporan --}}
            <button type="button" class="btn-download-report" id="btnDownloadReport">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" x2="12" y1="15" y2="3"/>
                </svg>
                <span>Unduh laporan</span>
            </button>
        </div>
    </div>

    {{-- 1. Baris 4 Kartu KPI --}}
    <section class="admin-kpi-grid" aria-label="Ringkasan Statistik">
        {{-- KPI 1: Total Pengguna --}}
        <article class="admin-kpi-card">
            <div class="admin-kpi-top">
                <span class="admin-kpi-label">Total pengguna</span>
                <span class="admin-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
            </div>
            <div class="admin-kpi-value" id="kpiTotalPengguna">{{ $currentData['total_pengguna'] }}</div>
        </article>

        {{-- KPI 2: Mitra UMKM Aktif --}}
        <article class="admin-kpi-card">
            <div class="admin-kpi-top">
                <span class="admin-kpi-label">Mitra UMKM aktif</span>
                <span class="admin-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                        <path d="M2 7h20"/>
                    </svg>
                </span>
            </div>
            <div class="admin-kpi-value" id="kpiMitraAktif">{{ $currentData['mitra_aktif'] }}</div>
        </article>

        {{-- KPI 3: Total Pesanan Masuk --}}
        <article class="admin-kpi-card">
            <div class="admin-kpi-top">
                <span class="admin-kpi-label">Total Pesanan Masuk</span>
                <span class="admin-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                        <path d="M12 17.5v-11"/>
                    </svg>
                </span>
            </div>
            <div class="admin-kpi-value" id="kpiTotalPesanan">{{ $currentData['total_pesanan'] }}</div>
        </article>

        {{-- KPI 4: Transaksi Periode --}}
        <article class="admin-kpi-card">
            <div class="admin-kpi-top">
                <span class="admin-kpi-label" id="kpiTransaksiLabel">{{ $currentData['transaksi_label'] }}</span>
                <span class="admin-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="5" rx="2"/>
                        <line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                </span>
            </div>
            <div class="admin-kpi-value" id="kpiTransaksiNilai">{{ $currentData['transaksi_nilai'] }}</div>
        </article>
    </section>

    {{-- 2. Baris Tengah: Grafik Pesanan Mingguan + Perlu Ditinjau --}}
    <section class="admin-two-col-grid">
        {{-- Kartu Grafik Batang --}}
        <article class="admin-panel-card">
            <div class="admin-panel-head">
                <div class="admin-panel-head-col">
                    <h2 class="admin-panel-title">Pesanan mingguan</h2>
                    <p class="admin-panel-subtitle" id="chartTotalSubtitle">{{ $currentData['chart_subtitle'] }}</p>
                </div>
            </div>

            <div class="admin-chart-wrap">
                <div class="admin-chart-yaxis" aria-hidden="true">
                    <span>1.500</span>
                    <span>1.000</span>
                    <span>500</span>
                    <span>0</span>
                </div>

                <div class="admin-chart-plot">
                    <div class="admin-chart-bars" id="adminChartBars">
                        @foreach ($currentData['bars'] as $bar)
                            @php
                                $heightPercent = min(100, max(8, round(($bar['value'] / $maxScale) * 100)));
                            @endphp
                            <div class="admin-bar-item {{ $bar['peak'] ? 'is-peak' : '' }}">
                                <div class="admin-bar-track">
                                    <div class="admin-bar-fill {{ $bar['peak'] ? 'is-peak' : '' }}"
                                         style="height: {{ $heightPercent }}%;"
                                         title="{{ $bar['label'] }}: {{ number_format($bar['value'], 0, ',', '.') }} pesanan">
                                        <span class="admin-bar-tooltip">{{ number_format($bar['value'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <span class="admin-bar-label">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>

        {{-- Kartu Perlu Ditinjau --}}
        <article class="admin-panel-card">
            <div class="admin-panel-head">
                <h2 class="admin-panel-title">Perlu ditinjau</h2>
            </div>

            <div class="admin-review-list">
                {{-- Verifikasi Mitra --}}
                <div class="admin-review-row">
                    <div class="admin-review-left">
                        <span class="admin-soft-icon-box">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                <path d="M2 7h20"/>
                            </svg>
                        </span>
                        <span class="admin-review-name">Verifikasi mitra</span>
                    </div>

                    <div class="admin-review-right">
                        <span class="admin-count-pill">{{ $verifikasiMitraCount }}</span>
                        <a href="{{ route('admin.umkm') }}" class="btn-review-action">Tinjau</a>
                    </div>
                </div>

                {{-- Laporan Pengguna --}}
                <div class="admin-review-row">
                    <div class="admin-review-left">
                        <span class="admin-soft-icon-box">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                <line x1="4" x2="4" y1="22" y2="15"/>
                            </svg>
                        </span>
                        <span class="admin-review-name">Laporan pengguna</span>
                    </div>

                    <div class="admin-review-right">
                        <span class="admin-count-pill">{{ $laporanPenggunaCount }}</span>
                        <a href="#laporan-aduan-section" class="btn-review-action">Tinjau</a>
                    </div>
                </div>
            </div>
        </article>
    </section>

    {{-- 3. Baris Bawah: Tabel Laporan Aduan + Aktivitas Terbaru --}}
    <section class="admin-two-col-grid">
        {{-- Kartu Tabel Laporan Aduan --}}
        <article class="admin-panel-card" id="laporan-aduan-section">
            <div class="admin-panel-head">
                <h2 class="admin-panel-title">Laporan Aduan</h2>
                <a href="#laporan-aduan-section" class="admin-see-all-link" id="btnSeeAllReports">Lihat semua</a>
            </div>

            <div class="admin-table-responsive">
                <table class="admin-report-table">
                    <thead>
                        <tr>
                            <th>ID Laporan</th>
                            <th>Pelanggan</th>
                            <th>Mitra UMKM</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanList as $item)
                            <tr>
                                <td class="col-id">{{ $item['id'] }}</td>
                                <td>{{ $item['pelanggan'] }}</td>
                                <td>{{ $item['mitra'] }}</td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn-table-detail js-open-report-modal"
                                            data-id="{{ $item['id'] }}"
                                            data-status="{{ $item['status'] }}"
                                            data-tanggal="{{ $item['tanggal'] }}"
                                            data-pelanggan="{{ $item['pelanggan'] }}"
                                            data-pelanggan-badge="{{ $item['pelanggan_badge'] }}"
                                            data-mitra="{{ $item['mitra'] }}"
                                            data-mitra-kategori="{{ $item['mitra_kategori'] }}"
                                            data-subjek="{{ $item['subjek'] }}">
                                        Lihat Detail...
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination Tabel --}}
            <div class="admin-table-footer">
                <span class="admin-pagination-info" id="tablePaginationInfo">Menampilkan 1–4 dari 1.286</span>

                <nav class="admin-pagination-nav" aria-label="Navigasi Halaman Laporan">
                    <button type="button" class="page-arrow-btn" data-page="prev" aria-label="Halaman sebelumnya">&lt;</button>
                    <button type="button" class="page-num-btn is-active" data-page="1">1</button>
                    <button type="button" class="page-num-btn" data-page="2">2</button>
                    <button type="button" class="page-num-btn" data-page="3">3</button>
                    <span class="page-ellipsis">...</span>
                    <button type="button" class="page-arrow-btn" data-page="next" aria-label="Halaman berikutnya">&gt;</button>
                </nav>
            </div>
        </article>

        {{-- Kartu Aktivitas Terbaru --}}
        <article class="admin-panel-card" id="aktivitas-terbaru-section">
            <div class="admin-panel-head">
                <h2 class="admin-panel-title">Aktivitas terbaru</h2>
                <a href="#aktivitas-terbaru-section" class="admin-see-all-link">Lihat semua</a>
            </div>

            <div class="admin-activity-list">
                @foreach ($activities as $act)
                    <div class="admin-activity-item">
                        <span class="admin-activity-icon {{ $act['type'] === 'mint' ? 'icon-mint' : 'icon-cream' }}">
                            @if ($act['icon'] === 'store')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                    <path d="M2 7h20"/>
                                </svg>
                            @elseif ($act['icon'] === 'utensils')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                    <path d="M7 2v20"/>
                                    <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                                </svg>
                            @elseif ($act['icon'] === 'flag')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                    <line x1="4" x2="4" y1="22" y2="15"/>
                                </svg>
                            @else
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                                    <line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                            @endif
                        </span>

                        <div class="admin-activity-content">
                            <span class="admin-activity-title">{{ $act['title'] }}</span>
                            <span class="admin-activity-time">{{ $act['time'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
</div>

{{-- ========================================================================
     MODAL DIALOG: DETAIL LAPORAN / ADUAN (Sesuai Gambar Group 21.jpg)
     ======================================================================== --}}
<div class="admin-modal-backdrop {{ $isModalInitiallyOpen ? '' : 'is-hidden' }}"
     id="adminReportModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="adminReportModalTitle">
    <div class="admin-modal-card">
        {{-- Modal Header --}}
        <div class="admin-modal-header">
            <button type="button" class="admin-modal-icon-btn js-close-report-modal" aria-label="Kembali">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
            </button>

            <h2 class="admin-modal-title" id="adminReportModalTitle">Detail Laporan / Aduan</h2>

            <div class="admin-modal-header-right">
                <span class="status-investigation-pill">
                    <span class="status-dot"></span>
                    <span id="modalHeaderStatus">{{ $initialModalReport['status'] }}</span>
                </span>

                <button type="button" class="admin-modal-icon-btn js-close-report-modal" aria-label="Tutup dialog">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <hr class="admin-modal-divider">

        {{-- Meta ID Pengaduan & Tanggal Laporan --}}
        <div class="admin-modal-meta-row">
            <div class="admin-modal-meta-left">
                <span class="admin-modal-meta-label">ID Pengaduan</span>
                <div class="admin-modal-id-group">
                    <span class="admin-modal-id-val" id="modalReportId">{{ $initialModalReport['id'] }}</span>
                    <span class="admin-modal-inline-badge" id="modalInlineStatus">{{ $initialModalReport['status'] }}</span>
                </div>
            </div>

            <div class="admin-modal-meta-right">
                <span class="admin-modal-meta-label">Tanggal Laporan</span>
                <span class="admin-modal-date-val" id="modalReportDate">{{ $initialModalReport['tanggal'] }}</span>
            </div>
        </div>

        {{-- Box 2 Kolom: Pelapor & Mitra UMKM Terlapor --}}
        <div class="admin-modal-parties-grid">
            <div class="admin-modal-info-box">
                <span class="admin-modal-box-label">Pelapor</span>
                <span class="admin-modal-box-title" id="modalReporterName">{{ $initialModalReport['pelanggan'] }}</span>
                <span class="admin-modal-box-sub" id="modalReporterBadge">{{ $initialModalReport['pelanggan_badge'] }}</span>
            </div>

            <div class="admin-modal-info-box">
                <span class="admin-modal-box-label">Mitra UMKM Terlapor</span>
                <span class="admin-modal-box-title" id="modalMitraName">{{ $initialModalReport['mitra'] }}</span>
                <span class="admin-modal-box-sub" id="modalMitraCategory">{{ $initialModalReport['mitra_kategori'] }}</span>
            </div>
        </div>

        {{-- Box Subjek Pengaduan --}}
        <div class="admin-modal-info-box">
            <span class="admin-modal-box-label">Subjek Pengaduan</span>
            <p class="admin-modal-subject-text" id="modalReportSubject">{{ $initialModalReport['subjek'] }}</p>
        </div>
    </div>
</div>

{{-- Toast Feedback --}}
<div class="admin-toast is-hidden" id="adminToast" role="status"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodDatasets = @json($periodDatasets);
    let currentPeriod = @json($activePeriod);
    const maxScale = 1500;

    // Elements
    const triggerBtn     = document.getElementById('periodTriggerBtn');
    const triggerLabel   = document.getElementById('periodTriggerLabel');
    const dropdownMenu   = document.getElementById('periodDropdownMenu');
    const optionButtons  = document.querySelectorAll('.period-option-btn');

    const kpiPengguna    = document.getElementById('kpiTotalPengguna');
    const kpiMitra       = document.getElementById('kpiMitraAktif');
    const kpiPesanan     = document.getElementById('kpiTotalPesanan');
    const kpiTrxLabel    = document.getElementById('kpiTransaksiLabel');
    const kpiTrxNilai    = document.getElementById('kpiTransaksiNilai');
    const chartSubtitle  = document.getElementById('chartTotalSubtitle');
    const chartBarsWrap  = document.getElementById('adminChartBars');

    // 1. Toggle Dropdown Filter Periode
    function closeDropdown() {
        dropdownMenu.classList.add('is-hidden');
        triggerBtn.classList.remove('is-open');
        triggerBtn.setAttribute('aria-expanded', 'false');
    }

    function openDropdown() {
        dropdownMenu.classList.remove('is-hidden');
        triggerBtn.classList.add('is-open');
        triggerBtn.setAttribute('aria-expanded', 'true');
    }

    triggerBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (dropdownMenu.classList.contains('is-hidden')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('adminPeriodDropdown').contains(e.target)) {
            closeDropdown();
        }
    });

    // 2. Render Data Kondisional saat Periode Dipilih
    function applyPeriodData(periodKey) {
        const data = periodDatasets[periodKey];
        if (!data) return;

        currentPeriod = periodKey;
        triggerLabel.textContent  = data.label;
        kpiPengguna.textContent   = data.total_pengguna;
        kpiMitra.textContent      = data.mitra_aktif;
        kpiPesanan.textContent    = data.total_pesanan;
        kpiTrxLabel.textContent   = data.transaksi_label;
        kpiTrxNilai.textContent   = data.transaksi_nilai;
        chartSubtitle.textContent = data.chart_subtitle;

        // Update active state pada dropdown
        optionButtons.forEach(btn => {
            btn.classList.toggle('is-selected', btn.dataset.period === periodKey);
        });

        // Render ulang batang grafik
        chartBarsWrap.innerHTML = data.bars.map(bar => {
            const pct = Math.min(100, Math.max(8, Math.round((bar.value / maxScale) * 100)));
            const peakClass = bar.peak ? 'is-peak' : '';
            const formattedVal = Number(bar.value).toLocaleString('id-ID');
            return `
                <div class="admin-bar-item ${peakClass}">
                    <div class="admin-bar-track">
                        <div class="admin-bar-fill ${peakClass}"
                             style="height: ${pct}%;"
                             title="${bar.label}: ${formattedVal} pesanan">
                            <span class="admin-bar-tooltip">${formattedVal}</span>
                        </div>
                    </div>
                    <span class="admin-bar-label">${bar.label}</span>
                </div>
            `;
        }).join('');

        // Sinkronkan URL query tanpa reload halaman
        const url = new URL(window.location.href);
        url.searchParams.set('periode', periodKey);
        window.history.replaceState({}, '', url);

        closeDropdown();
    }

    optionButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            applyPeriodData(this.dataset.period);
        });
    });

    // 3. Unduh Laporan CSV
    const toastEl = document.getElementById('adminToast');
    function showToast(message) {
        toastEl.textContent = message;
        toastEl.classList.remove('is-hidden');
        clearTimeout(window.__adminToastTimer);
        window.__adminToastTimer = setTimeout(() => {
            toastEl.classList.add('is-hidden');
        }, 3000);
    }

    document.getElementById('btnDownloadReport').addEventListener('click', function () {
        const data = periodDatasets[currentPeriod];
        const rows = [
            ['Laporan Ringkasan Dashboard Admin Plan2Eat'],
            ['Periode', data.label],
            ['Total Pengguna', data.total_pengguna],
            ['Mitra UMKM Aktif', data.mitra_aktif],
            ['Total Pesanan Masuk', data.total_pesanan],
            [data.transaksi_label, data.transaksi_nilai],
            [],
            ['Label Waktu', 'Jumlah Pesanan'],
            ...data.bars.map(b => [b.label, b.value])
        ];

        const csvContent = 'data:text/csv;charset=utf-8,' + rows.map(r => r.join(',')).join('\n');
        const link = document.createElement('a');
        link.setAttribute('href', encodeURI(csvContent));
        link.setAttribute('download', `laporan-admin-plan2eat-${currentPeriod}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast(`Laporan periode "${data.label}" berhasil diunduh.`);
    });

    // 4. Modal Detail Laporan / Aduan
    const modalBackdrop = document.getElementById('adminReportModal');
    const openModalBtns = document.querySelectorAll('.js-open-report-modal');
    const closeModalBtns = document.querySelectorAll('.js-close-report-modal');

    function openReportModal(dataset) {
        document.getElementById('modalHeaderStatus').textContent   = dataset.status;
        document.getElementById('modalInlineStatus').textContent   = dataset.status;
        document.getElementById('modalReportId').textContent       = dataset.id;
        document.getElementById('modalReportDate').textContent     = dataset.tanggal;
        document.getElementById('modalReporterName').textContent   = dataset.pelanggan;
        document.getElementById('modalReporterBadge').textContent  = dataset.pelangganBadge;
        document.getElementById('modalMitraName').textContent      = dataset.mitra;
        document.getElementById('modalMitraCategory').textContent  = dataset.mitraKategori;
        document.getElementById('modalReportSubject').textContent  = dataset.subjek;

        modalBackdrop.classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReportModal() {
        modalBackdrop.classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    openModalBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            openReportModal(this.dataset);
        });
    });

    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', closeReportModal);
    });

    modalBackdrop.addEventListener('click', function (e) {
        if (e.target === modalBackdrop) {
            closeReportModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modalBackdrop.classList.contains('is-hidden')) {
            closeReportModal();
        }
    });

    // 5. Interaksi Pagination Tabel Laporan Aduan
    const pageButtons = document.querySelectorAll('.page-num-btn');
    const pageInfo = document.getElementById('tablePaginationInfo');
    pageButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            pageButtons.forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
            const p = Number(this.dataset.page);
            const start = (p - 1) * 4 + 1;
            const end = p * 4;
            pageInfo.textContent = `Menampilkan ${start}–${end} dari 1.286`;
        });
    });
});
</script>
@endpush