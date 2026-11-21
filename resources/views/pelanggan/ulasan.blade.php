@extends('layouts.main')

@section('title', 'Ulasan Pesanan Saya — Plan2Eat')

@section('nav')
    @include('layouts.nav')
@endsection

@section('content')
@php
    $totalPesanan = $pesananSelesai->count();
    $sudahDiulas  = $pesananSelesai->filter(fn($item) => !empty($item->ulasan))->count();
    $belumDiulas  = $totalPesanan - $sudahDiulas;
@endphp

<div class="uls-page">
    {{-- Tombol Kembali --}}
    <div class="uls-back-row">
        <a href="{{ url()->previous() }}" class="uls-back-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Hero Banner --}}
    <section class="uls-hero-card">
        <div class="uls-hero-top">
            <span class="uls-hero-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Penilaian Dapur Mitra
            </span>
            <h1 class="uls-hero-title">Pesanan Selesai & Ulasan</h1>
            <p class="uls-hero-sub">Bagikan pengalaman rasa, porsi, dan kemasan ramah lingkungan dari pesanan kateringmu untuk membantu UMKM terus berkembang.</p>
        </div>

        <div class="uls-stats-strip">
            <div class="uls-stat-box">
                <span class="uls-stat-num">{{ $totalPesanan }}</span>
                <span class="uls-stat-label">Total Selesai</span>
            </div>
            <div class="uls-stat-box">
                <span class="uls-stat-num is-amber">{{ $belumDiulas }}</span>
                <span class="uls-stat-label">Belum Diulas</span>
            </div>
            <div class="uls-stat-box">
                <span class="uls-stat-num">{{ $sudahDiulas }}</span>
                <span class="uls-stat-label">Sudah Diulas</span>
            </div>
        </div>
    </section>

    {{-- Alert Session & Error Validasi --}}
    @if(session('success'))
        <div class="uls-alert is-success" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="uls-alert is-error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="uls-alert is-error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter Tabs --}}
    @if($totalPesanan > 0)
        <div class="uls-chips" role="tablist">
            <button type="button" class="uls-chip is-active" data-filter="all">
                <span>Semua Pesanan</span>
                <span class="uls-chip-count">{{ $totalPesanan }}</span>
            </button>
            <button type="button" class="uls-chip" data-filter="unreviewed">
                <span>Belum Diulas</span>
                <span class="uls-chip-count">{{ $belumDiulas }}</span>
            </button>
            <button type="button" class="uls-chip" data-filter="reviewed">
                <span>Sudah Diulas</span>
                <span class="uls-chip-count">{{ $sudahDiulas }}</span>
            </button>
        </div>
    @endif

    {{-- Daftar Kartu Pesanan --}}
    <div class="uls-cards-grid" id="ulsCardsGrid">
        @forelse($pesananSelesai as $pesanan)
            @php
                $isReviewed = !empty($pesanan->ulasan);
            @endphp

            <article class="uls-card" data-status="{{ $isReviewed ? 'reviewed' : 'unreviewed' }}">
                {{-- Header Info Pesanan --}}
                <div class="uls-order-head">
                    <div class="uls-vendor-group">
                        <div class="uls-vendor-icon" aria-hidden="true">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="uls-order-meta">
                            <div class="uls-order-id-row">
                                <span>ID #{{ $pesanan->id_pesanan }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($pesanan->tgl_selesai)->format('d M Y, H:i') }}</span>
                            </div>
                            <h2 class="uls-vendor-name">{{ $pesanan->umkm->nama_vendor ?? 'UMKM Tidak Diketahui' }}</h2>
                            <div class="uls-order-sub">
                                <span>Total Pembayaran: <strong>Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    @if($isReviewed)
                        <span class="uls-status-pill is-reviewed">
                            <span class="dot"></span>
                            Sudah Diulas
                        </span>
                    @else
                        <span class="uls-status-pill is-pending">
                            <span class="dot"></span>
                            Menunggu Ulasan
                        </span>
                    @endif
                </div>

                {{-- KONDISI 1: BELUM DIULAS --}}
                @if(!$isReviewed)
                    <form action="{{ route('pelanggan.ulasan-create') }}" method="POST" enctype="multipart/form-data" class="uls-form">
                        @csrf
                        <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

                        {{-- Rating Bintang --}}
                        <div class="uls-field-group">
                            <label class="uls-label">
                                <span>Berikan Rating</span>
                                <span class="optional">Wajib dipilih</span>
                            </label>
                            <div class="uls-star-box" data-star-picker>
                                <div class="uls-stars-group">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="uls-star-btn" data-star-value="{{ $i }}" title="{{ $i }} Bintang">
                                            <input type="radio" name="rating" value="{{ $i }}" class="uls-star-radio" required>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                                <span class="uls-rating-caption" data-star-label>Pilih 1–5 bintang</span>
                            </div>
                        </div>

                        {{-- Teks Ulasan --}}
                        <div class="uls-field-group">
                            <label for="ulasan-{{ $pesanan->id_pesanan }}" class="uls-label">
                                <span>Ceritakan Pengalamanmu</span>
                                <span class="optional">Opsional</span>
                            </label>
                            <div class="uls-textarea-wrap">
                                <textarea
                                    id="ulasan-{{ $pesanan->id_pesanan }}"
                                    name="ulasan"
                                    class="uls-textarea"
                                    placeholder="Bagaimana rasa makanan, kesegaran bahan, dan porsinya?"
                                ></textarea>
                            </div>
                        </div>

                        {{-- Unggah Foto --}}
                        <div class="uls-field-group">
                            <label class="uls-label">
                                <span>Unggah Foto Makanan</span>
                                <span class="optional">Opsional • JPG/PNG maks. 2MB</span>
                            </label>
                            <div class="uls-upload-zone">
                                <input type="file" name="foto_ulasan" accept="image/jpeg,image/png,image/jpg" class="uls-file-input" data-photo-input>
                                <div class="uls-upload-icon">
                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </div>
                                <div class="uls-upload-text">
                                    <strong>Pilih foto dari perangkat</strong>
                                    <span>Tunjukkan tampilan menu yang kamu terima</span>
                                </div>
                            </div>
                            <div class="uls-preview-box" data-photo-preview>
                                <div class="uls-preview-left">
                                    <img src="" alt="Preview Foto" class="uls-preview-thumb">
                                    <span class="uls-preview-name"></span>
                                </div>
                                <button type="button" class="uls-btn-clear-file" data-clear-photo>Hapus</button>
                            </div>
                        </div>

                        <button type="submit" class="uls-btn-primary">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                            <span>Kirim Ulasan</span>
                        </button>
                    </form>

                {{-- KONDISI 2: SUDAH DIULAS --}}
                @else
                    <div class="uls-review-box">
                        <div class="uls-review-top">
                            <div class="uls-rendered-stars" aria-label="Rating {{ $pesanan->ulasan->rating }} dari 5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" class="uls-star-icon {{ $i <= $pesanan->ulasan->rating ? 'is-filled' : 'is-empty' }}">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                                <span class="uls-score-badge">{{ $pesanan->ulasan->rating }}.0 / 5</span>
                            </div>
                        </div>

                        @if($pesanan->ulasan->ulasan)
                            <p class="uls-review-text">{{ $pesanan->ulasan->ulasan }}</p>
                        @else
                            <p class="uls-review-text is-muted">Tidak ada teks ulasan tertulis.</p>
                        @endif

                        @if($pesanan->ulasan->foto_ulasan)
                            <div class="uls-review-photo-wrap" onclick="openLightbox('{{ asset('storage/' . $pesanan->ulasan->foto_ulasan) }}')" title="Klik untuk memperbesar foto">
                                <img src="{{ asset('storage/' . $pesanan->ulasan->foto_ulasan) }}" alt="Foto Ulasan Pesanan #{{ $pesanan->id_pesanan }}" class="uls-review-photo">
                            </div>
                        @endif

                        <div class="uls-review-actions">
                            <button type="button" class="uls-btn-edit" onclick="toggleEdit({{ $pesanan->ulasan->id_ulasan }})">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                <span>Edit Ulasan</span>
                            </button>

                            <form id="delete-form-{{ $pesanan->ulasan->id_ulasan }}" action="{{ route('pelanggan.ulasan-delete', $pesanan->ulasan->id_ulasan) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="uls-btn-delete" onclick="openDeleteModal({{ $pesanan->ulasan->id_ulasan }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Form Edit Tersembunyi --}}
                    <div class="uls-edit-drawer" id="edit-form-{{ $pesanan->ulasan->id_ulasan }}">
                        <div class="uls-edit-header">
                            <h3 class="uls-edit-title">Perbarui Ulasanmu</h3>
                        </div>

                        <form action="{{ route('pelanggan.ulasan-update', $pesanan->ulasan->id_ulasan) }}" method="POST" enctype="multipart/form-data" class="uls-form">
                            @csrf
                            @method('PUT')

                            {{-- Edit Rating --}}
                            <div class="uls-field-group">
                                <label class="uls-label">
                                    <span>Edit Rating</span>
                                    <span class="optional">Wajib</span>
                                </label>
                                <div class="uls-star-box" data-star-picker>
                                    <div class="uls-stars-group">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="uls-star-btn {{ $i <= $pesanan->ulasan->rating ? 'is-active' : '' }}" data-star-value="{{ $i }}" title="{{ $i }} Bintang">
                                                <input
                                                    type="radio"
                                                    name="rating"
                                                    value="{{ $i }}"
                                                    class="uls-star-radio"
                                                    {{ $pesanan->ulasan->rating == $i ? 'checked' : '' }}
                                                    required
                                                >
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="uls-rating-caption has-value" data-star-label>{{ $pesanan->ulasan->rating }} / 5 Bintang</span>
                                </div>
                            </div>

                            {{-- Edit Teks Ulasan --}}
                            <div class="uls-field-group">
                                <label class="uls-label">
                                    <span>Edit Ulasan</span>
                                    <span class="optional">Opsional</span>
                                </label>
                                <div class="uls-textarea-wrap">
                                    <textarea name="ulasan" class="uls-textarea" placeholder="Tulis ulasan terbarumu...">{{ $pesanan->ulasan->ulasan }}</textarea>
                                </div>
                            </div>

                            {{-- Ganti Foto --}}
                            <div class="uls-field-group">
                                <label class="uls-label">
                                    <span>Ganti Foto</span>
                                    <span class="optional">Biarkan kosong jika tidak diganti</span>
                                </label>
                                <div class="uls-upload-zone">
                                    <input type="file" name="foto_ulasan" accept="image/jpeg,image/png,image/jpg" class="uls-file-input" data-photo-input>
                                    <div class="uls-upload-icon">
                                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                    <div class="uls-upload-text">
                                        <strong>Pilih foto baru (Opsional)</strong>
                                        <span>Format JPG/PNG maksimal 2MB</span>
                                    </div>
                                </div>
                                <div class="uls-preview-box" data-photo-preview>
                                    <div class="uls-preview-left">
                                        <img src="" alt="Preview Foto Baru" class="uls-preview-thumb">
                                        <span class="uls-preview-name"></span>
                                    </div>
                                    <button type="button" class="uls-btn-clear-file" data-clear-photo>Hapus</button>
                                </div>
                            </div>

                            <div class="uls-edit-btn-row">
                                <button type="button" class="uls-btn-cancel" onclick="toggleEdit({{ $pesanan->ulasan->id_ulasan }})">Batal</button>
                                <button type="submit" class="uls-btn-save-edit">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <div class="uls-empty-card" style="grid-column: 1 / -1;">
                <div class="uls-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h3>Belum Ada Pesanan Selesai</h3>
                <p>Anda belum memiliki pesanan yang diselesaikan. Pesanan yang sudah selesai akan muncul di halaman ini untuk diulas.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty state khusus saat filter chip tidak menemukan hasil --}}
    <div class="uls-empty-card is-hidden" id="ulsFilterEmpty" style="display:none;">
        <h3>Tidak Ada Pesanan pada Kategori Ini</h3>
        <p>Coba pilih filter status lainnya di atas untuk melihat daftar pesananmu.</p>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Ulasan --}}
