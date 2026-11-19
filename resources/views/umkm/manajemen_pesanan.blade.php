{{--
    Halaman Manajemen Pesanan UMKM (Dapur Mitra)
    Letak file : resources/views/umkm/manajemen_pesanan.blade.php
    Route      : umkm.pesanan (GET), umkm.pesanan-status (PUT /umkm/pesanan/{id_pesanan}/status)
                 (route yang masih di-comment jatuh ke '#', view tetap bisa dirender)

    Data dari controller ($pesanan = collection/array), tiap item punya key:
        id_pesanan, status_pesanan (persiapan|masak|diambil|selesai),
        nama_pelanggan, lokasi (opsional, cth "Kos Oren"),
        items => [ ['kuantitas' => 1, 'nama' => 'Ayam Goreng'], ... ]

    Pemetaan status database -> tampilan:
        persiapan = Step 1 "Prep"  / tab "Masuk"      -> tombol "Proses Masak"
        masak     = Step 2 "Masak" / tab "Dimasak"    -> tombol "Panggil Kurir" + "Tandai Siap Kirim"
        diambil   = Step 3 "Siap"  / tab "Siap Ambil" -> tombol "Konfirmasi Selesai"
        selesai   = tidak ditampilkan lagi

    Pratinjau tanpa controller: buka  /umkm/pesanan?step=1  |  ?step=2  |  ?step=3
--}}
@extends('main')

@section('title', 'Manajemen Pesanan - Plan2Eat')
@section('nav_active', 'pesanan')
@section('body_class', 'page-order-management')

@php
    $safe = fn ($name, $param = null) => Route::has($name)
        ? ($param !== null ? route($name, $param) : route($name))
        : '#';

    $urlBeranda = $safe('umkm.dashboard');
    $urlStatus  = $safe('umkm.pesanan-status', '__ID__');

    $stages = [
        'persiapan' => ['step' => 1, 'tab' => 'Masuk',      'badge' => 'is-red'],
        'masak'     => ['step' => 2, 'tab' => 'Dimasak',    'badge' => 'is-dark'],
        'diambil'   => ['step' => 3, 'tab' => 'Siap Ambil', 'badge' => 'is-mint'],
    ];
    $stepStatus = [1 => 'persiapan', 2 => 'masak', 3 => 'diambil'];

    // Data contoh (otomatis dipakai bila $pesanan tidak dikirim dari controller)
    if (!isset($pesanan)) {
        $demo = $stepStatus[(int) request('step', 1)] ?? 'persiapan';
        $pesanan = [
            ['id_pesanan' => 909, 'status_pesanan' => $demo, 'nama_pelanggan' => 'Sekarwangi', 'lokasi' => 'Kos Oren',
             'items' => [['kuantitas' => 1, 'nama' => 'Ayam Geprek Sambel Ijo'], ['kuantitas' => 1, 'nama' => 'Capcay']]],
            ['id_pesanan' => 910, 'status_pesanan' => $demo, 'nama_pelanggan' => 'Apriliana', 'lokasi' => 'Kos Sancang 1',
             'items' => [['kuantitas' => 1, 'nama' => 'Ikan Tongkol Goreng'], ['kuantitas' => 1, 'nama' => 'Ayam Goreng']]],
            ['id_pesanan' => 911, 'status_pesanan' => $demo, 'nama_pelanggan' => 'Rafli Fakhrullah', 'lokasi' => 'Jl. Tunjang',
             'items' => [['kuantitas' => 1, 'nama' => 'Ayam Goreng'], ['kuantitas' => 1, 'nama' => 'Capcay']]],
            ['id_pesanan' => 912, 'status_pesanan' => $demo, 'nama_pelanggan' => 'Faira Azzahra', 'lokasi' => 'Jl. Lodaya',
             'items' => [['kuantitas' => 1, 'nama' => 'Ayam Goreng'], ['kuantitas' => 1, 'nama' => 'Tumis Kangkung']]],
        ];
    }

    $orders = collect($pesanan)->map(fn ($p) => (object) $p)
        ->filter(fn ($p) => isset($stages[$p->status_pesanan]))->values();

    $count = [];
    foreach ($stages as $key => $s) {
        $count[$key] = $orders->where('status_pesanan', $key)->count();
    }

    $activeKey = request('tab');
    if (!isset($stages[$activeKey])) {
        $activeKey = collect(array_keys($stages))->first(fn ($k) => $count[$k] > 0) ?? 'persiapan';
    }
    $step = $stages[$activeKey]['step'];

    $stepDefs = [
        1 => ['label' => 'Prep',  'icon' => '<rect x="2.5" y="4" width="15" height="14" rx="3"/><path d="M2.5 9h15M7 2v4M13 2v4"/><circle cx="17" cy="17" r="4.5"/><path d="M17 14.7V17l1.5 1"/>'],
        2 => ['label' => 'Masak', 'icon' => '<path d="M12 22c4 0 7-2.7 7-6.5 0-2.6-1.5-4.4-3-6-.3 1.3-1 2.2-2 2.7.3-2.7-.8-5.3-3-7.2-.2 3-2.2 4.5-3.6 6.3C6.2 12.9 5 14.2 5 15.8 5 19.5 8 22 12 22z"/>'],
        3 => ['label' => 'Siap',  'icon' => '<path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/>'],
    ];
