@extends('layouts.main')

@section('title', 'Mitra Catering Sehat - Plan2Eat')
@section('page_pill', 'Beranda')

@section('content')
@php
  $daftarMitra = $mitras ?? collect();
@endphp

<div class="mtr-page">
  {{-- Tombol Kembali ke Beranda --}}
  <div class="mtr-back-row">
    <a href="{{ route('pelanggan.beranda') }}" class="mtr-back-link">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali ke Beranda</span>
    </a>
  </div>

  {{-- Judul & Subjudul Halaman --}}
  <header class="mtr-page-header">
    <h1 class="mtr-page-title">Mitra Catering Sehat</h1>
    <p class="mtr-page-subtitle">Evaluasi gizi &amp; analisis hitung kalori</p>
  </header>

  {{-- Daftar Kartu Mitra UMKM --}}
  <section class="mtr-cards-grid" aria-label="Daftar Mitra Catering Sehat">
    @forelse ($daftarMitra as $mitra)
      @php
        $mitraId = $mitra->id_umkm;
        $rawFotoToko = $mitra->foto_toko;
        if ($rawFotoToko && str_starts_with($rawFotoToko, 'http')) {
          $fotoTokoUrl = $rawFotoToko;
        } elseif ($rawFotoToko) {
          $fotoTokoUrl = asset('storage/' . $rawFotoToko);
        } else {
          $fotoTokoUrl = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=300&q=80';
        }
        $hargaMulai = (int) ($mitra->harga_mulai ?? collect($mitra->menus)->min('harga_normal') ?? 0);
      @endphp

      <article class="mtr-card">
        {{-- Bagian Atas: Foto Dapur + Nama, Rating, Alamat, Harga Mulai --}}
        <div class="mtr-card-top">
          <img
            src="{{ $fotoTokoUrl }}"
            alt="{{ $mitra->nama_vendor }}"
            class="mtr-store-thumb"
            loading="lazy"
          >

          <div class="mtr-store-info">
            <h2 class="mtr-store-name">{{ $mitra->nama_vendor }}</h2>

            <div class="mtr-store-meta">
              <span class="mtr-rating-group">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="#FACC15" stroke="#FACC15" stroke-width="1.5" aria-hidden="true">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <strong>{{ number_format((float) ($mitra->rating ?? 0), 1) }}</strong><span class="mtr-review-count">({{ (int) ($mitra->total_ulasan ?? 0) }})</span>
              </span>
              <span class="mtr-meta-sep" aria-hidden="true">|</span>
              <span class="mtr-store-address">{{ $mitra->alamat_lengkap ?: '-' }}</span>
            </div>

            <p class="mtr-price-start">
              @if ($hargaMulai > 0)
                Mulai dari <strong>Rp{{ number_format($hargaMulai, 0, ',', '.') }}</strong><span>/porsi</span>
              @else
                <span>Belum ada harga menu</span>
              @endif
            </p>
          </div>
        </div>

        {{-- Bagian Tengah: Strip 4 Preview Menu --}}
        <div class="mtr-menu-strip">
          @forelse (collect($mitra->menus)->take(4) as $menu)
            @php
              $rawFotoMenu = $menu->foto_menu;
              if ($rawFotoMenu && str_starts_with($rawFotoMenu, 'http')) {
                $fotoMenuUrl = $rawFotoMenu;
              } elseif ($rawFotoMenu) {
                $fotoMenuUrl = asset('storage/' . $rawFotoMenu);
              } else {
                $fotoMenuUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=240&q=80';
              }
            @endphp
            <div class="mtr-menu-mini">
              <div class="mtr-menu-mini-thumb">
                <img src="{{ $fotoMenuUrl }}" alt="{{ $menu->nama_makanan }}" loading="lazy">
              </div>
              <span class="mtr-menu-mini-price">{{ number_format((int) $menu->harga_normal, 0, ',', '.') }}</span>
              <span class="mtr-menu-mini-name" title="{{ $menu->nama_makanan }}">{{ $menu->nama_makanan }}</span>
            </div>
          @empty
            <p style="grid-column: 1 / -1; margin: 12px 0; text-align: center; font-size: 12px; color: #555D56;">
              Belum ada menu aktif tersedia di mitra ini.
            </p>
          @endforelse
        </div>

        {{-- Bagian Bawah: Tombol Aksi Utama menuju Daftar Menu Katering --}}
        <form action="{{ route('pelanggan.menu') }}" method="GET">
          <input type="hidden" name="id_umkm" value="{{ $mitraId }}">
          <button type="submit" class="mtr-btn-detail" style="border:none; cursor:pointer;">
            <span>Lihat Menu &amp; Rincian Gizi</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </button>
        </form>
      </article>
    @empty
      <div class="mtr-empty-state">
        <p>Belum ada mitra catering sehat yang tersedia di area Anda saat ini.</p>
      </div>
    @endforelse
  </section>
</div>
@endsection