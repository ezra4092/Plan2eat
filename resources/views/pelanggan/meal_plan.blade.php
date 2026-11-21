@extends('layouts.main', ['activeMenu' => 'langganan'])

@section('title', 'Manajemen Meal Plan - Plan2Eat')
@section('page_badge', 'Meal Plan')

@push('styles')
<style>
  .mpl-page-wrap {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 22px;
  }

  /* Hero Banner */
  .mpl-hero-banner {
    background: linear-gradient(135deg, #05371F 0%, #16442B 100%);
    border-radius: 22px;
    padding: 24px 26px;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(5, 55, 31, 0.16);
  }

  .mpl-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.14);
    color: #A7F3D0;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    margin-bottom: 8px;
  }

  .mpl-hero-title {
    font-family: var(--font-sans);
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 4px;
    color: #FFFFFF;
  }

  .mpl-hero-sub {
    font-family: var(--font-inter);
    font-size: 13.5px;
    color: #D1FAE5;
    margin: 0;
  }

  /* Common White Card */
  .mpl-card {
    background: #FFFFFF;
    border-radius: 22px;
    border: 1px solid #ECECEC;
    padding: 22px;
    box-shadow: 0 6px 22px rgba(0, 0, 0, 0.04);
  }

  .mpl-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .mpl-card-title {
    font-family: var(--font-sans);
    font-size: 16.5px;
    font-weight: 800;
    color: #16442B;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Form Grid Buat Meal Plan */
  .mpl-create-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
  }

  @media (max-width: 768px) {
    .mpl-create-grid {
      grid-template-columns: 1fr;
    }
  }

  .mpl-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .mpl-label {
    font-family: var(--font-sans);
    font-size: 12px;
    font-weight: 700;
    color: #374151;
  }

  .mpl-input,
  .mpl-select {
    width: 100%;
    padding: 10px 13px;
    border: 1.5px solid #E5E7EB;
    border-radius: 12px;
    font-family: var(--font-inter);
    font-size: 13.5px;
    font-weight: 500;
    color: #1F2937;
    background: #FAFBF9;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .mpl-input:focus,
  .mpl-select:focus {
    border-color: #16442B;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(22, 68, 43, 0.12);
  }

  .mpl-btn-primary {
    background: #16442B;
    color: #FFFFFF;
    border: none;
    border-radius: 12px;
    padding: 11px 20px;
    font-family: var(--font-sans);
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background-color 0.2s, transform 0.1s;
    white-space: nowrap;
  }

  .mpl-btn-primary:hover {
    background: #05371F;
    transform: translateY(-1px);
  }

  .mpl-btn-soft {
    background: #E8F5E9;
    color: #16442B;
    border: none;
    border-radius: 10px;
    padding: 8px 14px;
    font-family: var(--font-sans);
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
  }

  .mpl-btn-soft:hover {
    background: #C8E6C9;
  }

  .mpl-btn-danger {
    background: #FEE2E2;
    color: #DC2626;
    border: none;
    border-radius: 10px;
    padding: 8px 14px;
    font-family: var(--font-sans);
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
  }

  .mpl-btn-danger:hover {
    background: #FECACA;
  }

  /* Header Edit Plan */
  .mpl-plan-head-form {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr auto auto;
    gap: 10px;
    align-items: center;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #F3F4F6;
  }

  @media (max-width: 768px) {
    .mpl-plan-head-form {
      grid-template-columns: 1fr;
    }
  }

  /* Add Menu Box inside Plan */
  .mpl-detail-panel {
    margin-top: 16px;
    background: #F6F8F5;
    border-radius: 16px;
    padding: 18px;
    border: 1px solid #E5EAE3;
  }

  .mpl-add-detail-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr auto;
    gap: 10px;
    align-items: end;
    margin-bottom: 16px;
  }

  @media (max-width: 768px) {
    .mpl-add-detail-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Table Schedule */
  .mpl-table-wrap {
    width: 100%;
    overflow-x: auto;
    background: #FFFFFF;
    border-radius: 14px;
    border: 1px solid #E5E7EB;
  }

  .mpl-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 580px;
    font-family: var(--font-inter);
  }

  .mpl-table th {
    background: #EFECE0;
    color: #16442B;
    font-family: var(--font-sans);
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    padding: 11px 14px;
  }

  .mpl-table td {
    padding: 12px 14px;
    font-size: 13px;
    color: #374151;
    border-top: 1px solid #F3F4F6;
    vertical-align: middle;
  }

  .mpl-waktu-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    background: #E8F5E9;
    color: #16442B;
  }

  .mpl-empty-card {
    text-align: center;
    padding: 40px 20px;
    background: #FFFFFF;
    border-radius: 22px;
    border: 1.5px dashed #D1D5DB;
    color: #6B7280;
    font-size: 14px;
  }
