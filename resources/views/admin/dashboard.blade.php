@extends('layouts.main')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard')

@php
    // Data default sesuai desain high-fidelity jika belum dikirim dari Controller
    $kpiStats = $stats ?? [
        'total_umkm'       => '128',
        'umkm_growth'      => '+12% dari bulan lalu',
        'total_users'      => '3,450',
        'users_growth'     => '+8.5% dari bulan lalu',
        'today_orders'     => '412',
        'orders_growth'    => '+5.2% dari kemarin',
        'total_revenue'    => 'Rp 45,2 Jt',
        'revenue_growth'   => '+15.3% dari bulan lalu',
    ];

    $chartData = $monthlyRevenue ?? [
        ['month' => 'Jan', 'value' => 28.0, 'active' => false],
        ['month' => 'Feb', 'value' => 32.0, 'active' => false],
        ['month' => 'Mar', 'value' => 30.0, 'active' => false],
        ['month' => 'Apr', 'value' => 38.0, 'active' => false],
        ['month' => 'Mei', 'value' => 42.0, 'active' => false],
        ['month' => 'Jun', 'value' => 45.2, 'active' => true],
    ];

    $activities = $recentActivities ?? [
        [
            'color' => 'green',
            'title' => 'UMKM Baru Mendaftar',
            'desc'  => 'Warung Sehat Bu Ani mengajukan verifikasi.',
            'time'  => '5 menit lalu',
        ],
        [
            'color' => 'blue',
            'title' => 'Pesanan Selesai',
            'desc'  => 'Pesanan #ORD-8921 telah diterima pelanggan.',
            'time'  => '12 menit lalu',
        ],
        [
            'color' => 'amber',
            'title' => 'Penarikan Dana',
            'desc'  => 'Dapur Organik meminta pencairan Rp 2.500.000.',
            'time'  => '1 jam lalu',
        ],
        [
            'color' => 'green',
            'title' => 'Pengguna Baru',
            'desc'  => 'Rina Sari bergabung sebagai pelanggan.',
            'time'  => '2 jam lalu',
        ],
    ];

    $verifications = $pendingUmkms ?? [
        [
            'id'       => 1,
            'initials' => 'DS',
            'name'     => 'Dapur Sehat Nusantara',
            'owner'    => 'Budi Santoso',
            'category' => 'Katering Diet',
            'date'     => '12 Jun 2024',
            'status'   => 'Menunggu',
        ],
        [
            'id'       => 2,
            'initials' => 'SB',
            'name'     => 'Salad Bar Fresh',
            'owner'    => 'Siti Aminah',
            'category' => 'Makanan Sehat',
            'date'     => '11 Jun 2024',
            'status'   => 'Menunggu',
        ],
        [
            'id'       => 3,
            'initials' => 'KD',
            'name'     => 'Katering Diet Mayo',
            'owner'    => 'Hendra Wijaya',
            'category' => 'Diet Khusus',
            'date'     => '10 Jun 2024',
            'status'   => 'Terverifikasi',
        ],
    ];

    $umkmIndexUrl   = Route::has('admin.umkm.index') ? route('admin.umkm.index') : url('/admin/umkm');
    $laporanIndexUrl = Route::has('admin.laporan.index') ? route('admin.laporan.index') : url('/admin/laporan');
@endphp

