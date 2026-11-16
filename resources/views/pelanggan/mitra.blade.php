@extends('layouts.main')

@section('title', 'Mitra Katering Sehat - Plan2Eat')
@section('page_badge', 'Beranda')

@section('content')
@php
    // Data fallback agar tampilan langsung sesuai desain meskipun controller belum mengirim variabel $mitras
    $daftarMitra = $mitras ?? [
        [
            'id' => 1,
            'nama' => 'Dapur Sifren',
            'rating' => '4.7',
            'ulasan' => '500+',
            'alamat' => 'Jl. Lodaya 2 RT04',
            'harga_mulai' => '16.000',
            'foto' => asset('assets/images/sample-catering-box.jpg'),
            'menus' => [
                ['nama' => 'Ayam bungkus', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Ayam Bakar', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
            ],
        ],
        [
            'id' => 2,
            'nama' => 'Dapur Sifren',
            'rating' => '4.7',
            'ulasan' => '500+',
            'alamat' => 'Jl. Lodaya 2 RT04',
            'harga_mulai' => '16.000',
            'foto' => asset('assets/images/sample-catering-box.jpg'),
            'menus' => [
                ['nama' => 'Ayam bungkus', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Ayam Bakar', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
            ],
        ],
        [
            'id' => 3,
            'nama' => 'Dapur Sifren',
            'rating' => '4.7',
            'ulasan' => '500+',
            'alamat' => 'Jl. Lodaya 2 RT04',
            'harga_mulai' => '16.000',
            'foto' => asset('assets/images/sample-catering-box.jpg'),
            'menus' => [
                ['nama' => 'Ayam bungkus', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Ayam Bakar', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
                ['nama' => 'Bunglon Geprek', 'harga' => '15.000', 'foto' => asset('assets/images/sample-catering-box.jpg')],
            ],
        ],
    ];
@endphp

<div class="mitra-page-container">
    {{-- Tombol Kembali ke Beranda --}}
    <a href="{{ url('/beranda') }}" class="btn-back-beranda">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        <span>Kembali ke Beranda</span>
    </a>

    {{-- Heading Halaman --}}
    <div class="mitra-page-heading">
        <h1 class="mitra-page-title">Mitra Katering Sehat</h1>
        <p class="mitra-page-subtitle">Evaluasi gizi &amp; analisis hitung kalori</p>
    </div>

    {{-- Daftar Kartu Mitra UMKM --}}
    <div class="mitra-list-grid">
        @foreach($daftarMitra as $mitra)
            <article class="mitra-vendor-card">
                {{-- Bagian Atas: Foto Utama & Detail Vendor --}}
                <div class="mitra-vendor-header">
                    <img
                        src="{{ $mitra['foto'] }}"
                        alt="{{ $mitra['nama'] }}"
                        class="mitra-vendor-thumb"
                        onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=300&q=80';"
                    >

                    <div class="mitra-vendor-info">
                        <h2 class="mitra-vendor-name">{{ $mitra['nama'] }}</h2>

                        <div class="mitra-vendor-meta">
                            <span class="mitra-rating-group">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#FACC15" stroke="#EAB308" stroke-width="1.5">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <strong>{{ $mitra['rating'] }}</strong>
                                <span class="mitra-review-count">({{ $mitra['ulasan'] }})</span>
                            </span>
                            <span class="mitra-meta-divider">|</span>
                            <span class="mitra-vendor-address">{{ $mitra['alamat'] }}</span>
                        </div>

                        <div class="mitra-starting-price">
                            <span class="price-highlight">Mulai dari Rp{{ $mitra['harga_mulai'] }}</span>
                            <span class="price-unit">/porsi</span>
                        </div>
                    </div>
                </div>

                {{-- Bagian Tengah: Strip 4 Pratinjau Menu --}}
                <div class="mitra-menu-preview-box">
                    <div class="mitra-menu-grid">
                        @foreach($mitra['menus'] as $menu)
                            <div class="mitra-menu-item">
                                <img
                                    src="{{ $menu['foto'] }}"
                                    alt="{{ $menu['nama'] }}"
                                    class="mitra-menu-thumb"
                                    onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=80';"
                                >
                                <span class="mitra-menu-price">{{ $menu['harga'] }}</span>
                                <span class="mitra-menu-name" title="{{ $menu['nama'] }}">{{ $menu['nama'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bagian Bawah: CTA Button --}}
                <a href="{{ url('/mitra/' . $mitra['id']) }}" class="btn-mitra-detail">
                    <span>Lihat Menu &amp; Rincian Gizi</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </article>
        @endforeach
    </div>
</div>
@endsection