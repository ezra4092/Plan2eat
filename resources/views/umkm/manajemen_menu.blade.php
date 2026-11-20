@extends('layouts.main')

@section('title', 'Manajemen Menu - Plan2Eat')
@section('nav_active', 'menu')
@section('body_class', 'page-menu-management')

@php
    $safe = fn ($name, $param = null) => Route::has($name)
        ? ($param !== null ? route($name, $param) : route($name))
        : '#';

    $urlBeranda = $safe('umkm.dashboard');
    $urlCreate  = $safe('umkm.menu-create');
    $urlUpdate  = $safe('umkm.menu-update', '__ID__');
    $urlDelete  = $safe('umkm.menu-delete', '__ID__');
    $urlStok    = $safe('umkm.menu-stok', '__ID__');
    $urlFlash   = $safe('umkm.flashsale-create');

    $menus    = collect($menus ?? [])->map(fn ($m) => is_array($m) ? (object) $m : $m);
    $total    = $menus->count();
    $tersedia = $menus->filter(fn ($m) => (bool) ($m->status ?? true) && ($m->stok_harian ?? 0) > 0)->count();
    $habis    = $total - $tersedia;

    $jenisMenuList = $jenisMenuList ?? collect();
    if (!isset($kategoriList)) {
        $kategoriList = $jenisMenuList->pluck('nama')->values();
        $kategoriCount = [];
        foreach ($jenisMenuList as $jm) {
            $kategoriCount[$jm->nama] = $menus->where('jenis_menu', $jm->id)->count();
        }
    }
    $kategoriCount = $kategoriCount ?? [];

    $foto = function ($path) {
        if (!$path) return null;
        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    };
@endphp