<div class="uls-modal-backdrop is-hidden" id="ulsDeleteModal" role="dialog" aria-modal="true">
    <div class="uls-modal-box">
        <div class="uls-modal-danger-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
        </div>
        <h4>Hapus Ulasan Ini?</h4>
        <p>Ulasan dan foto yang kamu unggah akan dihapus secara permanen. Kamu tetap bisa memberikan ulasan baru setelahnya.</p>
        <div class="uls-modal-actions">
            <button type="button" class="uls-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="uls-btn-confirm-del" id="ulsConfirmDeleteBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal Lightbox Foto --}}
<div class="uls-modal-backdrop is-hidden" id="ulsLightboxModal" onclick="closeLightbox()">
    <img src="" alt="Foto Ulasan" class="uls-lightbox-img" id="ulsLightboxImg">
</div>

<script>
    // 1. Toggle Buka-Tutup Form Edit
    function toggleEdit(id) {
        const drawer = document.getElementById('edit-form-' + id);
        if (!drawer) return;
        drawer.classList.toggle('is-open');
    }

    // 2. Interactive Star Rating Picker
    const ratingLabels = {
        1: '1/5 • Kurang Memuaskan',
        2: '2/5 • Cukup',
        3: '3/5 • Lumayan Enak',
        4: '4/5 • Enak & Puas',
        5: '5/5 • Sangat Luar Biasa!'
    };

    document.querySelectorAll('[data-star-picker]').forEach((picker) => {
        const buttons = picker.querySelectorAll('.uls-star-btn');
        const labelEl = picker.querySelector('[data-star-label]');

        const paintStars = (val) => {
            buttons.forEach((btn) => {
                const starVal = parseInt(btn.dataset.starValue, 10);
                btn.classList.toggle('is-active', starVal <= val);
            });
            if (labelEl && val > 0) {
                labelEl.textContent = ratingLabels[val] || `${val}/5 Bintang`;
                labelEl.classList.add('has-value');
            }
        };

        buttons.forEach((btn) => {
            const radio = btn.querySelector('input[type="radio"]');
            const starVal = parseInt(btn.dataset.starValue, 10);

            btn.addEventListener('click', () => {
                if (radio) radio.checked = true;
                paintStars(starVal);
            });

            btn.addEventListener('mouseenter', () => {
                buttons.forEach((b) => {
                    b.classList.toggle('is-active', parseInt(b.dataset.starValue, 10) <= starVal);
                });
            });
        });

        picker.addEventListener('mouseleave', () => {
            const checked = picker.querySelector('input[type="radio"]:checked');
            paintStars(checked ? parseInt(checked.value, 10) : 0);
        });
    });

    // 3. Live Preview Upload Foto
    document.querySelectorAll('[data-photo-input]').forEach((input) => {
        const group = input.closest('.uls-field-group');
        const previewBox = group.querySelector('[data-photo-preview]');
        const previewImg = previewBox.querySelector('.uls-preview-thumb');
        const previewName = previewBox.querySelector('.uls-preview-name');
        const clearBtn = previewBox.querySelector('[data-clear-photo]');

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) {
                previewBox.classList.remove('is-visible');
                return;
            }
            previewName.textContent = file.name;
            previewImg.src = URL.createObjectURL(file);
            previewBox.classList.add('is-visible');
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            previewImg.src = '';
            previewBox.classList.remove('is-visible');
        });
    });

    // 4. Filter Chips (Semua / Belum Diulas / Sudah Diulas)
    const filterChips = document.querySelectorAll('.uls-chip');
    const orderCards = document.querySelectorAll('#ulsCardsGrid .uls-card');
    const filterEmptyEl = document.getElementById('ulsFilterEmpty');

    filterChips.forEach((chip) => {
        chip.addEventListener('click', () => {
            filterChips.forEach((c) => c.classList.remove('is-active'));
            chip.classList.add('is-active');

            const filter = chip.dataset.filter;
            let visibleCount = 0;

            orderCards.forEach((card) => {
                const match = filter === 'all' || card.dataset.status === filter;
                card.classList.toggle('is-hidden', !match);
                if (match) visibleCount++;
            });

            if (filterEmptyEl && orderCards.length > 0) {
                filterEmptyEl.style.display = visibleCount === 0 ? 'flex' : 'none';
                filterEmptyEl.classList.toggle('is-hidden', visibleCount > 0);
            }
        });
    });

    // 5. Modal Konfirmasi Hapus
    let targetDeleteId = null;
    const deleteModal = document.getElementById('ulsDeleteModal');
    const confirmDeleteBtn = document.getElementById('ulsConfirmDeleteBtn');

    function openDeleteModal(id) {
        targetDeleteId = id;
        deleteModal.classList.remove('is-hidden');
    }

    function closeDeleteModal() {
        targetDeleteId = null;
        deleteModal.classList.add('is-hidden');
    }

    confirmDeleteBtn.addEventListener('click', () => {
        if (targetDeleteId) {
            const form = document.getElementById('delete-form-' + targetDeleteId);
            if (form) form.submit();
        }
    });

    // 6. Lightbox Foto Ulasan
    const lightboxModal = document.getElementById('ulsLightboxModal');
    const lightboxImg = document.getElementById('ulsLightboxImg');

    function openLightbox(src) {
        lightboxImg.src = src;
        lightboxModal.classList.remove('is-hidden');
    }

    function closeLightbox() {
        lightboxModal.classList.add('is-hidden');
        lightboxImg.src = '';
    }
</script>
@endsection