</style>
@endpush

@section('content')
@php
    $urlLangganan = Route::has('pelanggan.langganan') ? route('pelanggan.langganan') : url('/pesanan/langganan');
    $tomorrowDate = now('Asia/Jakarta')->addDay()->toDateString();
    $requestedTanggal = (string) request('tanggal', $tomorrowDate);
    $prefillDate = $requestedTanggal >= $tomorrowDate ? $requestedTanggal : $tomorrowDate;
@endphp

<div class="mpl-page-wrap">
    {{-- Kembali ke Langganan --}}
    <a href="{{ $urlLangganan }}" class="po-back">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
        <span>Kembali ke Langganan</span>
    </a>

    {{-- Hero Banner --}}
    <section class="mpl-hero-banner">
        <div>
            <div class="mpl-hero-badge">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Rencana Makan Sehat</span>
            </div>
            <h1 class="mpl-hero-title">Manajemen Meal Plan</h1>
            <p class="mpl-hero-sub">Susun jadwal makan harianmu dengan mudah untuk porsi tepat tanpa limbah makanan.</p>
        </div>
        <a href="{{ route('pelanggan.langganan') }}" style="background: rgba(255,255,255,0.16); color: #FFFFFF; padding: 10px 18px; border-radius: 999px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-regular fa-calendar"></i>
            <span>Lihat Kalender Langganan</span>
        </a>
    </section>

    {{-- Card 1: Form Buat Meal Plan Baru --}}
    <section class="mpl-card">
        <div class="mpl-card-header">
            <h2 class="mpl-card-title">
                <i class="fa-solid fa-circle-plus" style="color: #10B981;"></i>
                <span>Buat Meal Plan Baru</span>
            </h2>
        </div>

        <form action="{{ route('pelanggan.meal_plan-create') }}" method="POST" class="mpl-create-grid">
            @csrf
            <div class="mpl-field-group">
                <label class="mpl-label" for="judul_plan_new">Judul Rencana Makan</label>
                <input type="text" id="judul_plan_new" name="judul_plan" class="mpl-input" placeholder="Contoh: Diet Sehat Minggu Ini" value="{{ old('judul_plan') }}" required>
            </div>
            <div class="mpl-field-group">
                <label class="mpl-label" for="tanggal_mulai_new">Tanggal Mulai (Minimal Besok)</label>
                <input type="date" id="tanggal_mulai_new" name="tanggal_mulai" class="mpl-input" min="{{ $tomorrowDate }}" value="{{ old('tanggal_mulai', $prefillDate) }}" required>
            </div>
            <div class="mpl-field-group">
                <label class="mpl-label" for="tanggal_selesai_new">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai_new" name="tanggal_selesai" class="mpl-input" min="{{ $tomorrowDate }}" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($prefillDate)->addDays(6)->toDateString()) }}" required>
            </div>
            <button type="submit" class="mpl-btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Plan</span>
            </button>
        </form>
    </section>

    {{-- Daftar Meal Plan Anda --}}
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h2 style="font-family: var(--font-sans); font-size: 18px; font-weight: 800; color: #1F2937; margin: 0;">
            Daftar Meal Plan Anda
        </h2>
        <span style="font-size: 12.5px; color: #6B7280;">{{ $mealPlans->count() }} Rencana Tersimpan</span>
    </div>

    @forelse ($mealPlans as $plan)
        <section class="mpl-card">
            {{-- Baris Edit & Hapus Meal Plan Utama --}}
            <form action="{{ route('pelanggan.meal_plan-update', $plan->id_plan) }}" method="POST" class="mpl-plan-head-form">
                @csrf
                @method('PUT')
                <div class="mpl-field-group">
                    <label class="mpl-label">Judul Plan</label>
                    <input type="text" name="judul_plan" class="mpl-input" value="{{ $plan->judul_plan }}" required>
                </div>
                <div class="mpl-field-group">
                    <label class="mpl-label">Mulai</label>
                    <input type="date" name="tanggal_mulai" class="mpl-input" value="{{ \Carbon\Carbon::parse($plan->tanggal_mulai)->format('Y-m-d') }}" required>
                </div>
                <div class="mpl-field-group">
                    <label class="mpl-label">Selesai</label>
                    <input type="date" name="tanggal_selesai" class="mpl-input" value="{{ \Carbon\Carbon::parse($plan->tanggal_selesai)->format('Y-m-d') }}" required>
                </div>
                <button type="submit" class="mpl-btn-soft" style="align-self: end; height: 41px;">Update Plan</button>
                <button type="button" class="mpl-btn-danger" style="align-self: end; height: 41px;"
                    data-confirm="Hapus seluruh Meal Plan ini beserta jadwalnya?"
                    data-confirm-title="Hapus Meal Plan"
                    data-confirm-ok="Ya, Hapus"
                    data-confirm-danger="true"
                    data-confirm-form="delete-plan-{{ $plan->id_plan }}">
                    Hapus
                </button>
            </form>

            <form id="delete-plan-{{ $plan->id_plan }}" action="{{ route('pelanggan.meal_plan-delete', $plan->id_plan) }}" method="POST" hidden>
                @csrf
                @method('DELETE')
            </form>

            {{-- Bagian Kelola Menu Harian --}}
            <div class="mpl-detail-panel">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                    <h3 style="font-family: var(--font-sans); font-size: 14.5px; font-weight: 800; color: #16442B; margin: 0;">
                        Jadwal &amp; Menu Makanan
                    </h3>
                    @if ($plan->detailMealPlan->isNotEmpty())
                        <form action="{{ route('pelanggan.meal_plan-checkout', $plan->id_plan) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="mpl-btn-primary" style="padding: 8px 16px; font-size: 12.5px;">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <span>Pesan Meal Plan Ini (Min. Besok)</span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Tambah Menu ke Plan ini --}}
                <form action="{{ route('pelanggan.meal_plan_detail-create', $plan->id_plan) }}" method="POST" class="mpl-add-detail-grid">
                    @csrf
                    <div class="mpl-field-group">
                        <label class="mpl-label">Pilih Menu</label>
                        <select name="id_menu" class="mpl-select" required>
                            <option value="" disabled selected>Pilih Makanan...</option>
                            @foreach ($daftarMenu as $menu)
                                <option value="{{ $menu->id_menu }}">{{ $menu->nama_makanan }} ({{ $menu->kandungan_kalori ?? '-' }} kkal)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mpl-field-group">
                        <label class="mpl-label">Tanggal Makan (Min. Besok)</label>
                        @php
                            $planMinDate = max(\Carbon\Carbon::parse($plan->tanggal_mulai)->format('Y-m-d'), $tomorrowDate);
                            $planMaxDate = \Carbon\Carbon::parse($plan->tanggal_selesai)->format('Y-m-d');
                        @endphp
                        <input type="date" name="jadwal_makan" class="mpl-input"
                            min="{{ $planMinDate }}"
                            max="{{ $planMaxDate }}"
                            value="{{ $prefillDate >= $planMinDate && $prefillDate <= $planMaxDate ? $prefillDate : $planMinDate }}"
                            required>
                    </div>

                    <div class="mpl-field-group">
                        <label class="mpl-label">Waktu Makan</label>
                        <select name="waktu_makan" class="mpl-select" required>
                            <option value="Sarapan">Sarapan (08:00)</option>
                            <option value="Makan Siang">Makan Siang (12:00)</option>
                            <option value="Makan Malam">Makan Malam (18:00)</option>
                            <option value="Cemilan">Cemilan</option>
                        </select>
                    </div>

                    <button type="submit" class="mpl-btn-primary" style="height: 41px;">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Menu</span>
                    </button>
                </form>

                {{-- Tabel Daftar Menu di Plan ini --}}
                <div class="mpl-table-wrap">
                    <table class="mpl-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Menu Makanan</th>
                                <th>Kalori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plan->detailMealPlan as $detail)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($detail->jadwal_makan)->format('d M Y') }}</strong></td>

                                    <td class="view-mode-{{ $detail->id_detail_plan }}">
                                        <span class="mpl-waktu-pill">{{ $detail->waktu_makan }}</span>
                                    </td>
                                    <td class="view-mode-{{ $detail->id_detail_plan }}">
                                        <strong>{{ $detail->menu->nama_makanan ?? 'Menu tidak ditemukan' }}</strong>
                                    </td>
                                    <td class="view-mode-{{ $detail->id_detail_plan }}">
                                        {{ $detail->menu->kandungan_kalori ?? '-' }} kkal
                                    </td>
                                    <td class="view-mode-{{ $detail->id_detail_plan }}">
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            <button type="button" onclick="toggleEdit({{ $detail->id_detail_plan }})" class="mpl-btn-soft" style="padding: 5px 10px; font-size: 12px;">
                                                Edit
                                            </button>
                                            <form action="{{ route('pelanggan.meal_plan_detail-delete', $detail->id_detail_plan) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm="Hapus menu dari jadwal ini?" data-confirm-title="Hapus Menu Jadwal" data-confirm-ok="Ya, Hapus" data-confirm-danger="true" class="mpl-btn-danger" style="padding: 5px 10px; font-size: 12px;">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    {{-- Mode Edit Inline --}}
                                    <td colspan="4" class="edit-mode-{{ $detail->id_detail_plan }}" style="display: none; background: #FFFBEB;">
                                        <form action="{{ route('pelanggan.meal_plan_detail-update', $detail->id_detail_plan) }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin: 0;">
                                            @csrf
                                            @method('PUT')

                                            <select name="waktu_makan" class="mpl-select" style="width: auto; min-width: 130px;" required>
                                                <option value="Sarapan" {{ $detail->waktu_makan == 'Sarapan' ? 'selected' : '' }}>Sarapan</option>
                                                <option value="Makan Siang" {{ $detail->waktu_makan == 'Makan Siang' ? 'selected' : '' }}>Makan Siang</option>
                                                <option value="Makan Malam" {{ $detail->waktu_makan == 'Makan Malam' ? 'selected' : '' }}>Makan Malam</option>
                                                <option value="Cemilan" {{ $detail->waktu_makan == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                                            </select>

                                            <select name="id_menu" class="mpl-select" style="flex: 1; min-width: 180px;" required>
                                                @foreach ($daftarMenu as $menu)
                                                    <option value="{{ $menu->id_menu }}" {{ $detail->id_menu == $menu->id_menu ? 'selected' : '' }}>
                                                        {{ $menu->nama_makanan }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit" class="mpl-btn-primary" style="padding: 7px 14px; font-size: 12px;">Simpan</button>
                                            <button type="button" onclick="toggleEdit({{ $detail->id_detail_plan }})" class="mpl-btn-soft" style="padding: 7px 12px; font-size: 12px; background: #E5E7EB; color: #374151;">Batal</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #6B7280; padding: 20px;">
                                        Belum ada menu di jadwal ini. Silakan tambahkan menu di atas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @empty
        <div class="mpl-empty-card">
            <p style="margin: 0;">Anda belum memiliki Meal Plan. Gunakan form di atas untuk membuat jadwal makan pertamamu!</p>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    function toggleEdit(id) {
        const viewElements = document.querySelectorAll('.view-mode-' + id);
        const editElement = document.querySelector('.edit-mode-' + id);

        if (editElement.style.display === 'none') {
            viewElements.forEach(el => el.style.display = 'none');
            editElement.style.display = 'table-cell';
        } else {
            viewElements.forEach(el => el.style.display = 'table-cell');
            editElement.style.display = 'none';
        }
    }
</script>
@endpush
