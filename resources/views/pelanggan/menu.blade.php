@extends('layouts.main')

@section('title', ($mitra->nama_warung ?? $mitra->nama_umkm ?? 'Dapur Sifren') . ' - Daftar Menu Katering | Plan2Eat')
@section('body_class', 'page-customer-menu')
@section('page_pill', 'Beranda')

@section('content')
@php
  $storeName = $mitra->nama_warung ?? $mitra->nama_umkm ?? 'Dapur Sifren';
  $isVerified = $mitra->status_verifikasi ?? $mitra->is_verified ?? true;
  $usingDummyData = empty($menus);

  // Data fallback jika database menu untuk mitra ini masih kosong
  $daftarMenu = $menus ?? [
    (object) [
      'id_menu' => 1,
      'nama_makanan' => 'Salmon Teriyaki Quinoa Bowl',
      'kandungan_kalori' => 445,
      'badge' => 'High Protein',
      'badge_type' => 'protein',
      'deskripsi_makanan' => '36g Protein • 48g Karbo',
      'harga_normal' => 17000,
      'qty' => 2,
      'foto' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=500&q=80',
    ],
    (object) [
      'id_menu' => 2,
      'nama_makanan' => 'Sate Lilit Tempe Brown Rice',
      'kandungan_kalori' => 380,
      'badge' => 'Defisit Kalori',
      'badge_type' => 'defisit',
      'deskripsi_makanan' => '24g Protein • Sambal Matah',
      'harga_normal' => 13000,
      'qty' => 1,
      'foto' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=500&q=80',
    ],
    (object) [
      'id_menu' => 3,
      'nama_makanan' => 'Beef Teriyaki Shirataki Noodle',
      'kandungan_kalori' => 330,
      'badge' => 'Low Carb',
      'badge_type' => 'lowcarb',
      'deskripsi_makanan' => '28g Protein • 12g Karbo',
      'harga_normal' => 16000,
      'qty' => 1,
      'foto' => 'https://images.unsplash.com/photo-1552611052-33e04de081de?auto=format&fit=crop&w=500&q=80',
    ],
    (object) [
      'id_menu' => 4,
      'nama_makanan' => 'Nasi Rames Gembus Fit',
      'kandungan_kalori' => 390,
      'badge' => 'Nusantara Fit',
      'badge_type' => 'nusantara',
      'deskripsi_makanan' => '30g Protein • Telur Rebus',
      'harga_normal' => 15000,
      'qty' => 2,
      'foto' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=500&q=80',
    ],
    (object) [
      'id_menu' => 5,
      'nama_makanan' => 'Ayam Panggang Madu',
      'kandungan_kalori' => 415,
      'badge' => 'High Protein',
      'badge_type' => 'protein',
      'deskripsi_makanan' => '32g Protein • 20g Karbo',
      'harga_normal' => 16000,
      'qty' => 0,
      'foto' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=500&q=80',
    ],
    (object) [
      'id_menu' => 6,
      'nama_makanan' => 'Sup Ikan Dori Bening',
      'kandungan_kalori' => 360,
      'badge' => 'Defisit Kalori',
      'badge_type' => 'defisit',
      'deskripsi_makanan' => '26g Protein • Kuah Rempah',
      'harga_normal' => 14000,
      'qty' => 0,
      'foto' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&w=500&q=80',
    ],
  ];

  $initialPorsi = $usingDummyData ? 6 : ($cartSummary->total_porsi ?? 0);
  $initialSubtotal = $usingDummyData ? 101000 : ($cartSummary->subtotal ?? 0);
@endphp

