@extends('layouts.main', ['activeMenu' => 'data_master'])

@section('title', 'Data Master - Plan2Eat Admin')
@section('page_badge', 'Data Master')

@section('content')
@php
  $tab = $activeTab ?? 'umkm';
  $umkmList = $umkmTerverifikasi ?? collect();
  $customers = $pelangganList ?? collect();
  $jenisList = $jenisMenuList ?? collect();

  $umkmTotal = $umkmList->count();
  $umkmSuspended = $umkmList->filter(fn ($u) => (bool) ($u->user?->is_suspended ?? false))->count();
  $umkmAktif = $umkmTotal - $umkmSuspended;
  $umkmBuka = $umkmList->filter(fn ($u) => ! ($u->user?->is_suspended ?? false) && strtolower((string) $u->status_operasional) === 'buka')->count();
@endphp

<div class="aum-page">
  <header class="aum-page-header">
    <div class="aum-title-row">
      <h1 class="aum-page-title">Data Master</h1>
      <p class="aum-page-subtitle">Kelola mitra UMKM terverifikasi, data pelanggan, dan jenis menu.</p>
    </div>

    <div class="aum-main-tabs" role="tablist" aria-label="Kategori Data Master">
      <button type="button" role="tab"
              class="aum-main-tab {{ $tab === 'umkm' ? 'is-active' : '' }}"
              data-dm-tab="umkm">
        UMKM Terverifikasi ({{ $umkmTotal }})
      </button>
      <button type="button" role="tab"
              class="aum-main-tab {{ $tab === 'pelanggan' ? 'is-active' : '' }}"
              data-dm-tab="pelanggan">
        Data Pelanggan ({{ $customers->count() }})
      </button>
      <button type="button" role="tab"
              class="aum-main-tab {{ $tab === 'jenis_menu' ? 'is-active' : '' }}"
              data-dm-tab="jenis_menu">
        Jenis Menu ({{ $jenisList->count() }})
      </button>
    </div>
  </header>

  {{-- =======================================================================
       TAB 1: UMKM TERVERIFIKASI
       ======================================================================= --}}
  <section class="aum-tab-panel {{ $tab !== 'umkm' ? 'is-hidden' : '' }}" id="dmPanelUmkm">
    <div class="aum-toolbar-row">
      <div class="aum-search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input type="text" id="dmUmkmSearch" class="aum-search-input" placeholder="Cari nama usaha, pemilik, atau lokasi...">
      </div>

      <div class="aum-filter-chips" role="group" aria-label="Filter status UMKM">
        <button type="button" class="aum-chip is-active" data-dm-umkm-filter="all">Semua</button>
        <button type="button" class="aum-chip" data-dm-umkm-filter="aktif">Aktif</button>
        <button type="button" class="aum-chip" data-dm-umkm-filter="ditangguhkan">Ditangguhkan</button>
      </div>
    </div>

    <div class="aum-kpi-grid-4">
      <article class="aum-kpi-card">
        <div class="aum-kpi-top">
          <span class="aum-kpi-label">UMKM terverifikasi</span>
          <span class="aum-kpi-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l2-5h14l2 5"/><path d="M5 12v8h14v-8"/></svg>
          </span>
        </div>
        <div class="aum-kpi-value">{{ $umkmTotal }}</div>
      </article>

      <article class="aum-kpi-card">
        <div class="aum-kpi-top">
          <span class="aum-kpi-label">Aktif</span>
          <span class="aum-kpi-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </span>
        </div>
        <div class="aum-kpi-value">{{ $umkmAktif }}</div>
      </article>

      <article class="aum-kpi-card">
        <div class="aum-kpi-top">
          <span class="aum-kpi-label">Sedang Buka</span>
          <span class="aum-kpi-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </span>
        </div>
        <div class="aum-kpi-value">{{ $umkmBuka }}</div>
      </article>

      <article class="aum-kpi-card">
        <div class="aum-kpi-top">
          <span class="aum-kpi-label">Ditangguhkan</span>
          <span class="aum-kpi-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
          </span>
        </div>
        <div class="aum-kpi-value">{{ $umkmSuspended }}</div>
      </article>
    </div>

    <div class="aum-panel-card">
      <div class="aum-panel-head">
        <h2 class="aum-panel-title">Daftar UMKM Terverifikasi</h2>
        <span class="aum-panel-count" id="dmUmkmCount">{{ $umkmTotal }} data</span>
      </div>

      <div class="aum-table-responsive">
        <table class="aum-table" id="dmUmkmTable">
          <thead>
            <tr>
              <th>Usaha</th>
              <th>Lokasi</th>
              <th>Jam Buka</th>
              <th>Rating</th>
              <th>Pesanan</th>
              <th>Status</th>
              <th>Tinjau</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($umkmList as $u)
              @php
                $isSuspended = (bool) ($u->user?->is_suspended ?? false);
                $statusKey = $isSuspended ? 'ditangguhkan' : 'aktif';
                $words = preg_split('/\s+/', trim((string) $u->nama_vendor)) ?: ['UM'];
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
                $menusPayload = $u->menus->map(function ($m): array {
                    $foto = \App\Models\Menu::resolveFotoUrl($m->foto_menu ?? null);
                    return [
                        'nama' => $m->nama_makanan,
                        'kategori' => $m->jenisMenu?->nama ?? 'Menu Sehat',
                        'harga' => (float) $m->harga_normal,
                        'kalori' => (int) ($m->kalori ?? 0),
                        'stok' => (int) ($m->stok_harian ?? 0),
                        'status' => (bool) $m->status,
                        'foto' => $foto,
                    ];
                })->values()->all();

                $umkmDetailPayload = [
                    'id' => $u->id_umkm,
                    'initials' => $initials !== '' ? $initials : 'UM',
                    'nama_vendor' => $u->nama_vendor,
                    'pemilik' => $u->user?->nama_lengkap ?? '-',
                    'email' => $u->user?->email ?? '-',
                    'no_hp' => $u->user?->no_hp ?? '-',
                    'alamat' => $u->alamat_lengkap ?? '-',
                    'deskripsi' => $u->deskripsi_toko ?? 'Belum ada deskripsi usaha.',
                    'jam' => substr((string) ($u->jam_buka ?? '07:00'), 0, 5) . ' - ' . substr((string) ($u->jam_tutup ?? '21:00'), 0, 5) . ' WIB',
                    'operasional' => $u->status_operasional ?? 'Buka',
                    'rating' => number_format((float) ($u->rating_display ?? 0), 1, ',', '.'),
                    'total_ulasan' => (int) ($u->total_ulasan ?? 0),
                    'total_pesanan' => (int) ($u->total_pesanan ?? 0),
                    'is_suspended' => $isSuspended,
                    'catatan' => $u->catatan ?? '',
                    'foto_toko' => $u->foto_toko ? (str_starts_with((string) $u->foto_toko, 'http') ? $u->foto_toko : asset('storage/' . $u->foto_toko)) : null,
                    'status_url' => route('admin.data_master.umkm_status', $u->id_umkm),
                ];
              @endphp
              <tr data-search="{{ strtolower($u->nama_vendor . ' ' . ($u->user?->nama_lengkap ?? '') . ' ' . $u->alamat_lengkap) }}"
                  data-status="{{ $statusKey }}">
                <td>
                  <div class="aum-biz-cell">
                    <span class="aum-initials-badge">{{ $initials }}</span>
                    <div class="aum-biz-text">
                      <strong>{{ $u->nama_vendor }}</strong>
                      <span>Pemilik: {{ $u->user?->nama_lengkap ?? '-' }}</span>
                    </div>
                  </div>
                </td>
                <td>{{ \Illuminate\Support\Str::limit($u->alamat_lengkap ?? '-', 42) }}</td>
                <td>{{ substr((string) ($u->jam_buka ?? '07:00'), 0, 5) }} - {{ substr((string) ($u->jam_tutup ?? '21:00'), 0, 5) }}</td>
                <td>
                  <div class="aum-rating-cell">
                    <span class="aum-star-icon">&#9733;</span>
                    <span>{{ number_format((float) ($u->rating_display ?? 0), 1, ',', '.') }}</span>
                  </div>
                </td>
                <td>{{ $u->total_pesanan ?? 0 }}</td>
                <td>
                  <span class="aum-status-pill {{ $isSuspended ? 'is-suspended' : 'is-active' }}">
                    {{ $isSuspended ? 'Ditangguhkan' : 'Aktif' }}
                  </span>
                </td>
                <td>
                  <div class="dm-actions-cell">
                    <button type="button"
                            class="aum-btn-tinjau dm-btn-menu"
                            data-open-umkm-menus='@json($menusPayload)'
                            data-umkm-name="{{ $u->nama_vendor }}">
                      Menu ({{ count($menusPayload) }})
                    </button>
                    <button type="button"
                            class="aum-btn-tinjau"
                            data-open-umkm-tinjau='@json($umkmDetailPayload)'>
                      Tinjau
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; padding: 28px; color: #6B7280;">
                  Belum ada mitra UMKM yang terverifikasi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- =======================================================================
       TAB 2: DATA PELANGGAN
       ======================================================================= --}}
  <section class="aum-tab-panel {{ $tab !== 'pelanggan' ? 'is-hidden' : '' }}" id="dmPanelPelanggan">
    <div class="aum-toolbar-row">
      <div class="aum-search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input type="text" id="dmPelangganSearch" class="aum-search-input" placeholder="Cari nama atau email pelanggan...">
      </div>

      <div class="aum-filter-chips" role="group" aria-label="Filter status pelanggan">
        <button type="button" class="aum-chip is-active" data-dm-cust-filter="all">Semua</button>
        <button type="button" class="aum-chip" data-dm-cust-filter="aktif">Aktif</button>
        <button type="button" class="aum-chip" data-dm-cust-filter="ditangguhkan">Ditangguhkan</button>
      </div>
    </div>

    <div class="aum-panel-card">
      <div class="aum-panel-head">
        <h2 class="aum-panel-title">Daftar Pelanggan</h2>
        <span class="aum-panel-count" id="dmPelangganCount">{{ $customers->count() }} data</span>
      </div>

      <div class="aum-table-responsive">
        <table class="aum-table" id="dmPelangganTable">
          <thead>
            <tr>
              <th>Pelanggan</th>
              <th>No. HP</th>
              <th>Total Pesanan</th>
              <th>Saldo Akun</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($customers as $cust)
              @php
                $isCustSuspended = (bool) ($cust->is_suspended ?? false);
                $cWords = preg_split('/\s+/', trim((string) $cust->nama_lengkap)) ?: ['PL'];
                $cInitials = strtoupper(substr($cWords[0], 0, 1) . (isset($cWords[1]) ? substr($cWords[1], 0, 1) : substr($cWords[0], 1, 1)));
              @endphp
              <tr data-search="{{ strtolower($cust->nama_lengkap . ' ' . $cust->email . ' ' . ($cust->no_hp ?? '')) }}"
                  data-status="{{ $isCustSuspended ? 'ditangguhkan' : 'aktif' }}">
                <td>
                  <div class="aum-biz-cell">
                    <span class="aum-initials-badge">{{ $cInitials }}</span>
                    <div class="aum-biz-text">
                      <strong>{{ $cust->nama_lengkap }}</strong>
                      <span>{{ $cust->email }}</span>
                    </div>
                  </div>
                </td>
                <td>{{ $cust->no_hp ?: '-' }}</td>
                <td>{{ $cust->pesanan_count ?? 0 }}</td>
                <td>Rp {{ number_format((float) ($cust->saldo ?? 0), 0, ',', '.') }}</td>
                <td>
                  <span class="aum-status-pill {{ $isCustSuspended ? 'is-suspended' : 'is-active' }}">
                    {{ $isCustSuspended ? 'Ditangguhkan' : 'Aktif' }}
                  </span>
                </td>
                <td>
                  <div class="dm-actions-cell">
                    <form action="{{ route('admin.data_master.pelanggan_suspend', $cust->id_user) }}"
                          method="POST"
                          style="margin: 0;"
                          data-confirm="Yakin ingin {{ $isCustSuspended ? 'mengaktifkan kembali' : 'menangguhkan' }} akun {{ $cust->nama_lengkap }}?"
                          data-confirm-title="Konfirmasi Status Pelanggan"
                          data-confirm-danger="{{ $isCustSuspended ? 'false' : 'true' }}">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="aum-btn-tinjau {{ $isCustSuspended ? 'dm-btn-menu' : 'dm-btn-warn' }}">
                        {{ $isCustSuspended ? 'Aktivasi' : 'Suspend' }}
                      </button>
                    </form>

                    <form action="{{ route('admin.data_master.pelanggan_delete', $cust->id_user) }}"
                          method="POST"
                          style="margin: 0;"
                          data-confirm="Hapus akun pelanggan {{ $cust->nama_lengkap }} secara permanen?"
                          data-confirm-title="Hapus Pelanggan"
                          data-confirm-ok="Ya, Hapus"
                          data-confirm-danger="true">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="aum-btn-tinjau dm-btn-danger">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align: center; padding: 28px; color: #6B7280;">
                  Belum ada data pelanggan terdaftar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- =======================================================================
       TAB 3: JENIS MENU (CRUD)
       ======================================================================= --}}
  <section class="aum-tab-panel {{ $tab !== 'jenis_menu' ? 'is-hidden' : '' }}" id="dmPanelJenis">
    <div class="aum-toolbar-row is-between">
      <div class="aum-search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input type="text" id="dmJenisSearch" class="aum-search-input" placeholder="Cari jenis menu...">
      </div>

      <button type="button" class="dm-btn-primary" id="dmBtnAddJenis">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Tambah Jenis Menu</span>
      </button>
    </div>

    <div class="aum-panel-card">
      <div class="aum-panel-head">
        <h2 class="aum-panel-title">Daftar Jenis Menu</h2>
        <span class="aum-panel-count" id="dmJenisCount">{{ $jenisList->count() }} data</span>
      </div>

      <div class="aum-table-responsive">
        <table class="aum-table" id="dmJenisTable">
          <thead>
            <tr>
              <th style="width: 90px;">ID</th>
              <th>Nama Jenis Menu</th>
              <th>Jumlah Menu Terhubung</th>
              <th style="width: 180px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($jenisList as $jm)
              <tr data-search="{{ strtolower($jm->nama) }}">
                <td>#{{ $jm->id }}</td>
                <td><strong>{{ $jm->nama }}</strong></td>
                <td>{{ $jm->menus_count ?? 0 }} menu</td>
                <td>
                  <div class="dm-actions-cell">
                    <button type="button"
                            class="aum-btn-tinjau"
                            data-edit-jenis-id="{{ $jm->id }}"
                            data-edit-jenis-nama="{{ $jm->nama }}"
                            data-edit-jenis-url="{{ route('admin.data_master.jenis_menu_update', $jm->id) }}">
                      Edit
                    </button>

                    <form action="{{ route('admin.data_master.jenis_menu_delete', $jm->id) }}"
                          method="POST"
                          style="margin: 0;"
                          data-confirm="Hapus jenis menu '{{ $jm->nama }}'?"
                          data-confirm-title="Hapus Jenis Menu"
                          data-confirm-ok="Ya, Hapus"
                          data-confirm-danger="true">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="aum-btn-tinjau dm-btn-danger">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; padding: 28px; color: #6B7280;">
                  Belum ada jenis menu. Klik "Tambah Jenis Menu" untuk menambahkan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

