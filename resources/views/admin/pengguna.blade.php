@extends('admin.main')

@section('title', 'Pengguna — Pesanan | Admin Plan2Eat')
@section('breadcrumb_parent', 'Pengguna')
@section('breadcrumb_current', 'Pesanan')

@section('content')
@php
    $ordersList = $orders ?? [
        [
            'id' => '#P2E-1286',
            'initials' => 'AA',
            'name' => 'Ahmad Asqzy',
            'email' => 'ahmad.a@gmail.com',
            'mitra' => 'Dapur Hijau Organik',
            'items' => '3 item',
            'total' => 'Rp 101.000',
            'date' => '9 Nov 2026',
            'status' => 'Selesai',
            'status_key' => 'selesai',
            'status_class' => 'is-active',
        ],
        [
            'id' => '#P2E-1285',
            'initials' => 'SR',
            'name' => 'Siti Rahma',
            'email' => 'siti.rahma@gmail.com',
            'mitra' => 'Warung Sehat Bu Ari',
            'items' => '2 item',
            'total' => 'Rp 52.000',
            'date' => '9 Nov 2026',
            'status' => 'Diproses',
            'status_key' => 'diproses',
            'status_class' => 'is-pending',
        ],
        [
            'id' => '#P2E-1284',
            'initials' => 'BS',
            'name' => 'Budi Santoso',
            'email' => 'budi.s@gmail.com',
            'mitra' => 'Salad Segar Bogor',
            'items' => '1 item',
            'total' => 'Rp 33.000',
            'date' => '9 Nov 2026',
            'status' => 'Selesai',
            'status_key' => 'selesai',
            'status_class' => 'is-active',
        ],
        [
            'id' => '#P2E-1283',
            'initials' => 'DL',
            'name' => 'Dewi Lestari',
            'email' => 'dewi.l@gmail.com',
            'mitra' => 'Dapur Hijau Organik',
            'items' => '1 item',
            'total' => 'Rp 24.000',
            'date' => '9 Nov 2026',
            'status' => 'Dikirim',
            'status_key' => 'dikirim',
            'status_class' => 'is-shipped',
        ],
        [
            'id' => '#P2E-1282',
            'initials' => 'RP',
            'name' => 'Rizky Pratama',
            'email' => 'rizky.p@gmail.com',
            'mitra' => 'Dapur Sifren',
            'items' => '2 item',
            'total' => 'Rp 64.000',
            'date' => '9 Nov 2026',
            'status' => 'Diproses',
            'status_key' => 'diproses',
            'status_class' => 'is-pending',
        ],
        [
            'id' => '#P2E-1281',
            'initials' => 'NP',
            'name' => 'Nadia Putri',
            'email' => 'nadia.p@gmail.com',
            'mitra' => 'The Housee',
            'items' => '1 item',
            'total' => 'Rp 45.000',
            'date' => '8 Nov 2026',
            'status' => 'Selesai',
            'status_key' => 'selesai',
            'status_class' => 'is-active',
        ],
        [
            'id' => '#P2E-1280',
            'initials' => 'FH',
            'name' => 'Fajar Hidayat',
            'email' => 'fajar.h@gmail.com',
            'mitra' => 'Kebun Rasa',
            'items' => '2 item',
            'total' => 'Rp 58.000',
            'date' => '8 Nov 2026',
            'status' => 'Dibatalkan',
            'status_key' => 'dibatalkan',
            'status_class' => 'is-suspended',
        ],
        [
            'id' => '#P2E-1279',
            'initials' => 'AW',
            'name' => 'Ayu Wulandari',
            'email' => 'ayu.w@gmail.com',
            'mitra' => 'Boga Seimbang',
            'items' => '3 item',
            'total' => 'Rp 87.000',
            'date' => '8 Nov 2026',
            'status' => 'Selesai',
            'status_key' => 'selesai',
            'status_class' => 'is-active',
        ],
    ];
@endphp

