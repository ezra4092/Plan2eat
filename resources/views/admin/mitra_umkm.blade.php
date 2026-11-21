@extends('layouts.main')

@section('title', 'Mitra UMKM — Manajemen Usaha | Plan2Eat Admin')

@section('breadcrumb')
    <span class="breadcrumb-parent">Admin</span>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-parent">Mitra UMKM</span>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current" id="aumBreadcrumbTab">Manajemen Usaha</span>
@endsection

@section('content')
@php
    $mitraList = $mitras ?? [
        [
            'initials' => 'DH',
            'nama' => 'Dapur Hijau Organik',
            'pemilik' => 'Rina Wulandari',
            'lokasi' => 'Malabar, Kota Bogor',
            'kategori' => 'Dapur Organik',
            'rating' => '4,9',
            'pesanan' => 328,
            'status' => 'Aktif',
            'status_key' => 'aktif',
        ],
        [
            'initials' => 'WS',
            'nama' => 'Warung Sehat Bu Ari',
            'pemilik' => 'Ari Susanti',
            'lokasi' => 'Baranangsiang, Kota Bogor',
            'kategori' => 'Katering Sehat',
            'rating' => '4,8',
            'pesanan' => 284,
            'status' => 'Aktif',
            'status_key' => 'aktif',
        ],
        [
            'initials' => 'SS',
            'nama' => 'Salad Segar Bogor',
            'pemilik' => 'Dimas Saputra',
            'lokasi' => 'Pajajaran, Kota Bogor',
            'kategori' => 'Salad',
            'rating' => '4,9',
            'pesanan' => 216,
            'status' => 'Aktif',
            'status_key' => 'aktif',
        ],
        [
            'initials' => 'DS',
            'nama' => 'Dapur Sifren',
            'pemilik' => 'Siti Frema',
            'lokasi' => 'Ciomas, Kab. Bogor',
            'kategori' => 'Katering Sehat',
            'rating' => '4,7',
            'pesanan' => 192,
            'status' => 'Aktif',
            'status_key' => 'aktif',
        ],
        [
            'initials' => 'TH',
            'nama' => 'The Housee',
            'pemilik' => 'Andi Prasetyo',
            'lokasi' => 'Tanah Sareal, Kota Bogor',
            'kategori' => 'Katering Sehat',
            'rating' => '4,8',
            'pesanan' => 175,
            'status' => 'Aktif',
            'status_key' => 'aktif',
        ],
        [
            'initials' => 'KR',
            'nama' => 'Kebun Rasa',
            'pemilik' => 'Maya Permata',
            'lokasi' => 'Cibinong, Kab. Bogor',
            'kategori' => 'Dapur Organik',
            'rating' => '—',
            'pesanan' => 0,
            'status' => 'Menunggu verifikasi',
            'status_key' => 'menunggu',
        ],
        [
            'initials' => 'BS',
            'nama' => 'Boga Seimbang',
            'pemilik' => 'Rudi Hartono',
            'lokasi' => 'Dramaga, Kab. Bogor',
            'kategori' => 'Katering Sehat',
            'rating' => '4,6',
            'pesanan' => 86,
            'status' => 'Ditangguhkan',
            'status_key' => 'ditangguhkan',
        ],
    ];
@endphp