<div class="cmn-page">
  {{-- Baris Kembali ke Daftar Mitra --}}
  <div class="cmn-back-row">
    <a href="{{ route('pelanggan.mitra') }}" class="cmn-back-link">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali ke Menu Mitra</span>
    </a>
  </div>

  {{-- Header Judul & Identitas Mitra --}}
  <header class="cmn-header-row">
    <div class="cmn-header-left">
      <h1 class="cmn-page-title">Daftar Menu Katering</h1>
      <p class="cmn-page-subtitle">Porsi terukur siap makan</p>
    </div>

    <div class="cmn-header-right">
      <span class="cmn-mitra-name">{{ $storeName }}</span>
      @if($isVerified)
        <span class="cmn-mitra-verified">MITRA RESMI</span>
      @endif
    </div>
  </header>

  {{-- Grid Kartu Menu Katering --}}
  <section class="cmn-menu-grid" aria-label="Daftar Menu Katering">
    @foreach ($daftarMenu as $item)
      @php
        $menuId = $item->id_menu ?? $item->id;
        $menuName = $item->nama_makanan ?? $item->nama_menu ?? $item->nama;
        $menuPrice = (int) ($item->harga_normal ?? $item->harga ?? 0);
        $menuKalori = (int) ($item->kandungan_kalori ?? $item->kalori ?? 0);
        $menuMacro = $item->deskripsi_makanan ?? $item->info_makro ?? 'Porsi sehat terukur';
        $menuQty = $cartQuantities[$menuId] ?? ($item->qty ?? 0);

        $badgeLabel = $item->jenisMenu->nama ?? $item->badge ?? 'Menu Sehat';
        $badgeLower = strtolower($badgeLabel);
        $badgeClass = match(true) {
          ($item->badge_type ?? '') === 'defisit' || str_contains($badgeLower, 'defisit') => 'is-amber',
          ($item->badge_type ?? '') === 'lowcarb' || str_contains($badgeLower, 'carb')    => 'is-blue',
          ($item->badge_type ?? '') === 'nusantara' || str_contains($badgeLower, 'nusantara') => 'is-olive',
          default => 'is-teal',
        };

        $rawFoto = $item->foto_menu ?? $item->foto ?? null;
        if ($rawFoto && str_starts_with($rawFoto, 'http')) {
          $fotoUrl = $rawFoto;
        } elseif ($rawFoto) {
          $fotoUrl = asset('storage/' . $rawFoto);
        } else {
          $fotoUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=500&q=80';
        }
      @endphp

      <article class="cmn-card" data-menu-id="{{ $menuId }}" data-price="{{ $menuPrice }}">
        {{-- Bagian Foto Menu (Klik menuju detail menu) + Badge Kalori & Kategori --}}
        <a href="{{ route('pelanggan.menu-detail', ['id_menu' => $menuId]) }}" class="cmn-card-media" style="display:block; text-decoration:none;">
          <img
            src="{{ $fotoUrl }}"
            alt="{{ $menuName }}"
            class="cmn-card-img"
            loading="lazy"
          >

          {{-- Pill Kalori (Kiri Atas) --}}
          <span class="cmn-kcal-badge">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="#F97316" aria-hidden="true">
              <path d="M12 2C12 2 7 7.2 7 12.5C7 15.5 9.2 18 12 18C14.8 18 17 15.5 17 12.5C17 10.6 16.1 8.8 15 7.5C14.7 9.1 13.7 10 12.8 10C13.3 7.4 12 2 12 2Z"/>
            </svg>
            <span>{{ $menuKalori }} kkal</span>
          </span>

          {{-- Pill Tag Gizi (Kiri Bawah) --}}
          <span class="cmn-tag-badge {{ $badgeClass }}">
            {{ $badgeLabel }}
          </span>
        </a>

        {{-- Bagian Detail Menu & Kontrol Kuantitas --}}
        <div class="cmn-card-body">
          <div class="cmn-card-text">
            <a href="{{ route('pelanggan.menu-detail', ['id_menu' => $menuId]) }}" style="text-decoration:none; color:inherit;">
              <h2 class="cmn-card-title">{{ $menuName }}</h2>
            </a>
            <p class="cmn-card-macro">{{ $menuMacro }}</p>
          </div>

          <div class="cmn-card-footer">
            <span class="cmn-card-price">Rp{{ number_format($menuPrice, 0, ',', '.') }}</span>

            {{-- Kontrol Kuantitas (- qty + atau tombol +) --}}
            <div class="cmn-qty-control" data-qty="{{ $menuQty }}">
              @if($menuQty > 0)
                <div class="cmn-qty-pill">
                  <button type="button" class="cmn-qty-minus" aria-label="Kurangi porsi {{ $menuName }}">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                  </button>
                  <span class="cmn-qty-val">{{ $menuQty }}</span>
                  <button type="button" class="cmn-qty-plus is-in-pill" aria-label="Tambah porsi {{ $menuName }}">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round">
                      <line x1="12" y1="5" x2="12" y2="19"/>
                      <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                  </button>
                </div>
              @else
                <button type="button" class="cmn-qty-plus" aria-label="Tambah porsi {{ $menuName }}">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                </button>
              @endif
            </div>
          </div>
        </div>
      </article>
    @endforeach
  </section>
