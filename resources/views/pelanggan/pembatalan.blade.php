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
            <h3 class="btl-modal-title" id="btlModalTitle">Batalkan Pesanan Ini?</h3>
        </div>

        <form action="#" method="POST" id="btlCancelForm" class="btl-modal-form">
            @csrf
            {{-- Aktifkan baris di bawah jika route backend memakai method PUT/PATCH --}}
            {{-- @method('PUT') --}}

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
                    <strong>Apakah kamu yakin?</strong> Pesanan yang sudah dibatalkan tidak dapat diproses kembali oleh mitra.
                </p>
            </div>

            <div class="btl-modal-actions">
                <button type="button" class="btl-btn-no" onclick="closeCancelModal()">Tidak, Kembali</button>
                <button type="submit" class="btl-btn-yes">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const btlModal = document.getElementById('btlCancelModal');
    const btlForm = document.getElementById('btlCancelForm');
    const btlReasonInput = document.getElementById('btlReasonInput');

    function openCancelModal(btn) {
        const actionUrl = btn.getAttribute('data-action') || '#';
        btlForm.setAttribute('action', actionUrl);
        btlReasonInput.value = '';

        btlModal.classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => btlReasonInput.focus(), 50);
    }

    function closeCancelModal() {
        btlModal.classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    btlModal.addEventListener('click', (e) => {
        if (e.target === btlModal) closeCancelModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !btlModal.classList.contains('is-hidden')) {
            closeCancelModal();
        }
    });

    // Pengaman simulasi jika route backend pembatalan belum disambungkan
    btlForm.addEventListener('submit', (e) => {
        if (btlForm.getAttribute('action') === '#') {
            e.preventDefault();
            alert('Simulasi Pembatalan Berhasil.\nCatatan alasan: ' + btlReasonInput.value);
            closeCancelModal();
        }
    });
</script>