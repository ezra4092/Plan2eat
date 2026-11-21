<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mitra UMKM - {{ $umkm->nama_vendor ?? 'Mitra' }} | Plan2Eat Admin</title>
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
        {{-- Topbar --}}
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
                        <span class="breadcrumb-current">Plan2Eat Platform</span>
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
                $rawStatus = strtolower($umkm->status_verifikasi ?? 'menunggu');

                if ($rawStatus === 'disetujui') {
                    $statusType = 'aktif';
                    $statusLabel = 'Aktif';
                    $statusBadgeClass = 'is-active';
                    $docStatusLabel = 'Terverifikasi';
                    $docBadgeClass = 'is-verified';
                } elseif ($rawStatus === 'ditolak') {
                    $statusType = 'ditangguhkan';
                    $statusLabel = 'Ditangguhkan';
                    $statusBadgeClass = 'is-suspended';
                    $docStatusLabel = 'Ditangguhkan';
                    $docBadgeClass = 'is-rejected';
                } else {
                    $statusType = 'menunggu';
                    $statusLabel = 'Menunggu verifikasi';
                    $statusBadgeClass = 'is-pending';
                    $docStatusLabel = 'Perlu Verifikasi';
                    $docBadgeClass = 'is-pending';
                }

                $words = preg_split('/\s+/', trim($umkm->nama_vendor ?? 'UM'));
                $initials = strtoupper(
                    substr($words[0] ?? 'U', 0, 1) .
                    substr($words[1] ?? ($words[0] ?? 'M'), 0, 1)
                );

                $ownerName = $umkm->user->nama_lengkap ?? 'Pemilik Mitra';
                $ownerEmail = $umkm->user->email ?? '-';
                $ownerPhone = $umkm->user->no_telepon ?? ($umkm->user->no_hp ?? '-');
                $kategoriUsaha = $umkm->kategori ?? 'Dapur Organik (Katering Sehat)';
                $tagKategori = $umkm->tag_kategori ?? 'Katering Sehat & Organik';
                $totalPesanan = $umkm->pesanan_count ?? 0;
                $ratingUsaha = isset($umkm->rating) && $umkm->rating > 0
                    ? number_format((float) $umkm->rating, 1, ',', '.')
                    : '0,0';
                $ulasanCount = $umkm->ulasan_count ?? 0;
                $joinedDate = $umkm->created_at
                    ? \Carbon\Carbon::parse($umkm->created_at)->translatedFormat('j M Y')
                    : '-';

                $ktpUrl = !empty($umkm->foto_ktp)
                    ? asset('storage/' . $umkm->foto_ktp)
                    : 'https://placehold.co/800x500/FAF8F0/16442B?text=Dokumen+KTP+Pemilik+Usaha';
                $tempatUrl = !empty($umkm->foto_toko)
                    ? asset('storage/' . $umkm->foto_toko)
                    : 'https://placehold.co/800x500/FAF8F0/16442B?text=Foto+Tempat+Usaha+%2F+Dapur';
            @endphp

            @if(session('success'))
                <div class="mm-toast is-success" role="alert">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mm-toast is-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <div class="admin-dashboard umkm-detail-page">
                {{-- Tombol Kembali --}}
                <div class="umkm-back-row">
                    <a href="{{ Route::has('admin.umkm') ? route('admin.umkm') : url('/admin/umkm') }}" class="umkm-back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        <span>Kembali ke Manajemen Usaha</span>
                    </a>
                </div>

                {{-- Judul Halaman --}}
                <div class="umkm-mgmt-title-row">
                    <h1 class="admin-page-title">Detail Mitra UMKM</h1>
                    <span class="umkm-mgmt-subtitle">Informasi lengkap mengenai mitra usaha yang terdaftar.</span>
                </div>

                {{-- 3 Kartu Statistik Atas --}}
                <section class="umkm-detail-kpi-grid" aria-label="Statistik Mitra UMKM">
                    <div class="umkm-detail-kpi-card">
                        <div class="umkm-detail-kpi-body">
                            <span class="umkm-detail-kpi-label">Total Pesanan</span>
                            <div class="umkm-detail-kpi-num">{{ number_format((int) $totalPesanan, 0, ',', '.') }}</div>
                            <span class="umkm-detail-kpi-sub">Pesanan terselesaikan</span>
                        </div>
                        <span class="umkm-detail-kpi-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <line x1="3" y1="6" x2="21" y2="6"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </span>
                    </div>

                    <div class="umkm-detail-kpi-card">
                        <div class="umkm-detail-kpi-body">
                            <span class="umkm-detail-kpi-label">Rating Usaha</span>
                            <div class="umkm-detail-kpi-num">
                                {{ $ratingUsaha }} <small>/5.0</small>
                            </div>
                            <span class="umkm-detail-kpi-sub">
                                <span class="star-gold">★</span> {{ number_format((int) $ulasanCount, 0, ',', '.') }} ulasan pelanggan
                            </span>
                        </div>
                        <span class="umkm-detail-kpi-icon is-amber" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <polygon points="12 7 13.5 10.2 17 10.7 14.5 13.1 15.1 16.6 12 14.9 8.9 16.6 9.5 13.1 7 10.7 10.5 10.2 12 7"/>
                            </svg>
                        </span>
                    </div>

                    <div class="umkm-detail-kpi-card">
                        <div class="umkm-detail-kpi-body">
                            <span class="umkm-detail-kpi-label">Status Mitra</span>
                            <div class="umkm-detail-kpi-status">
                                <span class="umkm-dot-pill {{ $statusBadgeClass }}">
                                    <span class="status-dot"></span>
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <span class="umkm-detail-kpi-sub">Sejak {{ $joinedDate }}</span>
                        </div>
                        <span class="umkm-detail-kpi-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.604.852.997 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                        </span>
                    </div>
                </section>

                {{-- Grid Utama 2 Kolom --}}
                <div class="umkm-detail-main-grid">
                    {{-- Kolom Kiri: Profil & Informasi Usaha --}}
                    <section class="admin-panel-card umkm-info-panel">
                        <div class="umkm-profile-header">
                            <span class="umkm-detail-monogram">{{ $initials }}</span>
                            <div class="umkm-profile-heading">
                                <div class="umkm-profile-title-line">
                                    <h2>{{ $umkm->nama_vendor }}</h2>
                                    <span class="umkm-status-pill {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                </div>
                                <div class="umkm-profile-meta-line">
                                    <span>Pemilik: <strong>{{ $ownerName }}</strong></span>
                                    <span class="meta-bullet">•</span>
                                    <span class="umkm-category-tag">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                            <line x1="7" y1="7" x2="7.01" y2="7"/>
                                        </svg>
                                        {{ $tagKategori }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="umkm-detail-divider">

                        <h3 class="umkm-section-heading">Informasi Usaha</h3>

                        <div class="umkm-info-fields-grid">
                            <div class="umkm-info-field">
                                <span class="field-caption">Nama Usaha</span>
                                <strong class="field-val">{{ $umkm->nama_vendor }}</strong>
                            </div>

                            <div class="umkm-info-field">
                                <span class="field-caption">Nama Pemilik</span>
                                <strong class="field-val">{{ $ownerName }}</strong>
                            </div>

                            <div class="umkm-info-field">
                                <span class="field-caption">Email</span>
                                <strong class="field-val">{{ $ownerEmail }}</strong>
                            </div>

                            <div class="umkm-info-field">
                                <span class="field-caption">Nomor Telepon</span>
                                <strong class="field-val">{{ $ownerPhone }}</strong>
                            </div>

                            <div class="umkm-info-field">
                                <span class="field-caption">Kategori Usaha</span>
                                <strong class="field-val">{{ $kategoriUsaha }}</strong>
                            </div>

                            <div class="umkm-info-field">
                                <span class="field-caption">Jam Operasional</span>
                                <strong class="field-val">
                                    {{ substr($umkm->jam_buka ?? '07:00', 0, 5) }} – {{ substr($umkm->jam_tutup ?? '19:00', 0, 5) }} WIB
                                </strong>
                            </div>

                            <div class="umkm-info-field is-full">
                                <span class="field-caption">Alamat Usaha</span>
                                <strong class="field-val is-address">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $umkm->alamat_lengkap }}
                                </strong>
                            </div>
                        </div>

                        <div class="umkm-desc-cream-box">
                            <span class="umkm-desc-label">DESKRIPSI USAHA</span>
                            <p>{{ $umkm->deskripsi_toko ?? 'Belum ada deskripsi usaha yang ditambahkan oleh mitra.' }}</p>
                        </div>
                    </section>

                    {{-- Kolom Kanan: Dokumen Usaha + Status & Kontrol Akun (3 Versi) --}}
                    <div class="umkm-detail-side-stack">
                        {{-- Kartu Dokumen Usaha --}}
                        <section class="admin-panel-card umkm-doc-panel">
                            <div class="umkm-doc-header">
                                <h3 class="umkm-section-heading">Dokumen Usaha</h3>
                                <span class="umkm-doc-badge {{ $docBadgeClass }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="9 12 11 14 15 10"/>
                                    </svg>
                                    {{ $docStatusLabel }}
                                </span>
                            </div>

                            <div class="umkm-doc-list">
                                <div class="umkm-doc-item">
                                    <div class="umkm-doc-left">
                                        <span class="umkm-doc-icon" aria-hidden="true">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                                <circle cx="9" cy="11" r="2"/>
                                                <line x1="14" y1="10" x2="18" y2="10"/>
                                                <line x1="14" y1="14" x2="18" y2="14"/>
                                            </svg>
                                        </span>
                                        <div class="umkm-doc-meta">
                                            <strong>KTP Pemilik Usaha</strong>
                                            <span>JPG • 2.4 MB • {{ $docStatusLabel }}</span>
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="btn-lihat-dokumen"
                                            data-doc-title="KTP Pemilik Usaha"
                                            data-doc-src="{{ $ktpUrl }}">
                                        Lihat Dokumen
                                    </button>
                                </div>

                                <div class="umkm-doc-item">
                                    <div class="umkm-doc-left">
                                        <span class="umkm-doc-icon" aria-hidden="true">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 9l1.5-5h15L21 9"/>
                                                <path d="M5 12v8h14v-8"/>
                                            </svg>
                                        </span>
                                        <div class="umkm-doc-meta">
                                            <strong>Foto Tempat Usaha / Dapur</strong>
                                            <span>PNG • 4.1 MB • {{ $docStatusLabel }}</span>
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="btn-lihat-dokumen"
                                            data-doc-title="Foto Tempat Usaha / Dapur"
                                            data-doc-src="{{ $tempatUrl }}">
                                        Lihat Dokumen
                                    </button>
                                </div>
                            </div>
                        </section>

                        {{-- Kartu Status & Kontrol Akun (3 Versi Berdasarkan Status) --}}
                        <section class="admin-panel-card umkm-control-panel">
                            <div class="umkm-control-head">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                                <span>STATUS &amp; KONTROL AKUN</span>
                            </div>

                            {{-- VERSI 1: AKTIF -> Ada tombol Tangguhkan Mitra --}}
                            @if($statusType === 'aktif')
                                <p class="umkm-control-desc">
                                    Mitra saat ini berstatus aktif dan menerima pesanan katering secara reguler. Lakukan penangguhan jika ditemukan pelanggaran standar higienis atau operasional.
                                </p>
                                <button type="button" id="btnOpenSuspendModal" class="btn-suspend-mitra">
                                    Tangguhkan Mitra
                                </button>

                            {{-- VERSI 2: MENUNGGU VERIFIKASI -> Ada tombol Tolak & Setujui --}}
                            @elseif($statusType === 'menunggu')
                                <p class="umkm-control-desc">
                                    Mitra baru mendaftar dan menunggu peninjauan kelayakan profil serta dokumen usaha sebelum dapat berjualan di Plan2Eat.
                                </p>
                                <div class="umkm-verify-btn-row">
                                    <button type="button" id="btnOpenRejectModal" class="btn-reject-mitra">
                                        Tolak
                                    </button>
                                    <form action="{{ route('admin.umkm-verifikasi', $umkm->id_umkm) }}" method="POST" class="umkm-approve-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_verifikasi" value="disetujui">
                                        <button type="submit" class="btn-approve-mitra">
                                            Setujui Mitra
                                        </button>
                                    </form>
                                </div>

                            {{-- VERSI 3: DITANGGUHKAN -> Read-only, tanpa tombol aksi --}}
                            @else
                                <p class="umkm-control-desc">
                                    Akun mitra ini telah ditangguhkan atau ditolak sehingga tidak dapat menerima pesanan katering baru di platform.
                                </p>
                                @if(!empty($umkm->catatan))
                                    <div class="umkm-suspended-note">
                                        <span>Alasan Penangguhan / Penolakan:</span>
                                        <p>{{ $umkm->catatan }}</p>
                                    </div>
                                @endif
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- MODAL 1: MENANGGUHKAN AKUN MITRA (Versi Aktif) --}}
@if($statusType === 'aktif')
<div id="suspendMitraModal" class="umkm-overlay-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="suspendModalHeading">
    <div class="umkm-action-modal">
        <div class="umkm-action-modal-top">
            <button type="button" class="umkm-modal-back-btn" data-close-modal="suspendMitraModal" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
            </button>
            <h3 id="suspendModalHeading">Menangguhkan Akun Mitra</h3>
            <span class="modal-top-spacer" aria-hidden="true"></span>
        </div>

        <form action="{{ route('admin.umkm-verifikasi', $umkm->id_umkm) }}" method="POST" class="umkm-action-modal-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="status_verifikasi" value="ditolak">

            <label for="suspendReasonInput" class="umkm-modal-field-label">Alasan:</label>
            <textarea id="suspendReasonInput"
                      name="catatan"
                      rows="5"
                      class="umkm-modal-reason-box"
                      placeholder="Masukkan alasan..."
                      required></textarea>

            <p class="umkm-modal-confirm-text">Anda yakin ingin menangguhkan?</p>

            <div class="umkm-modal-dual-buttons">
                <button type="button" class="btn-modal-outline-neutral" data-close-modal="suspendMitraModal">
                    Batalkan
                </button>
                <button type="submit" class="btn-modal-outline-danger">
                    Tangguhkan Mitra
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL 2: TOLAK PENGAJUAN MITRA (Versi Menunggu Verifikasi) --}}
@if($statusType === 'menunggu')
<div id="rejectMitraModal" class="umkm-overlay-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="rejectModalHeading">
    <div class="umkm-action-modal">
        <div class="umkm-action-modal-top">
            <button type="button" class="umkm-modal-back-btn" data-close-modal="rejectMitraModal" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
            </button>
            <h3 id="rejectModalHeading">Tolak Pendaftaran Mitra</h3>
            <span class="modal-top-spacer" aria-hidden="true"></span>
        </div>

        <form action="{{ route('admin.umkm-verifikasi', $umkm->id_umkm) }}" method="POST" class="umkm-action-modal-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="status_verifikasi" value="ditolak">

            <label for="rejectReasonInput" class="umkm-modal-field-label">Alasan Penolakan:</label>
            <textarea id="rejectReasonInput"
                      name="catatan"
                      rows="5"
                      class="umkm-modal-reason-box"
                      placeholder="Masukkan alasan penolakan dokumen atau usaha..."
                      required></textarea>

            <p class="umkm-modal-confirm-text">Anda yakin ingin menolak pendaftaran mitra ini?</p>

            <div class="umkm-modal-dual-buttons">
                <button type="button" class="btn-modal-outline-neutral" data-close-modal="rejectMitraModal">
                    Batalkan
                </button>
                <button type="submit" class="btn-modal-outline-danger">
                    Tolak Mitra
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL 3: PREVIEW DOKUMEN USAHA (KTP / Foto Tempat Usaha) --}}
<div id="docPreviewModal" class="umkm-overlay-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="docPreviewTitle">
    <div class="umkm-doc-preview-modal">
        <div class="umkm-action-modal-top">
            <button type="button" class="umkm-modal-back-btn" data-close-modal="docPreviewModal" aria-label="Tutup preview">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
            </button>
            <h3 id="docPreviewTitle">Dokumen Usaha</h3>
            <span class="modal-top-spacer" aria-hidden="true"></span>
        </div>

        <div class="umkm-doc-preview-stage">
            <img id="docPreviewImage" src="" alt="Preview Dokumen Usaha">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('is-hidden');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('is-hidden');
    }

    // Buka Modal Tangguhkan (Status Aktif)
    document.getElementById('btnOpenSuspendModal')?.addEventListener('click', () => {
        openModal('suspendMitraModal');
    });

    // Buka Modal Tolak (Status Menunggu Verifikasi)
    document.getElementById('btnOpenRejectModal')?.addEventListener('click', () => {
        openModal('rejectMitraModal');
    });

    // Buka Modal Preview Dokumen
    const docImg = document.getElementById('docPreviewImage');
    const docTitle = document.getElementById('docPreviewTitle');
    document.querySelectorAll('.btn-lihat-dokumen').forEach(btn => {
        btn.addEventListener('click', () => {
            docTitle.textContent = btn.dataset.docTitle || 'Dokumen Usaha';
            docImg.src = btn.dataset.docSrc || '';
            openModal('docPreviewModal');
        });
    });

    // Tombol Close / Back di dalam modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            closeModal(btn.dataset.closeModal);
        });
    });

    // Klik di luar area modal untuk menutup
    document.querySelectorAll('.umkm-overlay-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) backdrop.classList.add('is-hidden');
        });
    });

    // Tombol ESC menutup semua modal terbuka
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.umkm-overlay-backdrop').forEach(m => m.classList.add('is-hidden'));
        }
    });
});
</script>
</body>
</html>