<div class="aum-page apu-page">
    {{-- Header Judul & Sub-Tab --}}
    <div class="aum-page-header">
        <div class="aum-title-row">
            <h1 class="aum-page-title">Pengguna</h1>
            <p class="aum-page-subtitle">Kelola data pengguna dan riwayatnya</p>
        </div>

        <div class="aum-main-tabs" role="tablist" aria-label="Kategori Data Pengguna">
            <button type="button" class="aum-main-tab is-active" data-main-tab="pesanan" role="tab" aria-selected="true">
                Pesanan
            </button>
            <button type="button" class="aum-main-tab" data-main-tab="transaksi" role="tab" aria-selected="false">
                Transaksi
            </button>
        </div>
    </div>

    {{-- =====================================================================
         PANEL 1: PESANAN (Default View sesuai Desain)
         ===================================================================== --}}
    <section id="panelPesanan" class="aum-tab-panel">
        {{-- Toolbar Pencarian, Filter Status & Aksi Kanan --}}
        <div class="aum-toolbar-row is-between apu-toolbar-row">
            <div class="aum-toolbar-left-group apu-toolbar-left">
                <div class="aum-search-box apu-search-box">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="search"
                           id="apuSearchInput"
                           class="aum-search-input"
                           placeholder="Cari nama pengguna atau ID pesanan..."
                           aria-label="Cari nama pengguna atau ID pesanan">
                </div>

                <div class="aum-filter-chips" role="group" aria-label="Filter status pesanan">
                    <button type="button" class="aum-chip is-active" data-status-filter="all">Semua</button>
                    <button type="button" class="aum-chip" data-status-filter="diproses">Diproses</button>
                    <button type="button" class="aum-chip" data-status-filter="dikirim">Dikirim</button>
                    <button type="button" class="aum-chip" data-status-filter="selesai">Selesai</button>
                    <button type="button" class="aum-chip" data-status-filter="dibatalkan">Dibatalkan</button>
                </div>
            </div>

            <div class="aum-trx-actions apu-toolbar-right">
                <button type="button" class="aum-outline-btn" id="btnToggleDateFilter" aria-expanded="false">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Filter tanggal</span>
                </button>

                <button type="button" class="aum-outline-btn" id="btnExportOrders">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span>Ekspor</span>
                </button>
            </div>
        </div>

        {{-- Drawer Filter Tanggal (Opsional saat tombol diklik) --}}
        <div class="aum-date-drawer is-hidden" id="apuDateDrawer">
            <div class="apu-date-drawer-inner">
                <div class="aum-date-field">
                    <label for="apuDateSelect">Tanggal Pesanan:</label>
                    <select id="apuDateSelect">
                        <option value="all">Semua Tanggal</option>
                        <option value="9 Nov 2026">9 Nov 2026</option>
                        <option value="8 Nov 2026">8 Nov 2026</option>
                    </select>
                </div>
                <button type="button" class="aum-chip" id="btnResetFilters">Reset Filter</button>
            </div>
        </div>

        {{-- 3 Kartu Ringkasan KPI --}}
        <div class="apu-kpi-grid-3">
            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Total pesanan</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                            <path d="M3 6h18"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['total_pesanan'] ?? '8.420' }}</div>
            </article>

            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Sedang diproses</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['sedang_diproses'] ?? '126' }}</div>
            </article>

            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Dibatalkan</span>
                    <span class="aum-kpi-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </span>
                </div>
                <div class="aum-kpi-value">{{ $stats['dibatalkan'] ?? '34' }}</div>
            </article>
        </div>

        {{-- Kartu Tabel Daftar Pesanan Pengguna --}}
        <section class="aum-panel-card">
            <div class="aum-panel-head">
                <h2 class="aum-panel-title">Daftar pesanan pengguna</h2>
                <div class="apu-panel-meta-right">
                    <span class="aum-panel-count" id="apuTotalBadge">{{ $stats['total_pesanan'] ?? '8.420' }} data</span>
                    <button type="button" class="apu-more-btn" aria-label="Opsi tabel">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="5" cy="12" r="1.8"/>
                            <circle cx="12" cy="12" r="1.8"/>
                            <circle cx="19" cy="12" r="1.8"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="aum-table-responsive">
                <table class="aum-table apu-orders-table" id="apuOrdersTable">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pengguna</th>
                            <th>Mitra UMKM</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="apu-col-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordersList as $order)
                            <tr data-status="{{ $order['status_key'] }}"
                                data-date="{{ $order['date'] }}"
                                data-search="{{ strtolower($order['id'] . ' ' . $order['name'] . ' ' . $order['email'] . ' ' . $order['mitra']) }}">
                                <td class="aum-trx-id">{{ $order['id'] }}</td>
                                <td>
                                    <div class="aum-biz-cell">
                                        <span class="apu-user-avatar">{{ $order['initials'] }}</span>
                                        <div class="aum-biz-text">
                                            <strong>{{ $order['name'] }}</strong>
                                            <span>{{ $order['email'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $order['mitra'] }}</td>
                                <td>{{ $order['items'] }}</td>
                                <td class="aum-trx-total">{{ $order['total'] }}</td>
                                <td>{{ $order['date'] }}</td>
                                <td>
                                    <span class="aum-status-pill {{ $order['status_class'] }}">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                                <td class="apu-col-action">
                                    <button type="button"
                                            class="apu-eye-btn"
                                            title="Lihat detail pesanan {{ $order['id'] }}"
                                            aria-label="Lihat detail pesanan {{ $order['id'] }}"
                                            data-order-id="{{ $order['id'] }}"
                                            data-order-name="{{ $order['name'] }}"
                                            data-order-email="{{ $order['email'] }}"
                                            data-order-mitra="{{ $order['mitra'] }}"
                                            data-order-items="{{ $order['items'] }}"
                                            data-order-total="{{ $order['total'] }}"
                                            data-order-date="{{ $order['date'] }}"
                                            data-order-status="{{ $order['status'] }}"
                                            data-order-status-class="{{ $order['status_class'] }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <tr id="apuEmptyRow" class="is-hidden">
                            <td colspan="8" class="apu-empty-cell">
                                Tidak ada data pesanan pengguna yang cocok dengan filter saat ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="aum-table-footer">
                <span class="aum-pagination-info" id="apuPaginationInfo">Menampilkan 1–8 dari 8.420</span>

                <nav class="aum-pagination-nav" aria-label="Navigasi halaman">
                    <button type="button" class="aum-page-btn" aria-label="Halaman sebelumnya">&lsaquo;</button>
                    <button type="button" class="aum-page-btn is-active" aria-current="page">1</button>
                    <button type="button" class="aum-page-btn">2</button>
                    <button type="button" class="aum-page-btn">3</button>
                    <span class="aum-page-dots">...</span>
                    <button type="button" class="aum-page-btn" aria-label="Halaman berikutnya">&rsaquo;</button>
                </nav>
            </div>
        </section>
    </section>

    {{-- =====================================================================
         PANEL 2: TRANSAKSI (Aktif saat sub-tab "Transaksi" diklik)
         ===================================================================== --}}
    <section id="panelTransaksi" class="aum-tab-panel is-hidden">
        <div class="apu-kpi-grid-3">
            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Total nilai transaksi</span>
                </div>
                <div class="aum-kpi-value">Rp 428.500.000</div>
            </article>
            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Pembayaran berhasil</span>
                </div>
                <div class="aum-kpi-value">8.260</div>
            </article>
            <article class="aum-kpi-card">
                <div class="aum-kpi-top">
                    <span class="aum-kpi-label">Menunggu verifikasi</span>
                </div>
                <div class="aum-kpi-value">160</div>
            </article>
        </div>

        <section class="aum-panel-card">
            <div class="aum-panel-head">
                <h2 class="aum-panel-title">Riwayat transaksi pengguna</h2>
                <span class="aum-panel-count">8.420 transaksi</span>
            </div>

            <div class="aum-table-responsive">
                <table class="aum-table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Pengguna</th>
                            <th>Metode Pembayaran</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="aum-trx-id">#TRX-9941</td>
                            <td>
                                <div class="aum-biz-cell">
                                    <span class="apu-user-avatar">AA</span>
                                    <div class="aum-biz-text">
                                        <strong>Ahmad Asqzy</strong>
                                        <span>ahmad.a@gmail.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>QRIS Instan</td>
                            <td class="aum-trx-total">Rp 101.000</td>
                            <td>9 Nov 2026</td>
                            <td><span class="aum-status-pill is-active">Lunas</span></td>
                        </tr>
                        <tr>
                            <td class="aum-trx-id">#TRX-9940</td>
                            <td>
                                <div class="aum-biz-cell">
                                    <span class="apu-user-avatar">SR</span>
                                    <div class="aum-biz-text">
                                        <strong>Siti Rahma</strong>
                                        <span>siti.rahma@gmail.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>Transfer Virtual Account</td>
                            <td class="aum-trx-total">Rp 52.000</td>
                            <td>9 Nov 2026</td>
                            <td><span class="aum-status-pill is-pending">Verifikasi</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</div>

{{-- Modal Detail Pesanan (Aksi Mata) --}}
<div class="aum-modal-backdrop is-hidden" id="orderDetailModal" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="aum-modal-box">
        <div class="aum-modal-header">
            <button type="button" class="aum-modal-back" id="closeOrderModal" aria-label="Tutup detail pesanan">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <h3 class="aum-modal-title" id="orderModalTitle">Detail Pesanan Pengguna</h3>
            <span class="aum-modal-spacer"></span>
        </div>

        <div class="apu-detail-body">
            <div class="apu-detail-top-bar">
                <div>
                    <span class="aum-caps-label">ID PESANAN</span>
                    <div class="apu-detail-id" id="modalOrderId">#P2E-1286</div>
                </div>
                <span class="aum-status-pill is-active" id="modalOrderStatus">Selesai</span>
            </div>

            <div class="aum-info-two-col">
                <div class="aum-info-field">
                    <span>Nama Pengguna</span>
                    <strong id="modalOrderName">Ahmad Asqzy</strong>
                </div>
                <div class="aum-info-field">
                    <span>Email Pengguna</span>
                    <strong id="modalOrderEmail">ahmad.a@gmail.com</strong>
                </div>
                <div class="aum-info-field">
                    <span>Mitra UMKM</span>
                    <strong id="modalOrderMitra">Dapur Hijau Organik</strong>
                </div>
                <div class="aum-info-field">
                    <span>Tanggal Pemesanan</span>
                    <strong id="modalOrderDate">9 Nov 2026</strong>
                </div>
                <div class="aum-info-field">
                    <span>Jumlah Item</span>
                    <strong id="modalOrderItems">3 item</strong>
                </div>
                <div class="aum-info-field">
                    <span>Total Pembayaran</span>
                    <strong id="modalOrderTotal">Rp 101.000</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainTabs = document.querySelectorAll('[data-main-tab]');
    const panelPesanan = document.getElementById('panelPesanan');
    const panelTransaksi = document.getElementById('panelTransaksi');
    const breadcrumbCurrent = document.getElementById('adminBreadcrumbCurrent');

    // 1. Switch Sub-Tab (Pesanan vs Transaksi)
    mainTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.mainTab;
            mainTabs.forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');

            if (target === 'pesanan') {
                panelPesanan.classList.remove('is-hidden');
                panelTransaksi.classList.add('is-hidden');
                if (breadcrumbCurrent) breadcrumbCurrent.textContent = 'Pesanan';
            } else {
                panelPesanan.classList.add('is-hidden');
                panelTransaksi.classList.remove('is-hidden');
                if (breadcrumbCurrent) breadcrumbCurrent.textContent = 'Transaksi';
            }
        });
    });

    // 2. Filter Pencarian, Status, & Tanggal
    const searchInput = document.getElementById('apuSearchInput');
    const statusChips = document.querySelectorAll('[data-status-filter]');
    const dateSelect = document.getElementById('apuDateSelect');
    const rows = document.querySelectorAll('#apuOrdersTable tbody tr[data-status]');
    const emptyRow = document.getElementById('apuEmptyRow');
    const paginationInfo = document.getElementById('apuPaginationInfo');

    let activeStatus = 'all';

    function applyOrderFilters() {
        const keyword = (searchInput.value || '').trim().toLowerCase();
        const selectedDate = dateSelect ? dateSelect.value : 'all';
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowDate = row.dataset.date;
            const rowText = row.dataset.search || '';

            const matchStatus = activeStatus === 'all' || rowStatus === activeStatus;
            const matchDate = selectedDate === 'all' || rowDate === selectedDate;
            const matchSearch = keyword === '' || rowText.includes(keyword);

            if (matchStatus && matchDate && matchSearch) {
                row.classList.remove('is-hidden');
                visibleCount++;
            } else {
                row.classList.add('is-hidden');
            }
        });

        if (emptyRow) {
            emptyRow.classList.toggle('is-hidden', visibleCount > 0);
        }

        if (paginationInfo) {
            paginationInfo.textContent = visibleCount > 0
                ? `Menampilkan 1–${visibleCount} dari 8.420`
                : 'Menampilkan 0 dari 8.420';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyOrderFilters);
    }

    statusChips.forEach(chip => {
        chip.addEventListener('click', () => {
            statusChips.forEach(c => c.classList.remove('is-active'));
            chip.classList.add('is-active');
            activeStatus = chip.dataset.statusFilter;
            applyOrderFilters();
        });
    });

    if (dateSelect) {
        dateSelect.addEventListener('change', applyOrderFilters);
    }

    // 3. Toggle Drawer Filter Tanggal & Reset
    const btnToggleDate = document.getElementById('btnToggleDateFilter');
    const dateDrawer = document.getElementById('apuDateDrawer');
    const btnResetFilters = document.getElementById('btnResetFilters');

    if (btnToggleDate && dateDrawer) {
        btnToggleDate.addEventListener('click', () => {
            const isHidden = dateDrawer.classList.toggle('is-hidden');
            btnToggleDate.setAttribute('aria-expanded', String(!isHidden));
        });
    }

    if (btnResetFilters) {
        btnResetFilters.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            if (dateSelect) dateSelect.value = 'all';
            activeStatus = 'all';
            statusChips.forEach(c => c.classList.toggle('is-active', c.dataset.statusFilter === 'all'));
            applyOrderFilters();
        });
    }

    // 4. Ekspor CSV Sederhana dari Baris yang Tampil
    const btnExport = document.getElementById('btnExportOrders');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            const csvLines = ['ID Pesanan,Nama Pengguna,Email,Mitra UMKM,Item,Total,Tanggal,Status'];
            rows.forEach(row => {
                if (!row.classList.contains('is-hidden')) {
                    const btn = row.querySelector('.apu-eye-btn');
                    if (btn) {
                        csvLines.push([
                            btn.dataset.orderId,
                            `"${btn.dataset.orderName}"`,
                            btn.dataset.orderEmail,
                            `"${btn.dataset.orderMitra}"`,
                            btn.dataset.orderItems,
                            `"${btn.dataset.orderTotal}"`,
                            `"${btn.dataset.orderDate}"`,
                            btn.dataset.orderStatus
                        ].join(','));
                    }
                }
            });

            const blob = new Blob([csvLines.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'daftar-pesanan-pengguna-plan2eat.csv';
            link.click();
            URL.revokeObjectURL(url);
        });
    }

    // 5. Modal Detail Pesanan
    const modal = document.getElementById('orderDetailModal');
    const closeModalBtn = document.getElementById('closeOrderModal');

    document.querySelectorAll('.apu-eye-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('modalOrderId').textContent = btn.dataset.orderId;
            document.getElementById('modalOrderName').textContent = btn.dataset.orderName;
            document.getElementById('modalOrderEmail').textContent = btn.dataset.orderEmail;
            document.getElementById('modalOrderMitra').textContent = btn.dataset.orderMitra;
            document.getElementById('modalOrderDate').textContent = btn.dataset.orderDate;
            document.getElementById('modalOrderItems').textContent = btn.dataset.orderItems;
            document.getElementById('modalOrderTotal').textContent = btn.dataset.orderTotal;

            const statusBadge = document.getElementById('modalOrderStatus');
            statusBadge.textContent = btn.dataset.orderStatus;
            statusBadge.className = 'aum-status-pill ' + btn.dataset.orderStatusClass;

            modal.classList.remove('is-hidden');
        });
    });

    if (closeModalBtn && modal) {
        closeModalBtn.addEventListener('click', () => modal.classList.add('is-hidden'));
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('is-hidden');
        });
    }
});
</script>
@endpush