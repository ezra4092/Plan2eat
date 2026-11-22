@extends('layouts.main', ['activeMenu' => 'umkm'])

@section('title', 'Verifikasi UMKM - Plan2Eat Admin')
@section('page_badge', 'Verifikasi UMKM')

@section('content')
@php
  $collection = collect($umkmList ?? []);
  $totalPendaftar = $collection->count();
  $totalMenunggu = $collection->filter(fn ($u) => strtolower((string) $u->status_verifikasi) === 'menunggu')->count();
  $totalDisetujui = $collection->filter(fn ($u) => strtolower((string) $u->status_verifikasi) === 'disetujui')->count();
  $totalDitolak = $collection->filter(fn ($u) => strtolower((string) $u->status_verifikasi) === 'ditolak')->count();
@endphp

<div class="aum-page">
  <header class="aum-page-header">
    <div class="aum-title-row">
      <h1 class="aum-page-title">Verifikasi UMKM</h1>
      <p class="aum-page-subtitle">Tinjau pendaftaran mitra dapur catering baru sebelum disetujui masuk ke sistem.</p>
    </div>
  </header>

  <div class="aum-toolbar-row">
    <div class="aum-search-box">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
      </svg>
      <input type="text" id="vuSearchInput" class="aum-search-input" placeholder="Cari nama usaha, pemilik, atau lokasi...">
    </div>

    <div class="aum-filter-chips" role="group" aria-label="Filter status verifikasi">
      <button type="button" class="aum-chip is-active" data-vu-filter="menunggu">Menunggu ({{ $totalMenunggu }})</button>
      <button type="button" class="aum-chip" data-vu-filter="ditolak">Ditolak ({{ $totalDitolak }})</button>
      <button type="button" class="aum-chip" data-vu-filter="disetujui">Disetujui ({{ $totalDisetujui }})</button>
      <button type="button" class="aum-chip" data-vu-filter="all">Semua ({{ $totalPendaftar }})</button>
    </div>
  </div>

  <div class="aum-kpi-grid-4">
    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Menunggu verifikasi</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value">{{ $totalMenunggu }}</div>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Disetujui</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value">{{ $totalDisetujui }}</div>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Ditolak</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value">{{ $totalDitolak }}</div>
    </article>

    <article class="aum-kpi-card">
      <div class="aum-kpi-top">
        <span class="aum-kpi-label">Total pendaftar</span>
        <span class="aum-kpi-icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l2-5h14l2 5"/><path d="M5 12v8h14v-8"/></svg>
        </span>
      </div>
      <div class="aum-kpi-value">{{ $totalPendaftar }}</div>
    </article>
  </div>

  <div class="aum-panel-card">
    <div class="aum-panel-head">
      <h2 class="aum-panel-title">Daftar Pengajuan Mitra UMKM</h2>
      <span class="aum-panel-count" id="vuCountLabel">{{ $totalMenunggu }} data</span>
    </div>

    <div class="aum-table-responsive">
      <table class="aum-table" id="vuTable">
        <thead>
          <tr>
            <th>Usaha</th>
            <th>Lokasi</th>
            <th>Jam Buka</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($collection as $u)
            @php
              $statusLower = strtolower((string) ($u->status_verifikasi ?? 'menunggu'));
              $words = preg_split('/\s+/', trim((string) $u->nama_vendor)) ?: ['UM'];
              $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1)));
              $statusBadgeClass = match ($statusLower) {
                  'disetujui' => 'is-active',
                  'ditolak'   => 'is-suspended',
                  default     => 'is-pending',
              };
              $statusLabel = match ($statusLower) {
                  'disetujui' => 'Disetujui',
                  'ditolak'   => 'Ditolak',
                  default     => 'Menunggu',
              };
              $tglDaftar = $u->created_at
                  ? \Carbon\Carbon::parse($u->created_at)->translatedFormat('d M Y')
                  : '-';

              $payload = [
                  'id' => $u->id_umkm,
                  'initials' => $initials !== '' ? $initials : 'UM',
                  'nama_vendor' => $u->nama_vendor,
                  'pemilik' => $u->user?->nama_lengkap ?? '-',
                  'email' => $u->user?->email ?? '-',
                  'no_hp' => $u->user?->no_hp ?? '-',
                  'alamat' => $u->alamat_lengkap ?? '-',
                  'deskripsi' => $u->deskripsi_toko ?: 'Belum ada deskripsi usaha.',
                  'jam' => substr((string) ($u->jam_buka ?? '07:00'), 0, 5) . ' - ' . substr((string) ($u->jam_tutup ?? '21:00'), 0, 5) . ' WIB',
                  'tgl_daftar' => $tglDaftar,
                  'status' => $statusLower,
                  'status_label' => $statusLabel,
                  'catatan' => $u->catatan ?? '',
                  'foto_toko' => $u->foto_toko ? (str_starts_with((string) $u->foto_toko, 'http') ? $u->foto_toko : asset('storage/' . $u->foto_toko)) : null,
                  'update_url' => route('admin.umkm-verifikasi', $u->id_umkm),
              ];
            @endphp
            <tr data-search="{{ strtolower($u->nama_vendor . ' ' . ($u->user?->nama_lengkap ?? '') . ' ' . ($u->alamat_lengkap ?? '')) }}"
                data-status="{{ $statusLower }}"
                style="{{ $statusLower !== 'menunggu' ? 'display: none;' : '' }}">
              <td>
                <div class="aum-biz-cell">
                  <span class="aum-initials-badge">{{ $initials }}</span>
                  <div class="aum-biz-text">
                    <strong>{{ $u->nama_vendor }}</strong>
                    <span>Pemilik: {{ $u->user?->nama_lengkap ?? '-' }}</span>
                  </div>
                </div>
              </td>
              <td>{{ \Illuminate\Support\Str::limit($u->alamat_lengkap ?? '-', 44) }}</td>
              <td>{{ substr((string) ($u->jam_buka ?? '07:00'), 0, 5) }} - {{ substr((string) ($u->jam_tutup ?? '21:00'), 0, 5) }}</td>
              <td>{{ $tglDaftar }}</td>
              <td>
                <span class="aum-status-pill {{ $statusBadgeClass }}">
                  {{ $statusLabel }}
                </span>
              </td>
              <td>
                <button type="button"
                        class="aum-btn-tinjau"
                        data-open-vu-tinjau='@json($payload)'>
                  Tinjau
                </button>
              </td>
            </tr>
          @empty
            <tr id="vuDbEmptyRow">
              <td colspan="6" style="text-align: center; padding: 28px; color: #6B7280;">
                Belum ada pengajuan UMKM terdaftar.
              </td>
            </tr>
          @endforelse
          <tr id="vuFilterEmptyRow" style="{{ $totalMenunggu > 0 || $collection->isEmpty() ? 'display: none;' : '' }}">
            <td colspan="6" style="text-align: center; padding: 28px; color: #6B7280;">
              Tidak ada pengajuan UMKM pada status ini.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- =========================================================================
     MODAL 1: TINJAU INFORMASI UMKM & TOMBOL SETUJUI / BATALKAN
     ========================================================================= --}}
