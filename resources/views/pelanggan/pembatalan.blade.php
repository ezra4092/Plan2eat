{{-- Pop-up Dialog Pembatalan Pesanan (Background Blur) --}}
<div class="btl-modal-backdrop is-hidden" id="btlCancelModal" role="dialog" aria-modal="true" aria-labelledby="btlModalTitle">
    <div class="btl-modal-box">
        <div class="btl-modal-head">
            <div class="btl-modal-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <h3 class="btl-modal-title" id="btlModalTitle">Batalkan Pesanan Ini?</h3>
                <p id="btlOrderMeta" style="margin: 4px 0 0; font-size: 12px; color: #6B7280;"></p>
            </div>
        </div>

        <form action="#" method="POST" id="btlCancelForm" class="btl-modal-form">
            @csrf

            <div class="btl-field-group">
                <label for="btlReasonInput" class="btl-label">
                    <span>Alasan Pembatalan</span>
                    <span class="btl-required">Wajib diisi</span>
                </label>

                <div class="btl-textarea-wrap">
                    <textarea
                        id="btlReasonInput"
                        name="catatan"
                        class="btl-textarea"
                        placeholder="Tuliskan alasan pembatalan pesananmu di sini..."
                        required
                    ></textarea>
                </div>
            </div>

            <div class="btl-confirm-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p>
                    <strong>Apakah kamu yakin?</strong> Dana pembayaran akan otomatis dikembalikan (refund) ke <strong>Saldo Akun Plan2Eat</strong> Anda.
                    <span id="btlDeadlineHint" style="display: block; margin-top: 3px; color: #16442B; font-weight: 600;"></span>
                </p>
            </div>

            <div class="btl-modal-actions">
                <button type="button" class="btl-btn-no" onclick="closeCancelModal()">Tidak, Kembali</button>
                <button type="submit" class="btl-btn-yes">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>

{{-- Dialog Refund Uang Setelah Pembatalan Berhasil --}}
@php
    $refundDialog = session('refund_dialog');
@endphp
@if (!empty($refundDialog))
<div class="btl-modal-backdrop" id="btlRefundModal" role="dialog" aria-modal="true" aria-labelledby="btlRefundTitle">
    <div class="btl-modal-box">
        <div class="btl-modal-head">
            <div class="btl-modal-icon" style="background: #DCFCE7; color: #16442B;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div>
                <h3 class="btl-modal-title" id="btlRefundTitle">Pembatalan &amp; Refund Berhasil</h3>
                <p style="margin: 4px 0 0; font-size: 12.5px; color: #4B5563;">
                    Pesanan #P2E-{{ $refundDialog['id_pesanan'] }} ({{ $refundDialog['mitra_name'] }}) telah dibatalkan.
                </p>
            </div>
        </div>

        <div style="background: #F4F7F4; border: 1px solid #D1E7DA; border-radius: 16px; padding: 16px; display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <span style="font-size: 12.5px; color: #4B5563;">Nominal Refund Masuk Saldo</span>
                <strong style="font-size: 18px; color: #16442B; font-family: var(--font-sans);">
                    +Rp {{ number_format((float) ($refundDialog['refund_amount'] ?? 0), 0, ',', '.') }}
                </strong>
            </div>
            <div style="border-top: 1px dashed #CBD5E1; padding-top: 10px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <span style="font-size: 12.5px; color: #4B5563;">Total Saldo Akun Anda Saat Ini</span>
                <strong style="font-size: 15px; color: #111827; font-family: var(--font-sans);">
                    Rp {{ number_format((float) ($refundDialog['new_saldo'] ?? 0), 0, ',', '.') }}
                </strong>
            </div>
            <div style="border-top: 1px dashed #CBD5E1; padding-top: 10px; font-size: 12px; color: #4B5563;">
                <strong>Catatan Pembatalan:</strong> {{ $refundDialog['catatan'] ?? '-' }}
            </div>
        </div>

        <div class="btl-modal-actions">
            <button type="button" class="btl-btn-yes" style="background: #16442B; width: 100%;" onclick="closeRefundModal()">
                Mengerti &amp; Tutup
            </button>
        </div>
    </div>
</div>
@endif

<script>
    const btlModal = document.getElementById('btlCancelModal');
    const btlForm = document.getElementById('btlCancelForm');
    const btlReasonInput = document.getElementById('btlReasonInput');
    const btlOrderMeta = document.getElementById('btlOrderMeta');
    const btlDeadlineHint = document.getElementById('btlDeadlineHint');
    const btlRefundModal = document.getElementById('btlRefundModal');

    function openCancelModal(btn) {
        const actionUrl = btn.getAttribute('data-action') || '#';
        const menuName = btn.getAttribute('data-menu') || '';
        const mitraName = btn.getAttribute('data-mitra') || '';
        const deadline = btn.getAttribute('data-deadline') || '';

        btlForm.setAttribute('action', actionUrl);
        btlReasonInput.value = '';
        if (btlOrderMeta) {
            btlOrderMeta.textContent = menuName ? (menuName + ' • ' + mitraName) : '';
        }
        if (btlDeadlineHint) {
            btlDeadlineHint.textContent = deadline;
        }

        btlModal.classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => btlReasonInput.focus(), 50);
    }

    function closeCancelModal() {
        btlModal.classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    function closeRefundModal() {
        if (btlRefundModal) {
            btlRefundModal.classList.add('is-hidden');
        }
        document.body.style.overflow = '';
    }

    if (btlModal) {
        btlModal.addEventListener('click', (e) => {
            if (e.target === btlModal) closeCancelModal();
        });
    }

    if (btlRefundModal) {
        document.body.style.overflow = 'hidden';
        btlRefundModal.addEventListener('click', (e) => {
            if (e.target === btlRefundModal) closeRefundModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (btlModal && !btlModal.classList.contains('is-hidden')) {
                closeCancelModal();
            }
            if (btlRefundModal && !btlRefundModal.classList.contains('is-hidden')) {
                closeRefundModal();
            }
        }
    });
</script>