<div class="aum-page">
    {{-- Header Judul & Sub-Tab Navigasi --}}
    <div class="aum-page-header">
        <div class="aum-title-row">
            <h1 class="aum-page-title">Mitra UMKM</h1>
            <p class="aum-page-subtitle">Kelola mitra dan profil usaha.</p>
        </div>

        <div class="aum-main-tabs" role="tablist" aria-label="Submenu Mitra UMKM">
            <button type="button" class="aum-main-tab is-active" data-tab-target="tab-usaha" data-tab-label="Manajemen Usaha">
                Manajemen Usaha
            </button>
            <button type="button" class="aum-main-tab" data-tab-target="tab-menu" data-tab-label="Menu & Paket">
                Menu &amp; Paket
            </button>
            <button type="button" class="aum-main-tab" data-tab-target="tab-transaksi" data-tab-label="Transaksi">
                Transaksi
            </button>
        </div>
    </div>

    {{-- =====================================================================
         TAB 1: MANAJEMEN USAHA (Tampilan Utama Sesuai Desain)
         ===================================================================== --}}
    <section id="tab-usaha" class="aum-tab-panel">
        {{-- Search & Filter Status Chips --}}
        <div class="aum-toolbar-row">
            <label class="aum-search-box" for="aumSearchInput">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="search"
                    id="aumSearchInput"
                    class="aum-search-input"
                    placeholder="Cari nama usaha..."
                    autocomplete="off"
                >
            </label>

            <div class="aum-filter-chips" role="group" aria-label="Filter Status Mitra">
                <button type="button" class="aum-chip is-active" data-status-filter="all">Semua</button>
                <button type="button" class="aum-chip" data-status-filter="aktif">Aktif</button>
                <button type="button" class="aum-chip" data-status-filter="menunggu">Menunggu verifikasi</button>
                <button type="button" class="aum-chip" data-status-filter="ditangguhkan">Ditangguhkan</button>
            </div>
        </div>

        {{-- 4 Kartu Ringkasan KPI --}}
        <div class="aum-kpi-grid-4">
            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Total mitra</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                            <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                            <path d="M9 21v-6h6v6"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['total'] ?? 248 }}</div>
            </article>

            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Aktif</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['aktif'] ?? 231 }}</div>
            </article>

            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Menunggu verifikasi</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <polyline points="12 7 12 12 15 14"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['menunggu'] ?? 12 }}</div>
            </article>

            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Ditangguhkan</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['ditangguhkan'] ?? 5 }}</div>
            </article>
        </div>

        {{-- Tabel Daftar Mitra UMKM --}}
        <div class="aum-panel-card">
            <div class="aum-panel-head">
                <h2 class="aum-panel-title">Daftar mitra UMKM</h2>
                <span class="aum-panel-count" id="aumHeaderTotalCount">{{ $stats['total'] ?? 248 }} data</span>
            </div>

            <div class="aum-table-responsive">
                <table class="aum-table" id="aumMitraTable">
                    <thead>
                        <tr>
                            <th>Usaha</th>
                            <th>Lokasi</th>
                            <th>Kategori</th>
                            <th>Rating</th>
                            <th>Pesanan bulan ini</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mitraList as $mitra)
                            @php
                                $pillClass = match ($mitra['status_key']) {
                                    'aktif' => 'is-active',
                                    'menunggu' => 'is-pending',
                                    'ditangguhkan' => 'is-suspended',
                                    default => 'is-active',
                                };
                            @endphp
                            <tr
                                data-status="{{ $mitra['status_key'] }}"
                                data-search="{{ strtolower($mitra['nama'] . ' ' . $mitra['pemilik'] . ' ' . $mitra['lokasi'] . ' ' . $mitra['kategori']) }}"
                            >
                                <td>
                                    <div class="aum-biz-cell">
                                        <span class="aum-initials-badge">{{ $mitra['initials'] }}</span>
                                        <div class="aum-biz-text">
                                            <strong>{{ $mitra['nama'] }}</strong>
                                            <span>Pemilik: {{ $mitra['pemilik'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $mitra['lokasi'] }}</td>
                                <td>{{ $mitra['kategori'] }}</td>
                                <td>
                                    <span class="aum-rating-cell">
                                        <svg class="aum-star-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        <span>{{ $mitra['rating'] }}</span>
                                    </span>
                                </td>
                                <td>{{ $mitra['pesanan'] }}</td>
                                <td>
                                    <span class="aum-status-pill {{ $pillClass }}">
                                        {{ $mitra['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="aum-btn-tinjau" data-mitra-name="{{ $mitra['nama'] }}">
                                        Tinjau
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="aum-table-footer">
                <span class="aum-pagination-info" id="aumPaginationText">Menampilkan 1–7 dari 248</span>

                <nav class="aum-pagination-nav" aria-label="Navigasi Halaman">
                    <button type="button" class="aum-page-btn aum-page-arrow" aria-label="Sebelumnya">&lsaquo;</button>
                    <button type="button" class="aum-page-btn is-active">1</button>
                    <button type="button" class="aum-page-btn">2</button>
                    <button type="button" class="aum-page-btn">3</button>
                    <span class="aum-page-dots">...</span>
                    <button type="button" class="aum-page-btn aum-page-arrow" aria-label="Berikutnya">&rsaquo;</button>
                </nav>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         TAB 2 & TAB 3 (Placeholder Siap Pakai Saat Sub-Tab Diklik)
         ===================================================================== --}}
    <section id="tab-menu" class="aum-tab-panel is-hidden">
        <div class="aum-panel-card">
            <div class="aum-panel-head">
                <h2 class="aum-panel-title">Daftar Menu &amp; Paket Katering Mitra</h2>
                <span class="aum-panel-count">Siap dihubungkan ke controller</span>
            </div>
            <p class="aum-page-subtitle">Gunakan tab ini untuk meninjau katalog menu sehat, informasi gizi, dan status stok dari seluruh mitra UMKM.</p>
        </div>
    </section>

    <section id="tab-transaksi" class="aum-tab-panel is-hidden">
        <div class="aum-panel-card">
            <div class="aum-panel-head">
                <h2 class="aum-panel-title">Riwayat Transaksi Mitra UMKM</h2>
                <span class="aum-panel-count">Siap dihubungkan ke controller</span>
            </div>
            <p class="aum-page-subtitle">Pantau penyelesaian pesanan katering harian dan pencairan dana mitra secara real-time.</p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainTabs = document.querySelectorAll('.aum-main-tab');
    const tabPanels = document.querySelectorAll('.aum-tab-panel');
    const breadcrumbTab = document.getElementById('aumBreadcrumbTab');

    // 1. Switch Sub-Tab (Manajemen Usaha / Menu & Paket / Transaksi)
    mainTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            mainTabs.forEach((t) => t.classList.remove('is-active'));
            tab.classList.add('is-active');

            const targetId = tab.getAttribute('data-tab-target');
            tabPanels.forEach((panel) => {
                panel.classList.toggle('is-hidden', panel.id !== targetId);
            });

            if (breadcrumbTab) {
                breadcrumbTab.textContent = tab.getAttribute('data-tab-label');
            }
        });
    });

    // 2. Pencarian Real-Time & Filter Status
    const searchInput = document.getElementById('aumSearchInput');
    const filterChips = document.querySelectorAll('.aum-chip');
    const tableRows = document.querySelectorAll('#aumMitraTable tbody tr');
    const paginationText = document.getElementById('aumPaginationText');
    let activeStatus = 'all';

    function filterTable() {
        const keyword = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        tableRows.forEach((row) => {
            const rowStatus = row.getAttribute('data-status');
            const rowText = row.getAttribute('data-search') || '';
            const matchStatus = activeStatus === 'all' || rowStatus === activeStatus;
            const matchKeyword = !keyword || rowText.includes(keyword);

            const isVisible = matchStatus && matchKeyword;
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        if (paginationText) {
            if (activeStatus === 'all' && !keyword) {
                paginationText.textContent = 'Menampilkan 1–7 dari 248';
            } else {
                paginationText.textContent = `Menampilkan ${visibleCount} hasil filter`;
            }
        }
    }

    filterChips.forEach((chip) => {
        chip.addEventListener('click', () => {
            filterChips.forEach((c) => c.classList.remove('is-active'));
            chip.classList.add('is-active');
            activeStatus = chip.getAttribute('data-status-filter');
            filterTable();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }

    // 3. Tombol Pagination Aktif
    const pageButtons = document.querySelectorAll('.aum-page-btn:not(.aum-page-arrow)');
    pageButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            pageButtons.forEach((b) => b.classList.remove('is-active'));
            btn.classList.add('is-active');
        });
    });
});
</script>
@endpush