<div class="aum-modal-backdrop is-hidden" id="vuTinjauModal" role="dialog" aria-modal="true" aria-labelledby="vuModalTitle">
  <div class="aum-modal-box dm-modal-wide">
    <div class="aum-modal-header">
      <button type="button" class="aum-modal-back" data-vu-close="vuTinjauModal" aria-label="Tutup">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <h3 class="aum-modal-title" id="vuModalTitle">Tinjau Pendaftaran Mitra UMKM</h3>
      <span class="aum-modal-spacer"></span>
    </div>

    <div class="dm-modal-body">
      <div class="aum-detail-card">
        <div class="aum-biz-hero-row">
          <div class="aum-hero-initials" id="vuDetInitials">UM</div>
          <div class="aum-hero-biz-meta">
            <div class="aum-hero-title-line">
              <h2 id="vuDetNama">-</h2>
              <span class="aum-status-pill is-pending" id="vuDetStatusPill">Menunggu</span>
            </div>
            <div class="aum-hero-sub-line">
              <span>Pemilik: <strong id="vuDetPemilik">-</strong></span>
              <span>&middot;</span>
              <span>Daftar: <span id="vuDetTanggal">-</span></span>
            </div>
          </div>
        </div>

        <div class="aum-soft-line"></div>
        <h4 class="aum-sub-heading">Informasi Usaha</h4>

        <div class="aum-info-two-col">
          <div class="aum-info-field">
            <span>Email Pemilik</span>
            <strong id="vuDetEmail">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Nomor Telepon</span>
            <strong id="vuDetPhone">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Jam Operasional</span>
            <strong id="vuDetJam">-</strong>
          </div>
          <div class="aum-info-field">
            <span>Alamat Usaha</span>
            <strong id="vuDetAlamat">-</strong>
          </div>
        </div>

        <div class="aum-desc-cream-box" style="margin-top: 12px;">
          <span class="aum-caps-label">Deskripsi Usaha</span>
          <p id="vuDetDeskripsi">-</p>
        </div>

        <div id="vuFotoWrap" style="margin-top: 12px; display: none;">
          <span class="aum-caps-label" style="display: block; margin-bottom: 6px;">Foto Tempat Usaha</span>
          <img id="vuFotoImg" src="" alt="Foto Usaha" style="width: 100%; max-height: 210px; object-fit: cover; border-radius: 12px; border: 1px solid #E5E0CE;">
        </div>

        <div id="vuCatatanWrap" class="aum-suspended-reason" style="margin-top: 12px; display: none;">
          <strong style="font-size: 12.5px; color: #991B1B;">Catatan Penolakan Sebelumnya:</strong>
          <p id="vuCatatanText" style="margin-top: 4px;"></p>
        </div>
      </div>

      <div class="aum-suspend-btn-row">
        <button type="button" id="vuOpenRejectBtn" class="aum-modal-btn-danger-soft">
          Batalkan / Tolak
        </button>

        <form id="vuApproveForm" method="POST" action="#" style="margin: 0;">
          @csrf
          @method('PUT')
          <input type="hidden" name="status_verifikasi" value="disetujui">
          <button type="submit" class="aum-btn-approve-solid">
            Disetujui
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- =========================================================================
     MODAL 2: DIALOG CATATAN PENOLAKAN (saat klik Batalkan / Tolak)
     ========================================================================= --}}