@section('content')
<div class="adm-dash">
    {{-- 1. Header Ringkasan Platform & Action Buttons --}}
    <div class="adm-dash-header">
        <div class="adm-dash-heading">
            <h1 class="adm-dash-title">Ringkasan Platform</h1>
            <p class="adm-dash-subtitle">Pantau performa dan aktivitas ekosistem Plan2Eat</p>
        </div>

        <div class="adm-dash-actions">
            {{-- Filter Periode Dropdown --}}
            <div class="adm-period-wrap">
                <button type="button" class="adm-btn-period" id="admPeriodBtn" aria-expanded="false">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span id="admPeriodLabel">{{ request('periode', 'Bulan Ini') }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div class="adm-period-dropdown is-hidden" id="admPeriodMenu">
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'Hari Ini']) }}" class="adm-period-option">Hari Ini</a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'Minggu Ini']) }}" class="adm-period-option">Minggu Ini</a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'Bulan Ini']) }}" class="adm-period-option is-selected">Bulan Ini</a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => '6 Bulan Terakhir']) }}" class="adm-period-option">6 Bulan Terakhir</a>
                </div>
            </div>

            {{-- Tombol Unduh Laporan --}}
            <a href="{{ Route::has('admin.laporan.export') ? route('admin.laporan.export', ['periode' => request('periode', 'Bulan Ini')]) : $laporanIndexUrl }}"
               class="adm-btn-download" id="admDownloadReportBtn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>Unduh Laporan</span>
            </a>
        </div>
    </div>

    {{-- 2. Grid 4 Kartu KPI --}}
    <section class="adm-kpi-grid" aria-label="Statistik Utama Platform">
        {{-- KPI 1: Total Mitra UMKM --}}
        <article class="adm-kpi-card">
            <div class="adm-kpi-top">
                <span class="adm-kpi-label">Total Mitra UMKM</span>
                <span class="adm-kpi-icon" aria-hidden="true">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                        <path d="M12 3v6"/>
                        <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                    </svg>
                </span>
            </div>
            <div class="adm-kpi-value">{{ $kpiStats['total_umkm'] }}</div>
            <div class="adm-kpi-trend">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                <span>{{ $kpiStats['umkm_growth'] }}</span>
            </div>
        </article>

        {{-- KPI 2: Total Pengguna --}}
        <article class="adm-kpi-card">
            <div class="adm-kpi-top">
                <span class="adm-kpi-label">Total Pengguna</span>
                <span class="adm-kpi-icon" aria-hidden="true">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
            </div>
            <div class="adm-kpi-value">{{ $kpiStats['total_users'] }}</div>
            <div class="adm-kpi-trend">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                <span>{{ $kpiStats['users_growth'] }}</span>
            </div>
        </article>

        {{-- KPI 3: Pesanan Hari Ini --}}
        <article class="adm-kpi-card">
            <div class="adm-kpi-top">
                <span class="adm-kpi-label">Pesanan Hari Ini</span>
                <span class="adm-kpi-icon" aria-hidden="true">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </span>
            </div>
            <div class="adm-kpi-value">{{ $kpiStats['today_orders'] }}</div>
            <div class="adm-kpi-trend">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                <span>{{ $kpiStats['orders_growth'] }}</span>
            </div>
        </article>

        {{-- KPI 4: Total Pendapatan --}}
        <article class="adm-kpi-card">
            <div class="adm-kpi-top">
                <span class="adm-kpi-label">Total Pendapatan</span>
                <span class="adm-kpi-icon" aria-hidden="true">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
            <div class="adm-kpi-value">{{ $kpiStats['total_revenue'] }}</div>
            <div class="adm-kpi-trend">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                <span>{{ $kpiStats['revenue_growth'] }}</span>
            </div>
        </article>
    </section>

    {{-- 3. Grid Tengah: Grafik Pendapatan Bulanan + Aktivitas Terbaru --}}
    <section class="adm-middle-grid">
        {{-- Kiri: Kartu Grafik Pendapatan Bulanan --}}
        <article class="adm-card adm-chart-card">
            <div class="adm-card-head">
                <div>
                    <h2 class="adm-card-title">Pendapatan Bulanan</h2>
                    <p class="adm-card-subtitle">Grafik pendapatan 6 bulan terakhir (dalam Juta Rp)</p>
                </div>
                <div class="adm-chart-legend">
                    <span class="adm-legend-dot"></span>
                    <span>Pendapatan</span>
                </div>
            </div>

            <div class="adm-chart-box">
                {{-- Sumbu Y --}}
                <div class="adm-chart-yaxis" aria-hidden="true">
                    <span>50</span>
                    <span>40</span>
                    <span>30</span>
                    <span>20</span>
                    <span>10</span>
                    <span>0</span>
                </div>

                {{-- Area Plot & Batang --}}
                <div class="adm-chart-plot">
                    <div class="adm-chart-gridlines" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span class="is-base"></span>
                    </div>

                    <div class="adm-chart-bars">
                        @foreach ($chartData as $bar)
                            @php
                                $pct = min(100, max(4, ($bar['value'] / 50) * 100));
                            @endphp
                            <div class="adm-bar-col">
                                <div class="adm-bar-track">
                                    <div class="adm-bar-fill {{ !empty($bar['active']) ? 'is-highlight' : '' }}"
                                         style="height: {{ $pct }}%;"
                                         tabindex="0"
                                         aria-label="{{ $bar['month'] }}: Rp {{ number_format($bar['value'], 1, ',', '.') }} Jt">
                                        <span class="adm-bar-tooltip">Rp {{ number_format($bar['value'], 1, ',', '.') }} Jt</span>
                                    </div>
                                </div>
                                <span class="adm-bar-month">{{ $bar['month'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>

        {{-- Kanan: Kartu Aktivitas Terbaru --}}
        <article class="adm-card adm-activity-card">
            <div class="adm-card-head">
                <div>
                    <h2 class="adm-card-title">Aktivitas Terbaru</h2>
                    <p class="adm-card-subtitle">Log aktivitas sistem terkini</p>
                </div>
            </div>

            <ul class="adm-activity-list">
                @foreach ($activities as $item)
                    <li class="adm-activity-item">
                        <span class="adm-activity-dot is-{{ $item['color'] }}" aria-hidden="true"></span>
                        <div class="adm-activity-body">
                            <strong class="adm-activity-title">{{ $item['title'] }}</strong>
                            <p class="adm-activity-desc">{{ $item['desc'] }}</p>
                            <span class="adm-activity-time">{{ $item['time'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="adm-activity-footer">
                <a href="{{ $laporanIndexUrl }}" class="adm-text-link">Lihat Semua Aktivitas</a>
            </div>
        </article>
    </section>

    {{-- 4. Tabel Permintaan Verifikasi UMKM Terbaru --}}
    <section class="adm-card adm-table-card">
        <div class="adm-card-head">
            <div>
                <h2 class="adm-card-title">Permintaan Verifikasi UMKM Terbaru</h2>
                <p class="adm-card-subtitle">Mitra yang menunggu persetujuan dokumen</p>
            </div>
            <a href="{{ $umkmIndexUrl }}" class="adm-text-link">Lihat Semua</a>
        </div>

        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>NAMA USAHA</th>
                        <th>PEMILIK</th>
                        <th>KATEGORI</th>
                        <th>TANGGAL DAFTAR</th>
                        <th>STATUS</th>
                        <th class="is-right">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($verifications as $umkm)
                        @php
                            $isVerified = strtolower($umkm['status']) === 'terverifikasi';
                            $detailUrl  = Route::has('admin.umkm.show')
                                ? route('admin.umkm.show', $umkm['id'])
                                : $umkmIndexUrl . '?umkm=' . $umkm['id'];
                        @endphp
                        <tr>
                            <td>
                                <div class="adm-umkm-cell">
                                    <span class="adm-umkm-monogram">{{ $umkm['initials'] }}</span>
                                    <strong class="adm-umkm-name">{{ $umkm['name'] }}</strong>
                                </div>
                            </td>
                            <td>{{ $umkm['owner'] }}</td>
                            <td>{{ $umkm['category'] }}</td>
                            <td>{{ $umkm['date'] }}</td>
                            <td>
                                <span class="adm-status-badge {{ $isVerified ? 'is-verified' : 'is-pending' }}">
                                    {{ $umkm['status'] }}
                                </span>
                            </td>
                            <td class="is-right">
                                <a href="{{ $detailUrl }}" class="adm-btn-action">
                                    {{ $isVerified ? 'Detail' : 'Tinjau' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="adm-empty-row">Belum ada permintaan verifikasi UMKM baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const periodBtn = document.getElementById('admPeriodBtn');
        const periodMenu = document.getElementById('admPeriodMenu');

        if (periodBtn && periodMenu) {
            periodBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isHidden = periodMenu.classList.toggle('is-hidden');
                periodBtn.setAttribute('aria-expanded', String(!isHidden));
            });

            document.addEventListener('click', function () {
                periodMenu.classList.add('is-hidden');
                periodBtn.setAttribute('aria-expanded', 'false');
            });
        }
    });
</script>
@endpush