@section('content')
<div class="mm-page"
     data-url-create="{{ $urlCreate }}"
     data-url-update="{{ $urlUpdate }}"
     data-url-delete="{{ $urlDelete }}"
     data-url-stok="{{ $urlStok }}"
     data-url-flash="{{ $urlFlash }}">

    {{-- Notifikasi Flash --}}
    @if (session('success'))
        <div style="background: #E8F5E9; border-left: 4px solid #2E7D32; color: #1B5E20; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background: #FFEBEE; border-left: 4px solid #C62828; color: #B71C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background: #FFEBEE; border-left: 4px solid #C62828; color: #B71C1C; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kembali --}}
    <div class="mm-back-row">
        <a href="{{ $urlBeranda }}" class="mm-back-link">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    {{-- Pencarian + Ringkasan --}}
    <div class="mm-toolbar">
        <label class="mm-search">
            <svg class="mm-search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" id="mmSearchInput" class="mm-search-input" placeholder="Cari nama menu atau bahan..." autocomplete="off">
            <svg class="mm-search-qr" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 8V5a2 2 0 0 1 2-2h3"/><path d="M16 3h3a2 2 0 0 1 2 2v3"/><path d="M21 16v3a2 2 0 0 1-2 2h-3"/><path d="M8 21H5a2 2 0 0 1-2-2v-3"/>
                <rect x="7" y="7" width="4" height="4"/><rect x="13" y="7" width="4" height="4"/><rect x="7" y="13" width="4" height="4"/><path d="M13 13h4v4"/>
            </svg>
        </label>

        <div class="mm-stats">
            <div class="mm-stat">
                <span class="mm-dot is-dark"></span>
                <span class="mm-stat-label">Total:</span>
                <span class="mm-stat-value">{{ $total }}</span>
            </div>
            <div class="mm-stat">
                <span class="mm-dot is-mint"></span>
                <span class="mm-stat-label">Tersedia:</span>
                <span class="mm-stat-value">{{ $tersedia }}</span>
            </div>
            <div class="mm-stat is-danger">
                <span class="mm-dot is-red"></span>
                <span class="mm-stat-label">Habis:</span>
                <span class="mm-stat-value">{{ $habis }}</span>
            </div>
        </div>
    </div>

    {{-- Filter kategori --}}
    <div class="mm-chips" role="tablist" aria-label="Filter kategori menu">
        <button type="button" class="mm-chip is-active" data-filter="all">Semua ({{ $total }})</button>
        @foreach ($kategoriList as $kat)
            <button type="button" class="mm-chip" data-filter="{{ $kat }}">{{ $kat }} ({{ $kategoriCount[$kat] ?? 0 }})</button>
        @endforeach
    </div>

    {{-- Daftar menu --}}
    <div class="mm-list" id="mmList">
        @forelse ($menus as $m)
            @php
                $isActive = (bool) ($m->status ?? true);
                $soldout = ($m->stok_harian ?? 0) <= 0;
                $isUnavailable = !$isActive || $soldout;
                $img = $foto($m->foto_menu ?? null);
                $kategoriNama = $m->jenisMenu->nama ?? ($m->kategori ?? 'Umum');
                $payload = [
                    'id' => $m->id_menu,
                    'nama' => $m->nama_makanan,
                    'deskripsi' => $m->deskripsi_makanan ?? '',
                    'kalori' => $m->kandungan_kalori ?? '',
                    'stok' => $m->stok_harian ?? 0,
                    'harga' => (int) $m->harga_normal,
                    'foto' => $img,
                    'jenis_menu' => $m->jenis_menu ?? '',
                    'status' => $isActive,
                ];
            @endphp
            <article class="mm-card {{ $isUnavailable ? 'is-soldout' : '' }}"
                     data-kategori="{{ $kategoriNama }}"
                     data-search="{{ strtolower(($m->nama_makanan ?? '') . ' ' . $kategoriNama) }}"
                     data-menu='@json($payload)'>

                <div class="mm-card-main">
                    <div class="mm-thumb">
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $m->nama_makanan }}" loading="lazy">
                        @else
                            <span class="mm-thumb-placeholder">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 3v7a3 3 0 0 0 3 3v8"/><path d="M7 3v6"/><path d="M10 3v7a3 3 0 0 1-3 3"/><path d="M17 3c-2 2-3 5-3 8h3v10"/></svg>
                            </span>
                        @endif

                        @if (!$isActive)
                            <span class="mm-soldout-badge">NONAKTIF</span>
                        @elseif ($soldout)
                            <span class="mm-soldout-badge">HABIS</span>
                        @elseif (!empty($m->badge))
                            <span class="mm-thumb-badge">{{ $m->badge }}</span>
                        @endif
                    </div>

                    <div class="mm-card-info">
                        <div class="mm-card-top">
                            <div class="mm-card-heading">
                                <span class="mm-card-label">{{ strtoupper($kategoriNama) }}</span>
                                <h3 class="mm-card-name">{{ $m->nama_makanan }}</h3>
                            </div>
                            <button type="button" class="mm-edit-btn" data-action="edit" aria-label="Edit {{ $m->nama_makanan }}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                            </button>
                        </div>

                        <div class="mm-price">Rp {{ number_format($m->harga_normal, 0, ',', '.') }}</div>

                        <div class="mm-tags">
                            @foreach (($m->tags ?? []) as $tag)
                                <span class="mm-tag is-{{ $tag[1] }}">
                                    @if (!empty($tag[2]))
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                    @endif
                                    {{ $tag[0] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mm-card-foot">
                    <div class="mm-status">
                        <span class="mm-dot {{ $isActive && !$soldout ? 'is-green' : 'is-red' }}"></span>
                        <span>
                            @if (!$isActive)
                                Status: Nonaktif
                            @elseif ($soldout)
                                Status: Habis
                            @else
                                Status: Aktif (Stok {{ $m->stok_harian }})
                            @endif
                        </span>
                    </div>

                    {{-- Toggle aktif / nonaktif menu (PATCH status) --}}
                    <form method="POST" action="{{ str_replace('__ID__', $m->id_menu, $urlStok) }}" class="mm-switch-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $isActive ? 0 : 1 }}">
                        <button type="submit" class="mm-switch {{ $isActive ? '' : 'is-off' }}"
                                role="switch" aria-checked="{{ $isActive ? 'true' : 'false' }}"
                                aria-label="{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} {{ $m->nama_makanan }}">
                            <span class="mm-switch-thumb"></span>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="mm-empty">Belum ada menu. Tekan tombol <strong>+</strong> untuk menambahkan.</div>
        @endforelse

        <div class="mm-empty is-hidden" id="mmNoResult">Menu tidak ditemukan.</div>
    </div>

    {{-- Tombol Tambah Menu --}}
    <button type="button" class="mm-fab" data-action="create" aria-label="Tambah menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        <span class="mm-fab-text">Tambah Menu</span>
    </button>
</div>

{{-- ===================== MODAL: Tambah / Edit Menu ===================== --}}
<div class="mp-modal-backdrop is-hidden" id="menuModal" role="dialog" aria-modal="true" aria-labelledby="menuModalTitle">
    <form class="mp-modal-card" id="menuForm" method="POST" action="{{ $urlCreate }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="menuMethod" value="POST" disabled>

        <div class="mp-modal-header">
            <div class="mp-modal-title-group">
                <span class="mp-modal-icon-badge">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 3v7a3 3 0 0 0 3 3v8"/><path d="M7 3v6"/><path d="M10 3v7a3 3 0 0 1-3 3"/><path d="M17 3c-2 2-3 5-3 8h3v10"/></svg>
                </span>
                <div>
                    <h2 class="mp-modal-title" id="menuModalTitle">Tambah Menu</h2>
                    <p class="mp-modal-subtitle" id="menuModalSub">Isi detail menu yang akan dijual</p>
                </div>
            </div>
            <button type="button" class="mp-modal-close-btn" data-close="menuModal" aria-label="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <hr class="mp-modal-divider">

        <div class="mp-modal-body">
            <div class="mp-modal-field">
                <label class="mp-modal-label" for="fNama">Nama Makanan</label>
                <div class="mp-modal-input-wrap">
                    <input class="mp-modal-input" type="text" id="fNama" name="nama_makanan" maxlength="255" placeholder="cth. Nasi Box Ayam Rica-Rica" required>
                </div>
            </div>

            <div class="mp-modal-field">
                <label class="mp-modal-label" for="fJenisMenu">Jenis Menu / Kategori</label>
                <div class="mp-modal-input-wrap">
                    <select class="mp-modal-input" id="fJenisMenu" name="jenis_menu" style="cursor: pointer;">
                        <option value="">-- Pilih Jenis Menu --</option>
                        @foreach ($jenisMenuList ?? [] as $jm)
                            <option value="{{ $jm->id }}">{{ $jm->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mp-modal-field">
                <label class="mp-modal-label" for="fDeskripsi">Deskripsi</label>
                <div class="mp-modal-textarea-wrap">
                    <textarea class="mp-modal-textarea" id="fDeskripsi" name="deskripsi_makanan" rows="2" placeholder="Bahan, rasa, atau catatan alergen"></textarea>
                </div>
            </div>

            <div class="mm-modal-grid-2">
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="fHarga">Harga Normal (Rp)</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="number" id="fHarga" name="harga_normal" min="0" step="500" placeholder="20000" required>
                    </div>
                </div>
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="fStok">Stok Harian</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="number" id="fStok" name="stok_harian" min="0" value="0" required>
                    </div>
                </div>
            </div>

            <div class="mm-modal-grid-2">
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="fKalori">Kandungan Kalori (kkal)</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="number" id="fKalori" name="kandungan_kalori" min="0" placeholder="cth. 450">
                    </div>
                </div>
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="fStatus">Status Menu</label>
                    <div class="mp-modal-input-wrap">
                        <select class="mp-modal-input" id="fStatus" name="status" style="cursor: pointer;">
                            <option value="1">Aktif (Tersedia)</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mp-modal-photo-row">
                <img class="mp-modal-avatar-thumb is-hidden" id="fFotoPreview" alt="Foto menu">
                <div class="mp-modal-photo-info">
                    <span class="mp-modal-photo-label">Foto Menu</span>
                    <input type="file" id="fFoto" name="foto_menu" accept="image/*" class="mm-file-input">
                </div>
            </div>
        </div>

        <div class="mm-modal-extra is-hidden" id="menuExtra">
            <button type="button" class="mm-btn-soft is-danger" id="btnAskDelete">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/></svg>
                Hapus
            </button>
            <button type="button" class="mm-btn-soft is-amber" id="btnOpenFlash">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4 14h7l-1 8 9-12h-7z"/></svg>
                Flash Sale
            </button>
        </div>

        <div class="mp-modal-footer">
            <button type="button" class="mp-modal-btn-cancel" data-close="menuModal">Batal</button>
            <button type="submit" class="mp-modal-btn-confirm">Simpan Menu</button>
        </div>
    </form>
</div>

{{-- ===================== MODAL: Konfirmasi Hapus ===================== --}}
<div class="mp-modal-backdrop is-hidden" id="deleteModal" role="dialog" aria-modal="true">
    <form class="mp-modal-card" id="deleteForm" method="POST" action="#">
        @csrf @method('DELETE')
        <div class="mp-modal-header">
            <div class="mp-modal-title-group">
                <span class="mp-modal-icon-badge mm-icon-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/></svg>
                </span>
                <div>
                    <h2 class="mp-modal-title">Hapus Menu?</h2>
                    <p class="mp-modal-subtitle" id="deleteName">-</p>
                </div>
            </div>
        </div>
        <p class="mm-modal-text">Menu beserta flash sale terkait akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <div class="mp-modal-footer">
            <button type="button" class="mp-modal-btn-cancel" data-close="deleteModal">Batal</button>
            <button type="submit" class="mp-modal-btn-confirm mm-btn-danger">Ya, Hapus</button>
        </div>
    </form>
</div>

{{-- ===================== MODAL: Flash Sale ===================== --}}
<div class="mp-modal-backdrop is-hidden" id="flashModal" role="dialog" aria-modal="true" aria-labelledby="flashTitle">
    <form class="mp-modal-card" method="POST" action="{{ $urlFlash }}">
        @csrf
        <input type="hidden" name="id_menu" id="flashIdMenu">

        <div class="mp-modal-header">
            <div class="mp-modal-title-group">
                <span class="mp-modal-icon-badge mm-icon-amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 4 14h7l-1 8 9-12h-7z"/></svg>
                </span>
                <div>
                    <h2 class="mp-modal-title" id="flashTitle">Buat Flash Sale</h2>
                    <p class="mp-modal-subtitle" id="flashMenuName">-</p>
                </div>
            </div>
            <button type="button" class="mp-modal-close-btn" data-close="flashModal" aria-label="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <hr class="mp-modal-divider">

        <div class="mp-modal-body">
            <div class="mm-modal-grid-2">
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="flHarga">Harga Diskon (Rp)</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="number" id="flHarga" name="harga_diskon" min="0" step="500" required>
                    </div>
                </div>
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="flQty">Kuantitas</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="number" id="flQty" name="kuantitas" min="1" required>
                    </div>
                </div>
            </div>

            <div class="mp-modal-field">
                <label class="mp-modal-label" for="flTgl">Tanggal</label>
                <div class="mp-modal-input-wrap">
                    <input class="mp-modal-input" type="date" id="flTgl" name="tgl_flash_sale" required>
                </div>
            </div>

            <div class="mm-modal-grid-2">
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="flMulai">Waktu Mulai</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="time" id="flMulai" name="waktu_mulai" required>
                    </div>
                </div>
                <div class="mp-modal-field">
                    <label class="mp-modal-label" for="flSelesai">Waktu Selesai</label>
                    <div class="mp-modal-input-wrap">
                        <input class="mp-modal-input" type="time" id="flSelesai" name="waktu_selesai" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="mp-modal-footer">
            <button type="button" class="mp-modal-btn-cancel" data-close="flashModal">Batal</button>
            <button type="submit" class="mp-modal-btn-confirm">Aktifkan Flash Sale</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const page = document.querySelector('.mm-page');
    const $ = (id) => document.getElementById(id);
    const urls = {
        create: page.dataset.urlCreate, update: page.dataset.urlUpdate,
        del: page.dataset.urlDelete
    };
    let current = null;

    const open  = (id) => $(id).classList.remove('is-hidden');
    const close = (id) => $(id).classList.add('is-hidden');

    document.querySelectorAll('[data-close]').forEach(b =>
        b.addEventListener('click', () => close(b.dataset.close)));
    document.querySelectorAll('.mp-modal-backdrop').forEach(bd =>
        bd.addEventListener('mousedown', e => { if (e.target === bd) bd.classList.add('is-hidden'); }));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.mp-modal-backdrop').forEach(m => m.classList.add('is-hidden'));
    });

    // ---- Filter kategori + pencarian ----
    const cards = [...document.querySelectorAll('.mm-card')];
    let filter = 'all', keyword = '';
    function applyFilter() {
        let shown = 0;
        cards.forEach(c => {
            const ok = (filter === 'all' || c.dataset.kategori === filter) && c.dataset.search.includes(keyword);
            c.classList.toggle('is-hidden', !ok);
            if (ok) shown++;
        });
        $('mmNoResult').classList.toggle('is-hidden', shown !== 0 || cards.length === 0);
    }
    document.querySelectorAll('.mm-chip').forEach(chip => chip.addEventListener('click', () => {
        document.querySelectorAll('.mm-chip').forEach(c => c.classList.remove('is-active'));
        chip.classList.add('is-active');
        filter = chip.dataset.filter;
        applyFilter();
    }));
    $('mmSearchInput').addEventListener('input', e => { keyword = e.target.value.trim().toLowerCase(); applyFilter(); });

    // ---- Tambah / Edit ----
    const form = $('menuForm');
    function setFoto(src) {
        const img = $('fFotoPreview');
        if (src) { img.src = src; img.classList.remove('is-hidden'); } else { img.classList.add('is-hidden'); }
    }
    function openCreate() {
        current = null;
        form.reset();
        form.action = urls.create;
        $('menuMethod').disabled = true;
        $('menuModalTitle').textContent = 'Tambah Menu';
        $('menuModalSub').textContent = 'Isi detail menu yang akan dijual';
        $('fJenisMenu').value = '';
        $('fStatus').value = '1';
        $('menuExtra').classList.add('is-hidden');
        setFoto(null);
        open('menuModal');
    }
    function openEdit(data) {
        current = data;
        form.reset();
        form.action = urls.update.replace('__ID__', data.id);
        $('menuMethod').value = 'PUT';
        $('menuMethod').disabled = false;
        $('menuModalTitle').textContent = 'Edit Menu';
        $('menuModalSub').textContent = data.nama;
        $('fNama').value = data.nama;
        $('fJenisMenu').value = data.jenis_menu || '';
        $('fDeskripsi').value = data.deskripsi || '';
        $('fHarga').value = data.harga;
        $('fStok').value = data.stok;
        $('fKalori').value = data.kalori || '';
        $('fStatus').value = (data.status === false || data.status === 0 || data.status === '0') ? '0' : '1';
        $('menuExtra').classList.remove('is-hidden');
        setFoto(data.foto);
        open('menuModal');
    }
    document.querySelector('[data-action="create"]').addEventListener('click', openCreate);
    document.querySelectorAll('[data-action="edit"]').forEach(btn => btn.addEventListener('click', () => {
        openEdit(JSON.parse(btn.closest('.mm-card').dataset.menu));
    }));
    $('fFoto').addEventListener('change', e => {
        const f = e.target.files[0];
        if (f) setFoto(URL.createObjectURL(f));
    });

    // ---- Hapus ----
    $('btnAskDelete').addEventListener('click', () => {
        if (!current) return;
        $('deleteForm').action = urls.del.replace('__ID__', current.id);
        $('deleteName').textContent = current.nama;
        close('menuModal');
        open('deleteModal');
    });

    // ---- Flash Sale ----
    $('btnOpenFlash').addEventListener('click', () => {
        if (!current) return;
        $('flashIdMenu').value = current.id;
        $('flashMenuName').textContent = current.nama;
        $('flQty').value = current.stok > 0 ? current.stok : '';
        $('flTgl').value = new Date().toISOString().slice(0, 10);
        close('menuModal');
        open('flashModal');
    });
})();
</script>
@endpush