@endphp

@section('content')
<div class="po-page"
     data-url-status="{{ $urlStatus }}"
     data-active="{{ $activeKey }}">

    {{-- Kembali --}}
    <a href="{{ $urlBeranda }}" class="po-back">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
        <span>Kembali ke Beranda</span>
    </a>

    {{-- ===== Alur Masak ===== --}}
    <section class="po-flow-card" aria-label="Alur masak">
        <div class="po-flow-inner">
            <div class="po-flow-head">
                <h2 class="po-flow-title">Alur Masak</h2>
                <span class="po-flow-step" id="poStepText">Step {{ $step }} dari 3</span>
            </div>

            <div class="po-track" style="--po-progress: {{ ($step - 1) * 50 }}">
                <span class="po-line"><span class="po-line-fill"></span></span>
                @foreach ($stepDefs as $i => $def)
                    @php $state = $i < $step ? 'is-done' : ($i === $step ? 'is-current' : 'is-upcoming'); @endphp
                    <div class="po-step {{ $state }}" data-step="{{ $i }}">
                        <span class="po-step-circle">
                            <svg class="po-ic-check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <svg class="po-ic-main" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $def['icon'] !!}</svg>
                        </span>
                        <span class="po-step-label">{{ $def['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Manajemen Pesanan ===== --}}
    <section class="po-orders-card" aria-label="Daftar pesanan">
        <div class="po-orders-head">
            <h2 class="po-orders-title">Manajemen Pesanan</h2>
            <span class="po-live">Live Update</span>
        </div>

        <div class="po-tabs" role="tablist">
            @foreach ($stages as $key => $s)
                <button type="button" role="tab"
                        class="po-tab {{ $key === $activeKey ? 'is-active' : '' }}"
                        data-tab="{{ $key }}">
                    <span>{{ $s['tab'] }}</span>
                    <span class="po-badge {{ $s['badge'] }}" data-count="{{ $key }}">{{ $count[$key] }}</span>
                </button>
            @endforeach
        </div>

        <div class="po-order-list">
            @foreach ($orders as $o)
                <article class="po-order {{ $o->status_pesanan === $activeKey ? '' : 'is-hidden' }}"
                         data-id="{{ $o->id_pesanan }}" data-status="{{ $o->status_pesanan }}">
                    <div class="po-order-head">
                        <span class="po-order-code">#P2E-{{ str_pad($o->id_pesanan, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="po-order-divider"></span>
                        <span class="po-order-name">{{ $o->nama_pelanggan }}@if (!empty($o->lokasi)) ({{ $o->lokasi }})@endif</span>
                    </div>
                    <ul class="po-order-items">
                        @foreach (($o->items ?? []) as $it)
                            <li>{{ data_get($it, 'kuantitas', 1) }}x {{ data_get($it, 'nama') }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>

        <div class="po-empty {{ $count[$activeKey] > 0 ? 'is-hidden' : '' }}" id="poEmpty">
            Belum ada pesanan di tahap ini.
        </div>

        {{-- Tombol aksi (satu set per tahap, yang tampil mengikuti step aktif) --}}
        <div id="poActions" class="{{ $count[$activeKey] > 0 ? '' : 'is-hidden' }}">
            <div class="po-actions {{ $step === 1 ? '' : 'is-hidden' }}" data-actions="1">
                <button type="button" class="po-btn is-dark is-block" data-advance="1">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20"/><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8"/><path d="m4 8 16-4"/><path d="m8.86 6.78-.45-1.81a2 2 0 0 1 1.45-2.43l1.94-.48a2 2 0 0 1 2.43 1.46l.45 1.8"/></svg>
                    Proses Masak
                </button>
            </div>

            <div class="po-actions {{ $step === 2 ? '' : 'is-hidden' }}" data-actions="2">
                <button type="button" class="po-btn is-amber" id="btnKurir">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="15" cy="5" r="1"/><path d="M12 17.5V14l-3-3 4-3 2 3h2"/></svg>
                    Panggil Kurir
                </button>
                <button type="button" class="po-btn is-dark" data-advance="2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
                    Tandai Siap Kirim
                </button>
            </div>

            <div class="po-actions {{ $step === 3 ? '' : 'is-hidden' }}" data-actions="3">
                <button type="button" class="po-btn is-dark is-block" data-advance="3">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/></svg>
                    Konfirmasi Selesai
                </button>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const root = document.querySelector('.po-page');
    const urlTpl = root.dataset.urlStatus;
    const csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content;
    const stages = { persiapan: { step: 1, next: 'masak' }, masak: { step: 2, next: 'diambil' }, diambil: { step: 3, next: 'selesai' } };
    const keys = Object.keys(stages);
    let active = root.dataset.active;

    const $  = (s) => root.querySelector(s);
    const $$ = (s) => Array.from(root.querySelectorAll(s));

    function toast(msg, isError) {
        const el = document.createElement('div');
        el.className = 'po-toast' + (isError ? ' is-error' : '');
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2800);
    }

    function render() {
        const step = stages[active].step;
        $('.po-track').style.setProperty('--po-progress', (step - 1) * 50);
        $('#poStepText').textContent = 'Step ' + step + ' dari 3';

        $$('.po-step').forEach((el) => {
            const i = Number(el.dataset.step);
            el.classList.toggle('is-done', i < step);
            el.classList.toggle('is-current', i === step);
            el.classList.toggle('is-upcoming', i > step);
        });

        let shown = 0;
        $$('.po-order').forEach((el) => {
            const show = el.dataset.status === active;
            el.classList.toggle('is-hidden', !show);
            if (show) shown++;
        });

        keys.forEach((k) => {
            const n = $$('.po-order').filter((el) => el.dataset.status === k).length;
            $('[data-count="' + k + '"]').textContent = n;
        });

        $$('.po-tab').forEach((t) => t.classList.toggle('is-active', t.dataset.tab === active));
        $('#poEmpty').classList.toggle('is-hidden', shown > 0);
        $('#poActions').classList.toggle('is-hidden', shown === 0);
        $$('[data-actions]').forEach((a) => a.classList.toggle('is-hidden', Number(a.dataset.actions) !== step));
    }

    async function advance(btn) {
        const cards = $$('.po-order').filter((el) => el.dataset.status === active);
        if (!cards.length) return;
        const to = stages[active].next;
        btn.disabled = true;

        try {
            // Bila route umkm.pesanan-status sudah aktif, kirim PUT per pesanan.
            if (urlTpl !== '#') {
                const res = await Promise.all(cards.map((c) => fetch(urlTpl.replace('__ID__', c.dataset.id), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ _method: 'PUT', status_pesanan: to })
                })));
                if (res.some((r) => !r.ok)) throw new Error('gagal');
            }

            cards.forEach((c) => { if (to === 'selesai') c.remove(); else c.dataset.status = to; });
            if (to !== 'selesai') active = to;
            toast(to === 'selesai' ? 'Pesanan dikonfirmasi selesai.' : 'Status pesanan diperbarui.');
            render();
        } catch (e) {
            toast('Gagal memperbarui status. Memuat ulang...', true);
            setTimeout(() => location.reload(), 1500);
        } finally {
            btn.disabled = false;
        }
    }

    $$('.po-tab').forEach((t) => t.addEventListener('click', () => { active = t.dataset.tab; render(); }));
    $$('[data-advance]').forEach((b) => b.addEventListener('click', () => advance(b)));
    $('#btnKurir').addEventListener('click', () => toast('Permintaan kurir dikirim (fitur kurir belum terhubung).'));
})();
</script>
@endpush
