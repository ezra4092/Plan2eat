@extends('layouts.main', ['activeMenu' => 'beranda'])

@section('title', 'Keranjang Pesanan - Plan2Eat')
@section('page_badge', 'Keranjang')

@push('styles')
<style>
  .cart-page-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 10px 16px 40px;
    font-family: var(--font-inter, sans-serif);
  }

  /* Step Wizard */
  .cart-steps-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
  }
  .cart-step-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #9CA3AF;
  }
  .cart-step-item.is-active {
    color: #16442B;
    font-weight: 700;
  }
  .cart-step-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #E5E7EB;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }
  .cart-step-item.is-active .cart-step-circle {
    background: #16442B;
    color: #FFFFFF;
  }
  .cart-step-sep {
    width: 32px;
    height: 2px;
    background: #E5E7EB;
  }

  /* Flash Alerts */
  .cart-alert {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 13.5px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .cart-alert-success {
    background: #ECFDF5;
    color: #065F46;
    border: 1px solid #A7F3D0;
  }
  .cart-alert-danger {
    background: #FEF2F2;
    color: #991B1B;
    border: 1px solid #FECACA;
  }

  /* Common Card */
  .cart-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    padding: 20px;
    margin-bottom: 18px;
  }

  /* Header UMKM Card */
  .cart-umkm-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
  }
  .cart-umkm-info h2 {
    font-size: 17px;
    font-weight: 800;
    color: #1F2937;
    margin: 2px 0 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .cart-umkm-info p {
    font-size: 12px;
    color: #6B7280;
    margin: 0;
  }
  .cart-umkm-badge {
    background: #E8F5E9;
    color: #16442B;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
  }

  /* Item Cards */
  .cart-item-card {
    background: #FAFAFA;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 14px;
    transition: all 0.2s ease;
  }
  .cart-item-card:hover {
    border-color: #10B981;
    background: #FFFFFF;
    box-shadow: 0 4px 12px rgba(22, 68, 43, 0.05);
  }

  .cart-item-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
  }
  .cart-item-title-group {
    flex: 1;
  }
  .cart-badge-paket {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #16442B;
    color: #A7F3D0;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    margin-bottom: 6px;
  }
  .cart-badge-satuan {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #0D9488;
    color: #CCFBF1;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    margin-bottom: 6px;
  }
  .cart-badge-fs {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #F59E0B;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    margin-bottom: 6px;
  }

  .cart-item-name {
    font-size: 15px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px;
  }
  .cart-item-price {
    font-size: 14px;
    font-weight: 800;
    color: #16442B;
  }
  .cart-btn-del {
    background: #FEE2E2;
    border: none;
    color: #DC2626;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
  }
  .cart-btn-del:hover {
    background: #FCA5A5;
  }

  /* Condition Notices */
  .cart-condition-notice {
    background: #F0FDF4;
    border-left: 3px solid #10B981;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: #166534;
    margin-bottom: 14px;
  }
  .cart-condition-notice.is-satuan {
    background: #F0FDFA;
    border-left-color: #0D9488;
    color: #115E59;
  }

  /* Controls Grid */
  .cart-item-controls {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    align-items: flex-end;
  }
  @media (max-width: 640px) {
    .cart-item-controls {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  .cart-field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .cart-field-label {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
  }

  /* Quantity Stepper (Menu Satuan) */
  .cart-qty-stepper {
    display: inline-flex;
    align-items: center;
    border: 1px solid #D1D5DB;
    border-radius: 10px;
    overflow: hidden;
    background: #FFFFFF;
    width: fit-content;
  }
  .cart-stepper-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #F3F4F6;
    color: #374151;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .cart-stepper-btn:hover {
    background: #E5E7EB;
  }
  .cart-stepper-input {
    width: 50px;
    height: 36px;
    border: none;
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    outline: none;
  }

  /* Time Picker Control */
  .cart-time-input-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .cart-time-input {
    padding: 8px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    color: #16442B;
    background: #FFFFFF;
    outline: none;
    min-width: 110px;
  }
  .cart-time-input:focus {
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
  }
  .cart-time-presets {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
  }
  .cart-preset-btn {
    background: #E5E7EB;
    border: none;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.15s;
  }
  .cart-preset-btn:hover {
    background: #16442B;
    color: #FFFFFF;
  }

  /* Date Range Row */
  .cart-date-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    padding-top: 14px;
    border-top: 1px solid #E5E7EB;
  }
  @media (max-width: 640px) {
    .cart-date-row {
      grid-template-columns: 1fr;
    }
  }
  .cart-date-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    background: #FFFFFF;
    outline: none;
  }

  /* Companion Menus */
  .cart-companion-wrap {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
  }
  .cart-companion-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 999px;
    border: 1px dashed #10B981;
    background: #F0FDF4;
    color: #065F46;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
  }
  .cart-companion-link:hover {
    background: #16442B;
    color: #FFFFFF;
    border-style: solid;
  }

  /* Bottom Actions Bar */
  .cart-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 24px;
  }
  .cart-btn-back {
    padding: 11px 22px;
    border-radius: 999px;
    border: 1px solid #D1D5DB;
    background: #FFFFFF;
    color: #4B5563;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
  }
  .cart-btn-back:hover {
    background: #F3F4F6;
    color: #111827;
  }
  .cart-btn-checkout {
    padding: 12px 28px;
    border-radius: 999px;
    border: none;
    background: #16442B;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(22, 68, 43, 0.25);
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .cart-btn-checkout:hover {
    background: #0f301e;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(22, 68, 43, 0.35);
  }

  /* Empty State */
  .cart-empty-box {
    text-align: center;
    padding: 60px 20px;
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #ECECEC;
  }
  .cart-empty-icon {
    font-size: 48px;
    color: #9CA3AF;
    margin-bottom: 14px;
  }
  .cart-empty-box h3 {
    font-size: 18px;
    font-weight: 800;
    color: #1F2937;
    margin-bottom: 6px;
  }
  .cart-empty-box p {
    font-size: 13px;
    color: #6B7280;
    margin-bottom: 20px;
  }
</style>
@endpush

@section('content')
<div class="cart-page-wrap">

  <!-- Step Wizard Indicator -->
  <div class="cart-steps-bar">
    <div class="cart-step-item is-active">
      <div class="cart-step-circle">1</div>
      <span>Atur Keranjang</span>
    </div>
    <div class="cart-step-sep"></div>
    <div class="cart-step-item">
      <div class="cart-step-circle">2</div>
      <span>Pembayaran</span>
    </div>
    <div class="cart-step-sep"></div>
    <div class="cart-step-item">
      <div class="cart-step-circle">3</div>
      <span>Selesai</span>
    </div>
  </div>

  @if(session('success'))
    <div class="cart-alert cart-alert-success">
      <i class="fa-solid fa-circle-check"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="cart-alert cart-alert-danger">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  @if(empty($cart['items']))
    <div class="cart-empty-box">
      <div class="cart-empty-icon">
        <i class="fa-solid fa-basket-shopping"></i>
      </div>
      <h3>Keranjang Anda Masih Kosong</h3>
      <p>Silakan pilih paket menu katering atau menu satuan sehat dari beranda.</p>
      <a href="{{ route('pelanggan.beranda') }}" class="cart-btn-checkout">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Pilih Menu di Beranda</span>
      </a>
    </div>
  @else
    <form action="{{ route('pelanggan.keranjang-update') }}" method="POST">
      @csrf

      <!-- Info Mitra / Toko Catering -->
      <div class="cart-card">
        <div class="cart-umkm-box">
          <div class="cart-umkm-info">
            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #10B981; letter-spacing: 0.5px;">Dapur Mitra Terpilih</span>
            <h2>{{ $umkm->nama_vendor ?? 'Mitra Plan2Eat' }}</h2>
            <p><i class="fa-solid fa-location-dot" style="color: #10B981;"></i> {{ $umkm->alamat_lengkap ?? 'Bogor' }}</p>
          </div>
          <span class="cart-umkm-badge">
            <i class="fa-solid fa-bag-shopping"></i> {{ count($cart['items']) }} Menu dalam Pesanan
          </span>
        </div>
      </div>

      <!-- Daftar Item Pesanan -->
      <div class="cart-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
          <h3 style="font-size: 15px; font-weight: 800; color: #1F2937; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;">
            Atur Jam Makan &amp; Porsi Menu
          </h3>
          <span style="font-size: 12px; color: #6B7280;">Sesuaikan waktu pengantaran untuk setiap menu</span>
        </div>

        @php $hasPaket = false; @endphp

        @foreach($cart['items'] as $index => $item)
          @if($item['is_paket']) @php $hasPaket = true; @endphp @endif

          <div class="cart-item-card">
            <div class="cart-item-top">
              <div class="cart-item-title-group">
                <!-- Badges -->
                @if($item['is_paket'])
                  <span class="cart-badge-paket">
                    <i class="fa-solid fa-boxes-stacked"></i> Menu {{ $index + 1 }}: {{ $item['jenis_nama'] ?? 'Paket Catering' }}
                  </span>
                @else
                  <span class="cart-badge-satuan">
                    <i class="fa-solid fa-bowl-food"></i> Menu Satuan (A La Carte)
                  </span>
                @endif

                @if(!empty($item['id_flash_sale']))
                  <span class="cart-badge-fs">
                    <i class="fa-solid fa-bolt"></i> Flash Sale
                  </span>
                @endif

                <h4 class="cart-item-name">{{ $item['nama_makanan'] }}</h4>
                <div class="cart-item-price">
                  Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                  <small style="font-size: 11px; color: #6B7280; font-weight: normal;">{{ $item['is_paket'] ? '/paket' : '/porsi' }}</small>
                </div>
              </div>

              <button type="button" class="cart-btn-del"
                onclick="if(confirm('Hapus menu ini dari keranjang?')) document.getElementById('delete-item-{{ $index }}').submit();">
                <i class="fa-solid fa-trash-can"></i> Hapus
              </button>
            </div>

            <!-- Petunjuk Sesuai Kondisi -->
            @if($item['is_paket'])
              <div class="cart-condition-notice">
                <i class="fa-solid fa-circle-info"></i>
                <strong>Menu Paket:</strong> Anda wajib mengatur jam makan/pengantaran untuk menu ini (misal Menu 1 jam 08:00, Menu 2 jam 17:00). Kuantitas otomatis dihitung 1 paket.
              </div>
            @else
              <div class="cart-condition-notice is-satuan">
                <i class="fa-solid fa-circle-info"></i>
                <strong>Menu Satuan:</strong> Silakan atur berapa porsi yang ingin Anda beli dan tentukan jam makan pengantarannya.
              </div>
            @endif

            <!-- Kontrol Porsi & Jam Makan -->
            <div class="cart-item-controls">
              <!-- KONDISI 1: Menu Paketan (Kuantitas Tetap 1) -->
              @if($item['is_paket'])
                <div class="cart-field-group">
                  <label class="cart-field-label">Jumlah Paket</label>
                  <div style="background: #E5E7EB; color: #374151; padding: 8px 14px; border-radius: 10px; font-weight: 700; font-size: 13px; width: fit-content;">
                    <i class="fa-solid fa-lock" style="font-size: 11px;"></i> 1 Paket Catering (Otomatis)
                  </div>
                  <input type="hidden" name="items[{{ $index }}][kuantitas]" value="1">
                </div>
              <!-- KONDISI 2: Menu Satuan (Bisa Atur Berapa Porsi) -->
              @else
                <div class="cart-field-group">
                  <label class="cart-field-label" for="qty_{{ $index }}">Jumlah Porsi (Maks. {{ $item['stok_maks'] }})</label>
                  <div class="cart-qty-stepper">
                    <button type="button" class="cart-stepper-btn" onclick="stepQty('qty_{{ $index }}', -1)">-</button>
                    <input type="number"
                      id="qty_{{ $index }}"
                      name="items[{{ $index }}][kuantitas]"
                      class="cart-stepper-input"
                      value="{{ $item['kuantitas'] }}"
                      min="1"
                      max="{{ $item['stok_maks'] }}"
                      required>
                    <button type="button" class="cart-stepper-btn" onclick="stepQty('qty_{{ $index }}', 1)">+</button>
                  </div>
                </div>
              @endif

              <!-- KEDUA KONDISI: Wajib Atur Jam Makan Setiap Menu -->
              <div class="cart-field-group">
                <label class="cart-field-label" for="waktu_{{ $index }}">
                  <i class="fa-regular fa-clock" style="color: #10B981;"></i> Jam Makan / Pengantaran (Menu {{ $index + 1 }})
                </label>
                <div class="cart-time-input-wrap">
                  <input type="time"
                    id="waktu_{{ $index }}"
                    name="items[{{ $index }}][waktu]"
                    class="cart-time-input"
                    value="{{ $item['waktu'] ?? ($index === 0 ? '08:00' : '17:00') }}"
                    required>

                  <div class="cart-time-presets">
                    <button type="button" class="cart-preset-btn" onclick="setTime('waktu_{{ $index }}', '08:00')">08:00 (Pagi)</button>
                    <button type="button" class="cart-preset-btn" onclick="setTime('waktu_{{ $index }}', '12:00')">12:00 (Siang)</button>
                    <button type="button" class="cart-preset-btn" onclick="setTime('waktu_{{ $index }}', '17:00')">17:00 (Sore)</button>
                    <button type="button" class="cart-preset-btn" onclick="setTime('waktu_{{ $index }}', '19:00')">19:00 (Malam)</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Pengaturan Jadwal Tanggal -->
        <div class="cart-date-row">
          <div>
            <label for="tgl_mulai" class="cart-field-label">Tanggal Mulai Pengantaran</label>
            <input type="date" id="tgl_mulai" name="tgl_mulai" class="cart-date-input"
              value="{{ $cart['tgl_mulai'] ?? now()->toDateString() }}"
              min="{{ now()->toDateString() }}" required>
          </div>

          @if($hasPaket)
            <div>
              <label for="tgl_selesai" class="cart-field-label">Tanggal Selesai Paket (Opsional)</label>
              <input type="date" id="tgl_selesai" name="tgl_selesai" class="cart-date-input"
                value="{{ $cart['tgl_selesai'] ?? now()->addDays(2)->toDateString() }}"
                min="{{ now()->toDateString() }}">
            </div>
          @endif
        </div>
      </div>

      <!-- Tambah Menu Pendamping Lain dari Mitra yang Sama -->
      @if($menuLainUmkm->count() > 1)
        <div class="cart-card">
          <h4 style="font-size: 13.5px; font-weight: 800; color: #1F2937; margin: 0 0 4px;">
            <i class="fa-solid fa-plus-circle" style="color: #10B981;"></i> Tambah Menu Lain ke Paket Anda dari {{ $umkm->nama_vendor }}
          </h4>
          <p style="font-size: 12px; color: #6B7280; margin: 0 0 10px;">
            Ingin menambah variasi makanan di jam makan lainnya? Tambahkan menu lain dari dapur yang sama:
          </p>
          <div class="cart-companion-wrap">
            @foreach($menuLainUmkm as $mLain)
              @php
                $alreadyInCart = collect($cart['items'])->contains('id_menu', $mLain->id_menu);
              @endphp
              @if(!$alreadyInCart)
                <a href="{{ route('pelanggan.keranjang', ['menu' => $mLain->id_menu]) }}" class="cart-companion-link">
                  <i class="fa-solid fa-plus"></i>
                  <span>{{ $mLain->nama_makanan }}</span>
                  <span style="opacity: 0.8;">(Rp {{ number_format($mLain->harga_normal, 0, ',', '.') }})</span>
                </a>
              @endif
            @endforeach
          </div>
        </div>
      @endif

      <!-- Actions Bar -->
      <div class="cart-actions-bar">
        <a href="{{ route('pelanggan.beranda') }}" class="cart-btn-back">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>
        <button type="submit" class="cart-btn-checkout">
          <span>Lanjut ke Pembayaran</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </form>

    <!-- Hidden Form Delete Items -->
    @foreach($cart['items'] as $index => $item)
      <form id="delete-item-{{ $index }}" action="{{ route('pelanggan.keranjang-hapus', $index) }}" method="POST" hidden>
        @csrf
        @method('DELETE')
      </form>
    @endforeach
  @endif

</div>
@endsection

@push('scripts')
<script>
  function stepQty(inputId, delta) {
    const el = document.getElementById(inputId);
    if (!el) return;
    const min = parseInt(el.min || 1, 10);
    const max = parseInt(el.max || 999, 10);
    let val = parseInt(el.value || 1, 10) + delta;
    if (val < min) val = min;
    if (val > max) val = max;
    el.value = val;
  }

  function setTime(inputId, timeVal) {
    const el = document.getElementById(inputId);
    if (el) el.value = timeVal;
  }
</script>
@endpush


@push('scripts')
<script>
  function stepQty(inputId, delta) {
    const el = document.getElementById(inputId);
    if (!el) return;
    const min = parseInt(el.min || 1, 10);
    const max = parseInt(el.max || 999, 10);
    let val = parseInt(el.value || 1, 10) + delta;
    if (val < min) val = min;
    if (val > max) val = max;
    el.value = val;
  }

  function setTime(inputId, timeVal) {
    const el = document.getElementById(inputId);
    if (el) el.value = timeVal;
  }
</script>
@endpush