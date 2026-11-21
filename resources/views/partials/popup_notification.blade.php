@once
<style>
  /* Compact Top-Right Floating Alert Box Notification */
  .p2e-notif-stack {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10050;
    width: auto;
    min-width: 260px;
    max-width: 360px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
  }

  .p2e-notif-box {
    pointer-events: auto;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px 14px;
    border-radius: 10px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.14);
    animation: p2eSlideInRight 0.24s cubic-bezier(0.16, 1, 0.3, 1);
    transition: opacity 0.24s ease, transform 0.24s ease;
  }

  .p2e-notif-box.is-leaving {
    opacity: 0;
    transform: translateX(18px);
  }

  .p2e-notif-box.is-success {
    background: #E2F2DB;
    border: 1px solid #B7DAB0;
    border-left: 4px solid #1E6B3A;
    color: #16442B;
  }

  .p2e-notif-box.is-danger {
    background: #FDECEC;
    border: 1px solid #F5C2C2;
    border-left: 4px solid #DC2626;
    color: #991B1B;
  }

  @keyframes p2eSlideInRight {
    from {
      opacity: 0;
      transform: translateX(20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .p2e-notif-icon {
    flex-shrink: 0;
    margin-top: 1px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .p2e-notif-content {
    flex: 1 1 auto;
    min-width: 0;
  }

  .p2e-notif-title {
    margin: 0 0 2px;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.3;
  }

  .p2e-notif-text {
    margin: 0;
    font-size: 12.5px;
    font-weight: 500;
    line-height: 1.45;
    word-break: break-word;
  }

  .p2e-notif-list {
    margin: 4px 0 0;
    padding-left: 16px;
    font-size: 12px;
    line-height: 1.45;
  }

  .p2e-notif-close {
    flex-shrink: 0;
    background: transparent;
    border: none;
    color: inherit;
    opacity: 0.65;
    cursor: pointer;
    padding: 2px;
    line-height: 1;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.15s ease, background-color 0.15s ease;
  }

  .p2e-notif-close:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.06);
  }

  .p2e-notif-progress {
    position: absolute;
    left: 0;
    bottom: 0;
    height: 3px;
    width: 100%;
    transform-origin: left center;
  }

  .p2e-notif-box.is-success .p2e-notif-progress {
    background: rgba(22, 68, 43, 0.45);
  }

  .p2e-notif-box.is-danger .p2e-notif-progress {
    background: rgba(220, 38, 38, 0.5);
  }

  @media (max-width: 575px) {
    .p2e-notif-stack {
      top: 14px;
      right: 14px;
      min-width: 220px;
      max-width: calc(100vw - 28px);
    }
  }

  /* Confirmation Dialog Modal (only for actions needing confirmation) */
  .p2e-popup-backdrop {
    position: fixed;
    inset: 0;
    z-index: 10060;
    background: rgba(17, 24, 39, 0.52);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 1;
    transition: opacity 0.2s ease;
  }

  .p2e-popup-backdrop.is-hidden {
    display: none;
    opacity: 0;
  }

  .p2e-popup-card {
    width: 100%;
    max-width: 400px;
    background: #FFFFFF;
    border-radius: 20px;
    padding: 24px 22px 20px;
    box-shadow: 0 24px 54px rgba(15, 23, 42, 0.22);
    border: 1.5px solid #E5E7EB;
    text-align: center;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    animation: p2ePopIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  }

  @keyframes p2ePopIn {
    from {
      opacity: 0;
      transform: translateY(8px) scale(0.96);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  .p2e-popup-card.is-success {
    border-color: #A7F3D0;
  }

  .p2e-popup-card.is-danger {
    border-color: #FECACA;
  }

  .p2e-popup-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .p2e-popup-card.is-success .p2e-popup-icon-wrap {
    background: #ECFDF5;
    color: #16442B;
    border: 1.5px solid #A7F3D0;
  }

  .p2e-popup-card.is-danger .p2e-popup-icon-wrap {
    background: #FEF2F2;
    color: #DC2626;
    border: 1.5px solid #FECACA;
  }

  .p2e-popup-title {
    margin: 0 0 8px;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 17px;
    font-weight: 800;
    line-height: 1.3;
  }

  .p2e-popup-card.is-success .p2e-popup-title {
    color: #16442B;
  }

  .p2e-popup-card.is-danger .p2e-popup-title {
    color: #991B1B;
  }

  .p2e-popup-message {
    margin: 0 0 18px;
    font-size: 13.5px;
    line-height: 1.55;
    color: #4B5563;
    word-break: break-word;
  }

  .p2e-popup-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .p2e-popup-btn {
    flex: 1 1 0;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 12px;
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: background-color 0.16s ease, transform 0.1s ease;
  }

  .p2e-popup-btn:active {
    transform: scale(0.98);
  }

  .p2e-popup-btn-primary.is-success {
    background: #16442B;
    color: #FFFFFF;
  }

  .p2e-popup-btn-primary.is-success:hover {
    background: #0F301E;
  }

  .p2e-popup-btn-primary.is-danger {
    background: #DC2626;
    color: #FFFFFF;
  }

  .p2e-popup-btn-primary.is-danger:hover {
    background: #B91C1C;
  }

  .p2e-popup-btn-secondary {
    background: #F3F4F6;
    color: #374151;
    border: 1px solid #E5E7EB;
  }

  .p2e-popup-btn-secondary:hover {
    background: #E5E7EB;
  }
</style>
@endonce

{{-- Top-Right Compact Notification Container --}}
<div id="p2eNotifStack" class="p2e-notif-stack" aria-live="polite" aria-atomic="true"></div>

{{-- Confirmation Pop-up Modal (replaces native confirm before delete/reset/cairkan) --}}
<div id="p2eConfirmBackdrop" class="p2e-popup-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="p2eConfirmTitle">
  <div id="p2eConfirmCard" class="p2e-popup-card is-danger">
    <div id="p2eConfirmIcon" class="p2e-popup-icon-wrap" aria-hidden="true"></div>
    <h3 id="p2eConfirmTitle" class="p2e-popup-title">Konfirmasi Tindakan</h3>
    <p id="p2eConfirmMessage" class="p2e-popup-message"></p>
    <div class="p2e-popup-actions">
      <button type="button" id="p2eConfirmCancelBtn" class="p2e-popup-btn p2e-popup-btn-secondary">
        Batal
      </button>
      <button type="button" id="p2eConfirmOkBtn" class="p2e-popup-btn p2e-popup-btn-primary is-danger">
        Ya, Lanjutkan
      </button>
    </div>
  </div>
</div>

@php
  $p2eFlashPopup = null;
  if (session('deleted')) {
      $p2eFlashPopup = [
          'type' => 'deleted',
          'title' => 'Data Dihapus',
          'message' => session('deleted'),
      ];
  } elseif (session('error')) {
      $p2eFlashPopup = [
          'type' => 'error',
          'title' => 'Gagal Diproses',
          'message' => session('error'),
      ];
  } elseif (isset($errors) && $errors->any()) {
      $p2eFlashPopup = [
          'type' => 'error',
          'title' => 'Periksa Kembali Data Anda',
          'messages' => $errors->all(),
      ];
  } elseif (session('success')) {
      $p2eFlashPopup = [
          'type' => 'success',
          'title' => 'Berhasil',
          'message' => session('success'),
      ];
  } elseif (session('forgot-pw')) {
      $p2eFlashPopup = [
          'type' => 'success',
          'title' => 'Password Berhasil Diganti',
          'message' => 'Silakan masuk kembali menggunakan password baru Anda.',
      ];
  }
@endphp

@once
<script>
(function () {
  const ICONS = {
    success: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    deleted: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>',
    error: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    confirmDanger: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    confirmSuccess: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
  };

  window.showAppPopup = function (opts) {
    const stack = document.getElementById('p2eNotifStack');
    if (!stack) return;

    const options = typeof opts === 'string' ? { type: 'success', message: opts } : (opts || {});
    const type = options.type || 'success';
    const isDanger = (type === 'deleted' || type === 'error' || type === 'danger');
    const duration = typeof options.duration === 'number' ? options.duration : 3000;

    const box = document.createElement('div');
    box.className = 'p2e-notif-box ' + (isDanger ? 'is-danger' : 'is-success');
    box.setAttribute('role', 'alert');

    const iconWrap = document.createElement('span');
    iconWrap.className = 'p2e-notif-icon';
    iconWrap.setAttribute('aria-hidden', 'true');
    iconWrap.innerHTML = type === 'deleted' ? ICONS.deleted : (isDanger ? ICONS.error : ICONS.success);

    const content = document.createElement('div');
    content.className = 'p2e-notif-content';

    if (options.title) {
      const titleEl = document.createElement('p');
      titleEl.className = 'p2e-notif-title';
      titleEl.textContent = options.title;
      content.appendChild(titleEl);
    }

    if (Array.isArray(options.messages) && options.messages.length > 0) {
      if (options.messages.length === 1) {
        const msgEl = document.createElement('p');
        msgEl.className = 'p2e-notif-text';
        msgEl.textContent = options.messages[0];
        content.appendChild(msgEl);
      } else {
        const ul = document.createElement('ul');
        ul.className = 'p2e-notif-list';
        options.messages.forEach(function (msg) {
          const li = document.createElement('li');
          li.textContent = msg;
          ul.appendChild(li);
        });
        content.appendChild(ul);
      }
    } else {
      const msgEl = document.createElement('p');
      msgEl.className = 'p2e-notif-text';
      msgEl.textContent = options.message || '';
      content.appendChild(msgEl);
    }

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'p2e-notif-close';
    closeBtn.setAttribute('aria-label', 'Tutup notifikasi');
    closeBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

    const progressBar = document.createElement('div');
    progressBar.className = 'p2e-notif-progress';

    box.appendChild(iconWrap);
    box.appendChild(content);
    box.appendChild(closeBtn);
    box.appendChild(progressBar);
    stack.appendChild(box);

    let remaining = duration;
    let startTime = Date.now();
    let timerId = null;

    function dismiss() {
      if (timerId) clearTimeout(timerId);
      box.classList.add('is-leaving');
      setTimeout(function () {
        if (box.parentNode) box.parentNode.removeChild(box);
      }, 240);
    }

    function startTimer() {
      startTime = Date.now();
      progressBar.style.transition = 'transform ' + remaining + 'ms linear';
      requestAnimationFrame(function () {
        progressBar.style.transform = 'scaleX(0)';
      });
      timerId = setTimeout(dismiss, remaining);
    }

    function pauseTimer() {
      if (timerId) clearTimeout(timerId);
      const elapsed = Date.now() - startTime;
      remaining = Math.max(400, remaining - elapsed);
      const computedScale = Math.max(0, remaining / duration);
      progressBar.style.transition = 'none';
      progressBar.style.transform = 'scaleX(' + computedScale + ')';
    }

    closeBtn.addEventListener('click', dismiss);
    box.addEventListener('mouseenter', pauseTimer);
    box.addEventListener('mouseleave', startTimer);

    startTimer();
  };

  let pendingConfirmCallback = null;

  function getConfirmEls() {
    return {
      backdrop: document.getElementById('p2eConfirmBackdrop'),
      card: document.getElementById('p2eConfirmCard'),
      icon: document.getElementById('p2eConfirmIcon'),
      title: document.getElementById('p2eConfirmTitle'),
      message: document.getElementById('p2eConfirmMessage'),
      cancelBtn: document.getElementById('p2eConfirmCancelBtn'),
      okBtn: document.getElementById('p2eConfirmOkBtn')
    };
  }

  window.closeConfirmPopup = function () {
    const els = getConfirmEls();
    if (!els.backdrop) return;
    els.backdrop.classList.add('is-hidden');
    document.body.style.overflow = '';
    pendingConfirmCallback = null;
  };

  window.openConfirmPopup = function (opts) {
    const options = opts || {};
    const isDanger = options.isDanger !== false;
    const els = getConfirmEls();
    if (!els.backdrop || !els.card) return;

    els.card.classList.remove('is-success', 'is-danger');
    els.card.classList.add(isDanger ? 'is-danger' : 'is-success');

    els.okBtn.classList.remove('is-success', 'is-danger');
    els.okBtn.classList.add(isDanger ? 'is-danger' : 'is-success');

    els.icon.innerHTML = isDanger ? ICONS.confirmDanger : ICONS.confirmSuccess;
    els.title.textContent = options.title || 'Konfirmasi Tindakan';
    els.message.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan?';
    els.okBtn.textContent = options.confirmText || 'Ya, Lanjutkan';
    els.cancelBtn.textContent = options.cancelText || 'Batal';

    pendingConfirmCallback = typeof options.onConfirm === 'function' ? options.onConfirm : null;

    els.backdrop.classList.remove('is-hidden');
    document.body.style.overflow = 'hidden';
  };

  document.addEventListener('DOMContentLoaded', function () {
    const confirmEls = getConfirmEls();
    if (confirmEls.cancelBtn) {
      confirmEls.cancelBtn.addEventListener('click', window.closeConfirmPopup);
    }
    if (confirmEls.backdrop) {
      confirmEls.backdrop.addEventListener('click', function (e) {
        if (e.target === confirmEls.backdrop) {
          window.closeConfirmPopup();
        }
      });
    }
    if (confirmEls.okBtn) {
      confirmEls.okBtn.addEventListener('click', function () {
        const cb = pendingConfirmCallback;
        window.closeConfirmPopup();
        if (cb) {
          cb();
        }
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && confirmEls.backdrop && !confirmEls.backdrop.classList.contains('is-hidden')) {
        window.closeConfirmPopup();
      }
    });

    document.addEventListener('click', function (e) {
      const trigger = e.target.closest('[data-confirm]');
      if (!trigger || trigger.tagName === 'FORM') return;

      e.preventDefault();
      const msg = trigger.dataset.confirm || 'Apakah Anda yakin ingin melanjutkan?';
      const title = trigger.dataset.confirmTitle || 'Konfirmasi Tindakan';
      const confirmText = trigger.dataset.confirmOk || 'Ya, Lanjutkan';
      const isDanger = trigger.dataset.confirmDanger !== 'false';
      const targetFormId = trigger.dataset.confirmForm;
      const form = targetFormId ? document.getElementById(targetFormId) : trigger.closest('form');

      window.openConfirmPopup({
        title: title,
        message: msg,
        confirmText: confirmText,
        isDanger: isDanger,
        onConfirm: function () {
          if (form) {
            form.dataset.p2eConfirmed = '1';
            form.submit();
          }
        }
      });
    });

    document.addEventListener('submit', function (e) {
      const form = e.target;
      if (!form || !form.dataset || !form.dataset.confirm || form.dataset.p2eConfirmed === '1') {
        return;
      }
      e.preventDefault();
      window.openConfirmPopup({
        title: form.dataset.confirmTitle || 'Konfirmasi Tindakan',
        message: form.dataset.confirm,
        confirmText: form.dataset.confirmOk || 'Ya, Lanjutkan',
        isDanger: form.dataset.confirmDanger !== 'false',
        onConfirm: function () {
          form.dataset.p2eConfirmed = '1';
          form.submit();
        }
      });
    });

    @if (!empty($p2eFlashPopup))
      window.showAppPopup(@json($p2eFlashPopup));
    @endif
  });
})();
</script>
@endonce