{{-- =========================================================================
     MODAL 1: DAFTAR MENU UMKM
     ========================================================================= --}}
<div class="aum-modal-backdrop is-hidden" id="dmMenuModal" role="dialog" aria-modal="true" aria-labelledby="dmMenuModalTitle">
  <div class="aum-modal-box dm-modal-wide">
    <div class="aum-modal-header">
      <button type="button" class="aum-modal-back" data-dm-close="dmMenuModal" aria-label="Tutup">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <h3 class="aum-modal-title" id="dmMenuModalTitle">Daftar Menu UMKM</h3>
      <span class="aum-modal-spacer"></span>
    </div>

    <div class="dm-modal-body">
      <div id="dmMenuGridContainer"></div>
    </div>
  </div>
</div>

{{-- =========================================================================
     MODAL 2: TINJAU DETAIL & KONTROL AKUN UMKM (Desain Gambar 1 & 3)
     ========================================================================= --}}
<div class="aum-modal-backdrop is-hidden" id="dmTinjauModal" role="dialog" aria-modal="true" aria-labelledby="dmTinjauTitle">
  <div class="aum-modal-box dm-modal-wide">
    <div class="aum-modal-header">
      <button type="button" class="aum-modal-back" data-dm-close="dmTinjauModal" aria-label="Tutup">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <h3 class="aum-modal-title" id="dmTinjauTitle">Detail Mitra UMKM</h3>
      <span class="aum-modal-spacer"></span>
    </div>

    <div class="dm-modal-body">
      <div class="aum-detail-kpi-grid">
        <article class="aum-detail-kpi-card">
          <div class="aum-detail-kpi-info">
            <span class="aum-detail-kpi-label">Total Pesanan</span>
            <div class="aum-detail-kpi-val" id="dmDetOrders">0</div>
            <span class="aum-detail-kpi-sub">Pesanan valid</span>
          </div>
        </article>

        <article class="aum-detail-kpi-card">
          <div class="aum-detail-kpi-info">
            <span class="aum-detail-kpi-label">Rating Usaha</span>
            <div class="aum-detail-rating-val">
              <span id="dmDetRating">0,0</span>
            </div>
            <span class="aum-detail-kpi-sub is-star" id="dmDetReviews">0 ulasan pelanggan</span>
          </div>
        </article>

        <article class="aum-detail-kpi-card">
          <div class="aum-detail-kpi-info">
            <span class="aum-detail-kpi-label">Status Mitra</span>
            <div class="aum-status-dot-pill is-active" id="dmDetStatusPill">
              <span class="dot"></span>
              <span id="dmDetStatusLabel">Aktif</span>
            </div>
            <span class="aum-detail-kpi-sub" id="dmDetOperasional">Buka</span>
          </div>
        </article>
      </div>

      <div class="aum-detail-card">
        <div class="aum-biz-hero-row">
          <div class="aum-hero-initials" id="dmDetInitials">UM</div>
          <div class="aum-hero-biz-meta">
            <div class="aum-hero-title-line">
              <h2 id="dmDetNama">-</h2>
            </div>
            <div class="aum-hero-sub-line">
              <span>Pemilik: <strong id="dmDetPemilik">-</strong></span>
            </div>
          </div>
        </div>

        <div class="aum-soft-line"></div>
        <h4 class="aum-sub-heading">Informasi Usaha</h4>

        <div class="aum-info-two-col">
          <div class="aum-info-field">
            <span>Email</span>
            <strong id="dmDetEmail">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Nomor Telepon</span>
            <strong id="dmDetPhone">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Jam Operasional</span>
            <strong id="dmDetJam">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Alamat Usaha</span>
            <strong id="dmDetAlamat">-</strong>
          </div>
        </div>

        <div class="aum-desc-cream-box" style="margin-top: 12px;">
          <span class="aum-caps-label">Deskripsi Usaha</span>
          <p id="dmDetDeskripsi">-</p>
        </div>
      </div>

      <div class="aum-detail-card">
        <div class="aum-control-head">
          <strong>Status &amp; Kontrol Akun</strong>
        </div>
        <p class="aum-control-desc" id="dmDetControlDesc">
          Lakukan penangguhan bila ditemukan pelanggaran standar higienis atau operasional mitra.
        </p>

        <form id="dmUmkmStatusForm" method="POST" action="#">
          @csrf
          @method('PATCH')
          <div class="aum-suspend-field" id="dmSuspendReasonWrap">
            <label for="dmSuspendReason">Alasan penangguhan (opsional):</label>
            <textarea id="dmSuspendReason" name="catatan" rows="2" placeholder="Masukkan alasan..."></textarea>
          </div>

          <div class="aum-suspend-btn-row" style="margin-top: 12px;">
            <button type="button" class="aum-modal-btn-cancel" data-dm-close="dmTinjauModal">Tutup</button>
            <button type="submit" id="dmUmkmStatusSubmitBtn" class="aum-modal-btn-danger-soft">Tangguhkan Mitra</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- =========================================================================
     MODAL 3: TAMBAH / EDIT JENIS MENU
     ========================================================================= --}}