</div>

{{-- Sticky Bottom Checkout Bar --}}
<div class="cmn-checkout-bar">
  <div class="cmn-checkout-inner">
    <div class="cmn-subtotal-group">
      <span class="cmn-subtotal-label">SUBTOTAL (<span id="cmnTotalPorsi">{{ $initialPorsi }}</span> PORSI)</span>
      <strong class="cmn-subtotal-amount" id="cmnSubtotalText">Rp{{ number_format($initialSubtotal, 0, ',', '.') }}</strong>
    </div>

    <div class="cmn-checkout-actions">
      <a href="{{ route('pelanggan.keranjang') }}" class="cmn-cart-btn" style="text-decoration:none;" aria-label="Lihat Keranjang">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <span class="cmn-cart-badge" id="cmnCartBadge">{{ $initialPorsi }}</span>
      </a>

      <a href="{{ route('pelanggan.checkout') }}" class="cmn-order-btn">
        <span>Pesan Katering</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="5" y1="12" x2="19" y2="12"/>
          <polyline points="12 5 19 12 12 19"/>
        </svg>
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const cards = document.querySelectorAll('.cmn-card');
  const totalPorsiEl = document.getElementById('cmnTotalPorsi');
  const subtotalTextEl = document.getElementById('cmnSubtotalText');
  const cartBadgeEl = document.getElementById('cmnCartBadge');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const updateCartUrl = "{{ route('pelanggan.keranjang-update') }}";

  function formatRupiah(angka) {
    return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function recalculateCart() {
    let totalQty = 0;
    let totalPrice = 0;

    cards.forEach(card => {
      const price = parseInt(card.dataset.price || '0', 10);
      const control = card.querySelector('.cmn-qty-control');
      const qty = parseInt(control.dataset.qty || '0', 10);
      totalQty += qty;
      totalPrice += price * qty;
    });

    totalPorsiEl.textContent = totalQty;
    cartBadgeEl.textContent = totalQty;
    subtotalTextEl.textContent = formatRupiah(totalPrice);
  }

  function syncCartBackend(menuId, qty) {
    if (!csrfToken) return;
    fetch(updateCartUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        id_menu: menuId,
        qty: qty
      })
    }).catch(() => {});
  }

  function renderControl(control, qty) {
    control.dataset.qty = qty;
    if (qty > 0) {
      control.innerHTML = `
        <div class="cmn-qty-pill">
          <button type="button" class="cmn-qty-minus" aria-label="Kurangi porsi">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
          </button>
          <span class="cmn-qty-val">${qty}</span>
          <button type="button" class="cmn-qty-plus is-in-pill" aria-label="Tambah porsi">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
          </button>
        </div>
      `;
    } else {
      control.innerHTML = `
        <button type="button" class="cmn-qty-plus" aria-label="Tambah porsi">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
        </button>
      `;
    }
  }

  cards.forEach(card => {
    const menuId = card.dataset.menuId;
    const control = card.querySelector('.cmn-qty-control');

    control.addEventListener('click', function (e) {
      const plusBtn = e.target.closest('.cmn-qty-plus');
      const minusBtn = e.target.closest('.cmn-qty-minus');
      let currentQty = parseInt(control.dataset.qty || '0', 10);

      if (plusBtn) {
        currentQty += 1;
        renderControl(control, currentQty);
        recalculateCart();
        syncCartBackend(menuId, currentQty);
      } else if (minusBtn && currentQty > 0) {
        currentQty -= 1;
        renderControl(control, currentQty);
        recalculateCart();
        syncCartBackend(menuId, currentQty);
      }
    });
  });
});
</script>
@endpush