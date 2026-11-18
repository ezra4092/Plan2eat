
@extends('layouts.main', ['activeMenu' => 'profil', 'role' => 'umkm'])

@section('title', 'Profil UMKM - Plan2Eat')
@section('page_badge', 'Dapur Mitra')

@section('content')
<div class="umkm-profile-page">

    <!-- 1. Kartu Identitas Warung -->
    <section class="store-profile-card" aria-label="Identitas Warung">
        <div class="store-profile-main">
            <div class="store-profile-top">

                <!-- Foto Profil + Badge Centang Hijau -->
                <div class="store-avatar-wrap">
                    <img class="store-avatar-img" src="{{ asset('img/profile-umkm.jpg') }}" alt="{{ $umkm->nama_vendor }}">
                    <span class="store-verified-badge" title="Mitra Terverifikasi">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                </div>

                <!-- Detail Nama Warung, Pemilik & Rating -->
                <div class="store-info-col">
                    <div class="store-name-row"> 
                        <h1 class="store-name">{{ $umkm->nama_vendor }}</h1>
                        <span class="badge-verified-partner">Mitra Terverifikasi</span>
                    </div>

                    <p class="store-owner">{{ $user->nama_lengkap }} (Pemilik)</p>

                    <div class="store-rating-row">
                        <span class="badge-rating">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <span>4.8 / 5.0</span>
                        </span>
                        <span class="review-count">142 Ulasan Mahasiswa</span>
                    </div>
                </div>
            </div>

            <!-- Alamat Warung -->
            <div class="store-address-row">
                <svg class="address-pin-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>{{ $umkm->alamat_lengkap }}</span>
            </div>
        </div>

        <!-- Tombol Edit Profil Warung -->
        <div class="store-profile-action">
            <button type="button" class="btn-edit-store" id="btnEditProfile">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                </svg>
                <span>Edit Profil Warung</span>
            </button>
        </div>
    </section>

    <!-- Grid 2 Kolom di Desktop (1 Kolom di Mobile) -->
    <div class="umkm-profile-grid">

        <!-- 2. Kartu Manajemen Toko -->
        <section class="store-management-card" aria-label="Manajemen Toko">
            <div class="profile-section-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <h2 class="profile-section-title">Manajemen Toko</h2>
            </div>

            <div class="management-list">

                <!-- Item 1: Status Buka Toko -->
                <div class="management-item-card">
                    <div class="management-item-left">
                        <div class="profile-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div class="management-item-text">
                            <span class="management-item-title">Status Buka Toko</span>
                            <span class="management-item-sub" id="storeSwitchSub">
                                {{ strtolower($umkm->status_operasional) === 'buka' ? 'Menerima Pesanan Katering' : 'Pesanan Sementara Ditutup' }}
                            </span>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="store-switch-btn {{ strtolower($umkm->status_operasional) === 'buka' ? 'is-open' : '' }}"
                        id="btnProfileStoreSwitch"
                        aria-pressed="{{ strtolower($umkm->status_operasional) === 'buka' ? 'true' : 'false' }}"
                    >
                        <span class="switch-label" id="storeSwitchLabel">
                            {{ strtoupper($umkm->status_operasional) }}
                        </span>
                        <span class="switch-thumb"></span>
                    </button>
                </div>

                <!-- Item 2: Manajemen Menu -->
                <a href="{{ url('/umkm/manajemen/menu') }}" class="management-item-card is-link">
                    <div class="management-item-left">
                        <div class="profile-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 11h16a1 1 0 0 1 1 1v.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 12.5V12a1 1 0 0 1 1-1z"></path>
                                <path d="M5 11a7 7 0 0 1 14 0"></path>
                                <path d="M5 14v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2"></path>
                            </svg>
                        </div>
                        <div class="management-item-text">
                            <span class="management-item-title">Manajemen Menu</span>
                        </div>
                    </div>

                    <span class="item-arrow-pill" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </span>
                </a>

                <!-- Item 3: Manajemen Pesanan -->
                <a href="{{ url('/umkm/manajemen/pesanan') }}" class="management-item-card is-link">
                    <div class="management-item-left">
                        <div class="profile-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <line x1="9" y1="12" x2="15" y2="12"></line>
                                <line x1="9" y1="16" x2="15" y2="16"></line>
                            </svg>
                        </div>
                        <div class="management-item-text">
                            <span class="management-item-title">Manajemen Pesanan</span>
                        </div>
                    </div>

                    <span class="item-arrow-pill" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </span>
                </a>

            </div>
        </section>

        <!-- 3. Kartu Jadwal & Jam Operasional -->
        <section class="store-schedule-card" aria-label="Jadwal dan Jam Operasional">
            <div class="profile-section-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <h2 class="profile-section-title">Jadwal &amp; Jam Operasional</h2>
            </div>

            <div class="schedule-list">

                <!-- Jadwal 1: Jam Operasional -->
                <div class="schedule-item-card">
                    <div class="schedule-item-left">
                        <div class="profile-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div class="schedule-item-info">
                            <span class="schedule-item-label">Jam Operasional Warung</span>
                            <div class="schedule-time-row">
                                <span class="schedule-time-val">
                                    {{ substr((string) $umkm->jam_buka, 0, 5) }}
                                    -
                                    {{ substr((string) $umkm->jam_tutup, 0, 5) }}
                                </span>
                                <span class="schedule-time-tz">WIB</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-schedule-edit" id="btnEditSchedule">Ubah</button>
                </div>

                <!-- Jadwal 2: Jam Persiapan Masak H-1 -->
                <div class="schedule-item-card">
                    <div class="schedule-item-left">
                        <div class="profile-icon-box">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 13h12v3a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4v-3z"></path>
                                <path d="M18 13l3-2"></path>
                                <path d="M9 5v3"></path>
                                <path d="M12 4v4"></path>
                                <path d="M15 5v3"></path>
                            </svg>
                        </div>
                        <div class="schedule-item-info">
                            <span class="schedule-item-label">Jam Persiapan Masak</span>
                            <div class="schedule-time-row">
                                <span class="schedule-time-val">01.00 - 05.00</span>
                                <span class="schedule-time-tz">WIB</span>
                            </div>
                            <span class="schedule-item-note">Batas konfirmasi pesanan masuk untuk menu besok</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <!-- 4. Dialog Edit Profil UMKM -->
    <dialog id="editProfileDialog" class="edit-profile-dialog">
        <div class="edit-profile-dialog-content">

            <div class="profile-section-header">
                <h2 class="profile-section-title">Edit Profil UMKM</h2>
            </div>

            <p>Perbarui informasi akun dan toko kamu.</p>

            <form
                action="{{ route('umkm.profil-update', $umkm->id_umkm) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <!-- Informasi Pemilik -->
                <h3>Informasi Pemilik</h3>

                <div class="edit-profile-field">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        required
                    >
                    @error('nama_lengkap')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Informasi Toko -->
                <h3>Informasi Toko</h3>

                <div class="edit-profile-field">
                    <label for="nama_vendor">Nama Warung</label>
                    <input
                        type="text"
                        id="nama_vendor"
                        name="nama_vendor"
                        value="{{ old('nama_vendor', $umkm->nama_vendor) }}"
                        required
                    >
                    @error('nama_vendor')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="deskripsi_toko">Deskripsi Toko</label>
                    <textarea
                        id="deskripsi_toko"
                        name="deskripsi_toko"
                        rows="3"
                    >{{ old('deskripsi_toko', $umkm->deskripsi_toko) }}</textarea>
                    @error('deskripsi_toko')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <textarea
                        id="alamat_lengkap"
                        name="alamat_lengkap"
                        rows="3"
                        required
                    >{{ old('alamat_lengkap', $umkm->alamat_lengkap) }}</textarea>
                    @error('alamat_lengkap')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="jam_buka">Jam Buka Warung</label>
                    <input
                        type="time"
                        id="jam_buka"
                        name="jam_buka"
                        value="{{ old('jam_buka', substr((string) $umkm->jam_buka, 0, 5)) }}"
                        required
                    >
                    @error('jam_buka')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="jam_tutup">Jam Tutup Warung</label>
                    <input
                        type="time"
                        id="jam_tutup"
                        name="jam_tutup"
                        value="{{ old('jam_tutup', substr((string) $umkm->jam_tutup, 0, 5)) }}"
                        required
                    >
                    @error('jam_tutup')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="status_operasional">Status Operasional</label>
                    <select
                        id="status_operasional"
                        name="status_operasional"
                        required
                    >
                        <option value="buka" {{ strtolower($umkm->status_operasional) === 'buka' ? 'selected' : '' }}>
                            Buka
                        </option>
                        <option value="tutup" {{ strtolower($umkm->status_operasional) === 'tutup' ? 'selected' : '' }}>
                            Tutup
                        </option>
                    </select>
                    @error('status_operasional')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="edit-profile-field">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <input
                        type="text"
                        id="metode_pembayaran"
                        name="metode_pembayaran"
                        value="{{ old('metode_pembayaran', $umkm->metode_pembayaran) }}"
                        required
                    >
                    @error('metode_pembayaran')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Dialog -->
                <div class="edit-profile-actions">
                    <button type="button" id="btnCloseProfile">
                        Batal
                    </button>

                    <button type="submit">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </dialog>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dialog = document.getElementById('editProfileDialog');

    const btnEditProfile = document.getElementById('btnEditProfile');
    const btnEditSchedule = document.getElementById('btnEditSchedule');
    const btnStoreSwitch = document.getElementById('btnProfileStoreSwitch');
    const btnClose = document.getElementById('btnCloseProfile');

    function openDialog() {
        if (dialog && !dialog.open) {
            dialog.showModal();
        }
    }

    function closeDialog() {
        if (dialog && dialog.open) {
            dialog.close();
        }
    }

    // Edit Profil Warung
    if (btnEditProfile) {
        btnEditProfile.addEventListener('click', openDialog);
    }

    // Edit Jam Operasional
    if (btnEditSchedule) {
        btnEditSchedule.addEventListener('click', openDialog);
    }

    // Edit Status Operasional
    if (btnStoreSwitch) {
        btnStoreSwitch.addEventListener('click', openDialog);
    }

    // Tutup Dialog
    if (btnClose) {
        btnClose.addEventListener('click', closeDialog);
    }

    // Tutup Dialog ketika klik area luar
    if (dialog) {
        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                closeDialog();
            }
        });
    }

    // Buka kembali dialog apabila validasi gagal
    @if ($errors->any())
        openDialog();
    @endif

});
</script>
@endpush
