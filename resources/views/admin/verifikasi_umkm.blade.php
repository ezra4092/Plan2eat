<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra UMKM - Manajemen Usaha | Plan2Eat Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="app-shell admin-shell">
<div class="app-layout">

    {{-- Sidebar / Bottom Navigation --}}
    @include('admin.nav')

    {{-- Area Konten Utama --}}
    <div class="app-content-wrapper">
        {{-- Topbar Breadcrumb & User --}}
        <header class="app-header admin-header">
            <div class="admin-header-inner">
                <div class="admin-header-left">
                    <span class="admin-mobile-logo-badge mobile-only-brand" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.4">
                            <circle cx="12" cy="12" r="8"/>
                            <path d="M12 8c2.2 0 4 1.8 4 4s-1.8 4-4 4"/>
                        </svg>
                    </span>
                    <nav class="admin-breadcrumb" aria-label="Breadcrumb">
                        <span class="breadcrumb-parent">Admin</span>
                        <span class="breadcrumb-sep">/</span>
                        <span class="breadcrumb-parent">Mitra UMKM</span>
                        <span class="breadcrumb-sep">/</span>
                        <span class="breadcrumb-current">Manajemen Usaha</span>
                    </nav>
                </div>

                <div class="admin-header-right">
                    <button type="button" class="btn-notification admin-notif-btn" aria-label="Notifikasi">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </button>
                    <div class="admin-user-chip">
                        <span class="admin-greeting-text">Halo, Admin</span>
                        <span class="admin-initial-avatar">
                            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="app-main-content admin-main-content">
            @php
                $collection = collect($umkmList ?? []);
                $totalMitra = $collection->count();
                $totalAktif = $collection->where('status_verifikasi', 'disetujui')->count();
                $totalDitangguhkan = $collection->where('status_verifikasi', 'ditolak')->count();
                $totalMenunggu = $totalMitra - $totalAktif - $totalDitangguhkan;
            @endphp

            @if(session('success'))
                <div class="mm-toast is-success" role="alert">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mm-toast is-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <div class="admin-dashboard">
                {{-- Header Judul --}}
                <div class="umkm-mgmt-title-row">
                    <h1 class="admin-page-title">Mitra UMKM</h1>
                    <span class="umkm-mgmt-subtitle">Kelola mitra dan profil usaha.</span>
                </div>

                {{-- Sub-navigasi Tab --}}
                <div class="umkm-subtabs" role="tablist">
                    <button type="button" class="umkm-subtab is-active" data-subtab="usaha">Manajemen Usaha</button>
                    <button type="button" class="umkm-subtab" data-subtab="menu">Menu &amp; Paket</button>
                    <button type="button" class="umkm-subtab" data-subtab="transaksi">Transaksi</button>
                </div>

                {{-- Search & Filter Pills --}}
                <div class="umkm-filter-toolbar">
                    <div class="umkm-search-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="search" id="umkmSearchInput" placeholder="Cari nama usaha..." aria-label="Cari nama usaha">
                    </div>

                    <div class="umkm-status-chips" role="group" aria-label="Filter status mitra">
                        <button type="button" class="umkm-chip is-active" data-filter="semua">Semua</button>
                        <button type="button" class="umkm-chip" data-filter="aktif">Aktif</button>
                        <button type="button" class="umkm-chip" data-filter="menunggu">Menunggu verifikasi</button>
                        <button type="button" class="umkm-chip" data-filter="ditangguhkan">Ditangguhkan</button>
                    </div>
                </div>

                {{-- 4 Kartu KPI --}}
                <section class="admin-kpi-grid" aria-label="Ringkasan Mitra UMKM">
                    <div class="admin-kpi-card">
                        <div class="admin-kpi-top">
                            <span class="admin-kpi-label">Total mitra</span>
                            <span class="admin-kpi-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l1.5-5h15L21 9"/>
                                    <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/>
                                    <path d="M5 12v8h14v-8"/>
                                </svg>
                            </span>
                        </div>
                        <span class="admin-kpi-value">{{ number_format($totalMitra, 0, ',', '.') }}</span>
                    </div>

                    <div class="admin-kpi-card">
                        <div class="admin-kpi-top">
                            <span class="admin-kpi-label">Aktif</span>
                            <span class="admin-kpi-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <polyline points="9 12 11 14 15 10"/>
                                </svg>
                            </span>
                        </div>
                        <span class="admin-kpi-value">{{ number_format($totalAktif, 0, ',', '.') }}</span>
                    </div>

                    <div class="admin-kpi-card">
                        <div class="admin-kpi-top">
                            <span class="admin-kpi-label">Menunggu verifikasi</span>
                            <span class="admin-kpi-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <polyline points="12 7 12 12 15 14"/>
                                </svg>
                            </span>
                        </div>
                        <span class="admin-kpi-value">{{ number_format($totalMenunggu, 0, ',', '.') }}</span>
                    </div>

                    <div class="admin-kpi-card">
                        <div class="admin-kpi-top">
                            <span class="admin-kpi-label">Ditangguhkan</span>
                            <span class="admin-kpi-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </span>
                        </div>
                        <span class="admin-kpi-value">{{ number_format($totalDitangguhkan, 0, ',', '.') }}</span>
                    </div>
                </section>

                {{-- Panel Daftar Mitra UMKM --}}
                <section class="admin-panel-card">
                    <div class="admin-panel-head">
                        <h2 class="admin-panel-title">Daftar mitra UMKM</h2>
                        <span class="admin-pagination-info" id="umkmTotalText">{{ $totalMitra }} data</span>
                    </div>

                    <div class="admin-table-responsive">
                        <table class="admin-report-table umkm-mgmt-table">
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
                            <tbody id="umkmTableBody">
                                @forelse($collection as $item)
                                    @php
                                        $rawStatus = strtolower($item->status_verifikasi ?? 'menunggu');
                                        if ($rawStatus === 'disetujui') {
                                            $filterKey = 'aktif';
                                            $statusLabel = 'Aktif';
                                            $badgeClass = 'is-active';
                                        } elseif ($rawStatus === 'ditolak') {
                                            $filterKey = 'ditangguhkan';
                                            $statusLabel = 'Ditangguhkan';
                                            $badgeClass = 'is-suspended';
                                        } else {
                                            $filterKey = 'menunggu';
                                            $statusLabel = 'Menunggu verifikasi';
                                            $badgeClass = 'is-pending';
                                        }

                                        $words = preg_split('/\s+/', trim($item->nama_vendor ?? 'UM'));
                                        $initials = strtoupper(
                                            substr($words[0] ?? 'U', 0, 1) .
                                            substr($words[1] ?? ($words[0] ?? 'M'), 0, 1)
                                        );

                                        $ownerName = $item->user->nama_lengkap ?? 'Pemilik Mitra';
                                        $ownerEmail = $item->user->email ?? '-';
                                        $kategori = $item->kategori ?? 'Katering Sehat';
                                        $rating = isset($item->rating) && $item->rating > 0
                                            ? number_format((float) $item->rating, 1, ',', '.')
                                            : '—';
                                        $pesananBulanIni = $item->pesanan_bulan_ini ?? ($item->pesanan_count ?? 0);
                                        $actionUrl = Route::has('admin.umkm-verifikasi')
                                            ? route('admin.umkm-verifikasi', $item->id_umkm)
                                            : url('/admin/umkm/' . $item->id_umkm . '/verifikasi');
                                    @endphp
                                    <tr class="umkm-row"
                                        data-status="{{ $filterKey }}"
                                        data-search="{{ strtolower(($item->nama_vendor ?? '') . ' ' . $ownerName . ' ' . ($item->alamat_lengkap ?? '')) }}">
                                        <td>
                                            <div class="umkm-vendor-cell">
                                                <span class="umkm-monogram">{{ $initials }}</span>
                                                <div class="umkm-vendor-info">
                                                    <strong>{{ $item->nama_vendor }}</strong>
                                                    <span>Pemilik: {{ $ownerName }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="umkm-location-cell">{{ $item->alamat_lengkap }}</td>
                                        <td>{{ $kategori }}</td>
                                        <td>
                                            <span class="umkm-rating-cell">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                </svg>
                                                {{ $rating }}
                                            </span>
                                        </td>
                                        <td>{{ number_format((int) $pesananBulanIni, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="umkm-status-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn-tinjau-umkm"
                                                    data-action="{{ $actionUrl }}"
                                                    data-nama="{{ $item->nama_vendor }}"
                                                    data-pemilik="{{ $ownerName }}"
                                                    data-email="{{ $ownerEmail }}"
                                                    data-alamat="{{ $item->alamat_lengkap }}"
                                                    data-deskripsi="{{ $item->deskripsi_toko ?? 'Tidak ada deskripsi toko.' }}"
                                                    data-jam="{{ substr($item->jam_buka ?? '08:00', 0, 5) }} - {{ substr($item->jam_tutup ?? '20:00', 0, 5) }} WIB"
                                                    data-pembayaran="{{ $item->metode_pembayaran ?? '-' }}"
                                                    data-status="{{ $rawStatus }}"
                                                    data-catatan="{{ $item->catatan ?? '' }}">
                                                Tinjau
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="umkmEmptyServerRow">
                                        <td colspan="7" class="text-center" style="padding: 28px 14px; color: #717972;">
                                            Belum ada data mitra UMKM terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr id="umkmNoMatchRow" class="is-hidden">
                                    <td colspan="7" class="text-center" style="padding: 28px 14px; color: #717972;">
                                        Tidak ada mitra UMKM yang sesuai dengan pencarian atau filter.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer Pagination --}}
                    <div class="admin-table-footer">
                        <span class="admin-pagination-info" id="umkmShowingInfo">
                            Menampilkan {{ $totalMitra > 0 ? '1–' . $totalMitra : '0' }} dari {{ $totalMitra }}
                        </span>
                        <div class="admin-pagination-nav" aria-label="Navigasi Halaman">
                            <button type="button" class="page-arrow-btn" aria-label="Sebelumnya">&lsaquo;</button>
                            <button type="button" class="page-num-btn is-active">1</button>
                            <button type="button" class="page-num-btn">2</button>
                            <button type="button" class="page-num-btn">3</button>
                            <span class="page-ellipsis">...</span>
                            <button type="button" class="page-arrow-btn" aria-label="Berikutnya">&rsaquo;</button>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</div>

{{-- Modal Tinjau & Verifikasi UMKM (Terhubung ke VerifikasiUmkmController@update) --}}
<div id="tinjauUmkmModal" class="mp-modal-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="modalTinjauTitle">
    <div class="mp-modal-card">
        <div class="mp-modal-header">
            <div class="mp-modal-title-group">
                <div class="mp-modal-icon-badge" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l1.5-5h15L21 9"/>
                        <path d="M5 12v8h14v-8"/>
                    </svg>
                </div>
                <div>
                    <h3 id="modalTinjauTitle" class="mp-modal-title">Tinjau Mitra UMKM</h3>
                    <p id="modalVendorSub" class="mp-modal-subtitle">Verifikasi kelayakan usaha mitra</p>
                </div>
            </div>
            <button type="button" id="closeTinjauModal" class="mp-modal-close-btn" aria-label="Tutup dialog">&times;</button>
        </div>

        <hr class="mp-modal-divider">

        <form id="formVerifikasiUmkm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="mp-modal-body">
                <div class="umkm-modal-detail-box">
                    <div class="umkm-detail-pair">
                        <span>Nama Usaha</span>
                        <strong id="detailNamaVendor">-</strong>
                    </div>
                    <div class="umkm-detail-pair">
                        <span>Pemilik &amp; Email</span>
                        <strong id="detailPemilik">-</strong>
                    </div>
                    <div class="umkm-detail-pair">
                        <span>Jam Operasional</span>
                        <strong id="detailJam">-</strong>
                    </div>
                    <div class="umkm-detail-pair">
                        <span>Metode Pembayaran</span>
                        <strong id="detailPembayaran">-</strong>
                    </div>
                    <div class="umkm-detail-pair is-full">
                        <span>Alamat Lengkap</span>
                        <strong id="detailAlamat">-</strong>
                    </div>
                    <div class="umkm-detail-pair is-full">
                        <span>Deskripsi Toko</span>
                        <p id="detailDeskripsi">-</p>
                    </div>
                </div>

                <div class="mp-modal-field">
                    <label class="mp-modal-label">Keputusan Verifikasi</label>
                    <div class="umkm-decision-grid">
                        <label class="umkm-decision-option is-approve">
                            <input type="radio" name="status_verifikasi" value="disetujui" required>
                            <span>Setujui (Aktifkan)</span>
                        </label>
                        <label class="umkm-decision-option is-reject">
                            <input type="radio" name="status_verifikasi" value="ditolak" required>
                            <span>Tolak / Tangguhkan</span>
                        </label>
                    </div>
                </div>

                <div class="mp-modal-field">
                    <label for="inputCatatanVerifikasi" class="mp-modal-label">Catatan Admin (Opsional)</label>
                    <div class="mp-modal-textarea-wrap">
                        <textarea id="inputCatatanVerifikasi" name="catatan" rows="3" class="mp-modal-textarea" placeholder="Tambahkan catatan persetujuan atau alasan penolakan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="mp-modal-footer" style="margin-top: 14px;">
                <button type="button" id="cancelTinjauModal" class="mp-modal-btn-cancel">Batal</button>
                <button type="submit" class="mp-modal-btn-confirm">Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('umkmSearchInput');
    const chips = document.querySelectorAll('.umkm-chip');
    const rows = document.querySelectorAll('.umkm-row');
    const noMatchRow = document.getElementById('umkmNoMatchRow');
    const showingInfo = document.getElementById('umkmShowingInfo');
    const totalText = document.getElementById('umkmTotalText');
    const totalAll = rows.length;
    let activeFilter = 'semua';

    function applyFilter() {
        const keyword = (searchInput.value || '').toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const matchStatus = activeFilter === 'semua' || row.dataset.status === activeFilter;
            const matchText = !keyword || (row.dataset.search || '').includes(keyword);
            const show = matchStatus && matchText;

            row.classList.toggle('is-hidden', !show);
            if (show) visibleCount++;
        });

        if (noMatchRow) {
            noMatchRow.classList.toggle('is-hidden', visibleCount > 0 || totalAll === 0);
        }
        if (totalText) totalText.textContent = visibleCount + ' data';
        if (showingInfo) {
            showingInfo.textContent = visibleCount > 0
                ? `Menampilkan 1–${visibleCount} dari ${totalAll}`
                : `Menampilkan 0 dari ${totalAll}`;
        }
    }

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('is-active'));
            chip.classList.add('is-active');
            activeFilter = chip.dataset.filter;
            applyFilter();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    // Modal Tinjau UMKM
    const modal = document.getElementById('tinjauUmkmModal');
    const form = document.getElementById('formVerifikasiUmkm');
    const catatanInput = document.getElementById('inputCatatanVerifikasi');

    document.querySelectorAll('.btn-tinjau-umkm').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = btn.dataset.action;
            document.getElementById('detailNamaVendor').textContent = btn.dataset.nama;
            document.getElementById('detailPemilik').textContent = `${btn.dataset.pemilik} (${btn.dataset.email})`;
            document.getElementById('detailJam').textContent = btn.dataset.jam;
            document.getElementById('detailPembayaran').textContent = btn.dataset.pembayaran;
            document.getElementById('detailAlamat').textContent = btn.dataset.alamat;
            document.getElementById('detailDeskripsi').textContent = btn.dataset.deskripsi;
            catatanInput.value = btn.dataset.catatan || '';

            const currentStatus = btn.dataset.status;
            form.querySelectorAll('input[name="status_verifikasi"]').forEach(radio => {
                radio.checked = (radio.value === currentStatus);
            });

            modal.classList.remove('is-hidden');
        });
    });

    function closeModal() {
        modal.classList.add('is-hidden');
    }

    document.getElementById('closeTinjauModal')?.addEventListener('click', closeModal);
    document.getElementById('cancelTinjauModal')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('is-hidden')) closeModal();
    });
});
</script>
</body>
</html>