<div class="aum-modal-backdrop is-hidden" id="dmJenisModal" role="dialog" aria-modal="true" aria-labelledby="dmJenisModalTitle">
  <div class="aum-modal-box" style="max-width: 440px;">
    <div class="aum-modal-header">
      <button type="button" class="aum-modal-back" data-dm-close="dmJenisModal" aria-label="Tutup">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <h3 class="aum-modal-title" id="dmJenisModalTitle">Tambah Jenis Menu</h3>
      <span class="aum-modal-spacer"></span>
    </div>

    <div class="dm-modal-body">
      <form id="dmJenisForm" method="POST" action="{{ route('admin.data_master.jenis_menu_store') }}">
        @csrf
        <input type="hidden" name="_method" id="dmJenisMethod" value="PUT" disabled>

        <div style="margin-bottom: 16px;">
          <label for="dmJenisNamaInput" style="display: block; font-size: 13px; font-weight: 700; color: #16442B; margin-bottom: 6px;">
            Nama Jenis Menu
          </label>
          <input type="text" id="dmJenisNamaInput" name="nama" class="dm-input" placeholder="Contoh: Paket Diet Harian" required>
        </div>

        <div class="aum-suspend-btn-row">
          <button type="button" class="aum-modal-btn-cancel" data-dm-close="dmJenisModal">Batalkan</button>
          <button type="submit" class="dm-btn-primary" style="flex: 1; justify-content: center; height: 42px;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // 1. Tab Switcher
  const tabs = document.querySelectorAll('[data-dm-tab]');
  const panels = {
    umkm: document.getElementById('dmPanelUmkm'),
    pelanggan: document.getElementById('dmPanelPelanggan'),
    jenis_menu: document.getElementById('dmPanelJenis')
  };

  tabs.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const target = btn.dataset.dmTab;
      tabs.forEach((b) => b.classList.toggle('is-active', b === btn));
      Object.keys(panels).forEach((key) => {
        if (panels[key]) panels[key].classList.toggle('is-hidden', key !== target);
      });
    });
  });

  // 2. Search & Filter Helper
  function wireTableFilter(tableId, searchInputId, countId, filterChipsSelector) {
    const table = document.getElementById(tableId);
    const searchInput = document.getElementById(searchInputId);
    const countEl = document.getElementById(countId);
    const chips = filterChipsSelector ? document.querySelectorAll(filterChipsSelector) : [];
    let currentFilter = 'all';

    function applyFilter() {
      if (!table) return;
      const q = (searchInput ? searchInput.value : '').trim().toLowerCase();
      let visible = 0;

      table.querySelectorAll('tbody tr[data-search]').forEach(function (tr) {
        const matchSearch = q === '' || (tr.dataset.search || '').includes(q);
        const matchStatus = currentFilter === 'all' || (tr.dataset.status || '') === currentFilter;
        const show = matchSearch && matchStatus;
        tr.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (countEl) countEl.textContent = visible + ' data';
    }

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach((c) => c.classList.remove('is-active'));
        chip.classList.add('is-active');
        currentFilter = chip.dataset.dmUmkmFilter || chip.dataset.dmCustFilter || 'all';
        applyFilter();
      });
    });
  }

  wireTableFilter('dmUmkmTable', 'dmUmkmSearch', 'dmUmkmCount', '[data-dm-umkm-filter]');
  wireTableFilter('dmPelangganTable', 'dmPelangganSearch', 'dmPelangganCount', '[data-dm-cust-filter]');
  wireTableFilter('dmJenisTable', 'dmJenisSearch', 'dmJenisCount', null);

  // 3. Modal Open/Close helpers
  function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
      el.classList.remove('is-hidden');
      document.body.style.overflow = 'hidden';
    }
  }
  function closeModal(id) {
    const el = document.getElementById(id);
    if (el) {
      el.classList.add('is-hidden');
      document.body.style.overflow = '';
    }
  }

  document.querySelectorAll('[data-dm-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeModal(btn.dataset.dmClose);
    });
  });

  document.querySelectorAll('.aum-modal-backdrop').forEach(function (backdrop) {
    backdrop.addEventListener('click', function (e) {
      if (e.target === backdrop) {
        backdrop.classList.add('is-hidden');
        document.body.style.overflow = '';
      }
    });
  });

  // 4. Modal Menu UMKM
  const menuContainer = document.getElementById('dmMenuGridContainer');
  const menuTitle = document.getElementById('dmMenuModalTitle');

  document.querySelectorAll('[data-open-umkm-menus]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const vendor = btn.dataset.umkmName || 'Mitra UMKM';
      const menus = JSON.parse(btn.dataset.openUmkmMenus || '[]');
      menuTitle.textContent = 'Menu ' + vendor;

      if (!menus.length) {
        menuContainer.innerHTML = '<p style="text-align:center; padding: 24px; color:#6B7280; margin:0;">UMKM ini belum memiliki menu terdaftar.</p>';
        openModal('dmMenuModal');
        return;
      }

      const grid = document.createElement('div');
      grid.className = 'dm-menu-modal-grid';

      menus.forEach(function (m) {
        const card = document.createElement('div');
        card.className = 'dm-menu-mini-card';

        if (m.foto) {
          const img = document.createElement('img');
          img.className = 'dm-menu-mini-img';
          img.src = m.foto;
          img.alt = m.nama;
          card.appendChild(img);
        }

        const body = document.createElement('div');
        body.className = 'dm-menu-mini-body';

        const title = document.createElement('h4');
        title.className = 'dm-menu-mini-title';
        title.textContent = m.nama;

        const meta = document.createElement('div');
        meta.className = 'dm-menu-mini-meta';
        meta.textContent = m.kategori + ' · ' + m.kalori + ' kkal · Stok: ' + m.stok;

        const foot = document.createElement('div');
        foot.className = 'dm-menu-mini-foot';
        const priceSpan = document.createElement('span');
        priceSpan.textContent = 'Rp ' + Number(m.harga || 0).toLocaleString('id-ID');
        const statusSpan = document.createElement('span');
        statusSpan.style.fontSize = '11.5px';
        statusSpan.style.color = (m.status && m.stok > 0) ? '#16442B' : '#B91C1C';
        statusSpan.textContent = (m.status && m.stok > 0) ? 'Tersedia' : 'Habis / Nonaktif';
        foot.appendChild(priceSpan);
        foot.appendChild(statusSpan);

        body.appendChild(title);
        body.appendChild(meta);
        body.appendChild(foot);
        card.appendChild(body);
        grid.appendChild(card);
      });

      menuContainer.innerHTML = '';
      menuContainer.appendChild(grid);
      openModal('dmMenuModal');
    });
  });

  // 5. Modal Tinjau Detail UMKM
  document.querySelectorAll('[data-open-umkm-tinjau]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const d = JSON.parse(btn.dataset.openUmkmTinjau || '{}');
      document.getElementById('dmDetOrders').textContent = d.total_pesanan ?? 0;
      document.getElementById('dmDetRating').textContent = d.rating ?? '0,0';
      document.getElementById('dmDetReviews').textContent = (d.total_ulasan ?? 0) + ' ulasan pelanggan';
      document.getElementById('dmDetOperasional').textContent = 'Toko ' + (d.operasional || 'Buka');
      document.getElementById('dmDetInitials').textContent = d.initials || 'UM';
      document.getElementById('dmDetNama').textContent = d.nama_vendor || '-';
      document.getElementById('dmDetPemilik').textContent = d.pemilik || '-';
      document.getElementById('dmDetEmail').textContent = d.email || '-';
      document.getElementById('dmDetPhone').textContent = d.no_hp || '-';
      document.getElementById('dmDetJam').textContent = d.jam || '-';
      document.getElementById('dmDetAlamat').textContent = d.alamat || '-';
      document.getElementById('dmDetDeskripsi').textContent = d.deskripsi || '-';

      const pill = document.getElementById('dmDetStatusPill');
      const statusLabel = document.getElementById('dmDetStatusLabel');
      const reasonWrap = document.getElementById('dmSuspendReasonWrap');
      const reasonInput = document.getElementById('dmSuspendReason');
      const submitBtn = document.getElementById('dmUmkmStatusSubmitBtn');
      const form = document.getElementById('dmUmkmStatusForm');

      form.action = d.status_url || '#';
      reasonInput.value = d.catatan || '';

      if (d.is_suspended) {
        pill.className = 'aum-status-dot-pill is-suspended';
        statusLabel.textContent = 'Ditangguhkan';
        reasonWrap.style.display = 'none';
        submitBtn.className = 'aum-btn-approve-solid';
        submitBtn.textContent = 'Aktifkan Kembali Mitra';
      } else {
        pill.className = 'aum-status-dot-pill is-active';
        statusLabel.textContent = 'Aktif';
        reasonWrap.style.display = '';
        submitBtn.className = 'aum-modal-btn-danger-soft';
        submitBtn.textContent = 'Tangguhkan Mitra';
      }

      openModal('dmTinjauModal');
    });
  });

  // 6. Modal CRUD Jenis Menu
  const jenisForm = document.getElementById('dmJenisForm');
  const jenisMethod = document.getElementById('dmJenisMethod');
  const jenisTitle = document.getElementById('dmJenisModalTitle');
  const jenisInput = document.getElementById('dmJenisNamaInput');
  const storeJenisUrl = @json(route('admin.data_master.jenis_menu_store'));

  document.getElementById('dmBtnAddJenis')?.addEventListener('click', function () {
    jenisForm.action = storeJenisUrl;
    jenisMethod.disabled = true;
    jenisTitle.textContent = 'Tambah Jenis Menu';
    jenisInput.value = '';
    openModal('dmJenisModal');
    setTimeout(() => jenisInput.focus(), 60);
  });

  document.querySelectorAll('[data-edit-jenis-id]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      jenisForm.action = btn.dataset.editJenisUrl;
      jenisMethod.disabled = false;
      jenisTitle.textContent = 'Edit Jenis Menu';
      jenisInput.value = btn.dataset.editJenisNama || '';
      openModal('dmJenisModal');
      setTimeout(() => jenisInput.focus(), 60);
    });
  });
});
</script>
@endpush