<div class="aum-modal-backdrop is-hidden" id="vuRejectModal" role="dialog" aria-modal="true" aria-labelledby="vuRejectTitle">
  <div class="aum-modal-box" style="max-width: 480px;">
    <div class="aum-modal-header">
      <button type="button" class="aum-modal-back" data-vu-close="vuRejectModal" aria-label="Tutup">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <h3 class="aum-modal-title" id="vuRejectTitle">Alasan Pembatalan / Penolakan</h3>
      <span class="aum-modal-spacer"></span>
    </div>

    <div class="dm-modal-body">
      <form id="vuRejectForm" method="POST" action="#">
        @csrf
        @method('PUT')
        <input type="hidden" name="status_verifikasi" value="ditolak">

        <div class="aum-suspend-field">
          <label for="vuRejectReasonInput">Catatan kenapa pendaftaran tidak disetujui:</label>
          <textarea
            id="vuRejectReasonInput"
            name="catatan"
            rows="4"
            placeholder="Contoh: Alamat usaha kurang lengkap atau deskripsi menu tidak sesuai standar katering..."
            required
          ></textarea>
        </div>

        <p class="aum-suspend-question">Apakah Anda yakin ingin membatalkan / menolak pendaftaran mitra ini?</p>

        <div class="aum-suspend-btn-row">
          <button type="button" class="aum-modal-btn-cancel" data-vu-close="vuRejectModal">Kembali</button>
          <button type="submit" class="aum-modal-btn-danger-soft">Kirim Penolakan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const table = document.getElementById('vuTable');
  const searchInput = document.getElementById('vuSearchInput');
  const countEl = document.getElementById('vuCountLabel');
  const filterEmptyRow = document.getElementById('vuFilterEmptyRow');
  const chips = document.querySelectorAll('[data-vu-filter]');
  let currentFilter = 'menunggu';

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
    if (filterEmptyRow) filterEmptyRow.style.display = visible === 0 ? '' : 'none';
  }

  if (searchInput) searchInput.addEventListener('input', applyFilter);
  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach((c) => c.classList.remove('is-active'));
      chip.classList.add('is-active');
      currentFilter = chip.dataset.vuFilter || 'menunggu';
      applyFilter();
    });
  });

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

  document.querySelectorAll('[data-vu-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeModal(btn.dataset.vuClose);
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

  let activeUpdateUrl = '#';
  let activeCatatan = '';

  document.querySelectorAll('[data-open-vu-tinjau]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const d = JSON.parse(btn.dataset.openVuTinjau || '{}');
      activeUpdateUrl = d.update_url || '#';
      activeCatatan = d.catatan || '';

      document.getElementById('vuDetInitials').textContent = d.initials || 'UM';
      document.getElementById('vuDetNama').textContent = d.nama_vendor || '-';
      document.getElementById('vuDetPemilik').textContent = d.pemilik || '-';
      document.getElementById('vuDetTanggal').textContent = d.tgl_daftar || '-';
      document.getElementById('vuDetEmail').textContent = d.email || '-';
      document.getElementById('vuDetPhone').textContent = d.no_hp || '-';
      document.getElementById('vuDetJam').textContent = d.jam || '-';
      document.getElementById('vuDetAlamat').textContent = d.alamat || '-';
      document.getElementById('vuDetDeskripsi').textContent = d.deskripsi || '-';

      const pill = document.getElementById('vuDetStatusPill');
      pill.textContent = d.status_label || 'Menunggu';
      pill.className = 'aum-status-pill ' + (d.status === 'disetujui' ? 'is-active' : (d.status === 'ditolak' ? 'is-suspended' : 'is-pending'));

      const fotoWrap = document.getElementById('vuFotoWrap');
      const fotoImg = document.getElementById('vuFotoImg');
      if (d.foto_toko) {
        fotoImg.src = d.foto_toko;
        fotoWrap.style.display = 'block';
      } else {
        fotoWrap.style.display = 'none';
      }

      const catatanWrap = document.getElementById('vuCatatanWrap');
      const catatanText = document.getElementById('vuCatatanText');
      if (d.catatan) {
        catatanText.textContent = d.catatan;
        catatanWrap.style.display = 'block';
      } else {
        catatanWrap.style.display = 'none';
      }

      document.getElementById('vuApproveForm').action = activeUpdateUrl;
      openModal('vuTinjauModal');
    });
  });

  document.getElementById('vuOpenRejectBtn')?.addEventListener('click', function () {
    document.getElementById('vuRejectForm').action = activeUpdateUrl;
    const reasonInput = document.getElementById('vuRejectReasonInput');
    reasonInput.value = activeCatatan;
    closeModal('vuTinjauModal');
    openModal('vuRejectModal');
    setTimeout(() => reasonInput.focus(), 60);
  });
});
</script>
@endpush
