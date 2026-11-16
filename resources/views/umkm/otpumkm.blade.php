@extends('layouts.main')

@section('title', 'Verifikasi Kode OTP UMKM — Plan2Eat')
@section('hide_nav', true)
@section('body_class', 'auth-shell')

@section('content')
<main class="otp-page-wrapper">
  <div class="otp-card-shell">
    {{-- Top Bar --}}
    <header class="otp-topbar">
      <a href="{{ url('/registrasiumkm') }}" class="otp-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
      </a>

      <div class="otp-topbar-brand">
        <svg width="26" height="26" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="6" width="40" height="37" rx="10" fill="#05371F"/>
          <rect x="13" y="2" width="4" height="8" rx="2" fill="#FFFFFF"/>
          <rect x="29" y="2" width="4" height="8" rx="2" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="11" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="6.5" fill="#E8702A"/>
        </svg>
        <span class="otp-topbar-title">Verify Code</span>
      </div>
    </header>

    {{-- Konten Utama Verifikasi OTP --}}
    <section class="otp-main-body">
      {{-- Ilustrasi Perisai & Daun --}}
      <div class="otp-shield-badge" aria-hidden="true">
        <div class="otp-shield-ring">
          <div class="otp-shield-inner">
            <svg width="24" height="26" viewBox="0 0 24 26" fill="none">
              <path d="M12 1L3 5V11.5C3 17.6 6.84 23.25 12 25C17.16 23.25 21 17.6 21 11.5V5L12 1Z" fill="#05371F"/>
              <circle cx="12" cy="11" r="1.7" fill="#FCE5C0"/>
              <path d="M11.1 12.2H12.9L13.3 15.8H10.7L11.1 12.2Z" fill="#FCE5C0"/>
            </svg>
          </div>
        </div>
        <div class="otp-leaf-mini">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
        </div>
      </div>

      <h1 class="otp-heading">Verifikasi Kode</h1>
      <p class="otp-subtext">
        Kode dikirim ke <strong>{{ request('email', session('otp_email', 'joe@gmail.com')) }}</strong>
        <a href="{{ url('/registrasiumkm') }}" class="otp-edit-link">
          Ubah
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
          </svg>
        </a>
      </p>

      {{-- Form 6 Digit OTP --}}
      <form id="otpUmkmForm" action="{{ url('/registrasiumkm') }}" method="GET" class="otp-form" novalidate>
        <input type="hidden" name="step" value="mitra">
        <input type="hidden" name="otp_code" id="otpUmkmHidden" value="">

        <div class="otp-boxes-row" id="otpUmkmBoxes">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" class="otp-box is-focused" aria-label="Digit 1" autofocus>
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 2">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 3">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 4">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 5">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 6">
        </div>

        {{-- Timer Kirim Ulang --}}
        <div class="otp-resend-row">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#414942" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
            <path d="M12 7v5l3 2"/>
          </svg>
          <span id="otpUmkmResend">Kirim ulang (<span id="otpUmkmTimer">0:45</span>)</span>
        </div>

        {{-- Tombol Verifikasi --}}
        <button type="submit" id="otpUmkmSubmit" class="btn-verify-otp" disabled>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10" fill="#FFFFFF"/>
            <polyline points="8 12.5 10.8 15.2 16.5 9.5" stroke="#05371F" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Verifikasi</span>
        </button>
      </form>
    </section>

    {{-- Numeric Keypad Bawah (Mobile < 768px) --}}
    <section class="otp-keypad-sheet" aria-label="Keypad Numerik">
      <div class="otp-keypad-grid">
        <button type="button" class="keypad-btn" data-key="1">1</button>
        <button type="button" class="keypad-btn" data-key="2">2</button>
        <button type="button" class="keypad-btn" data-key="3">3</button>
        <button type="button" class="keypad-btn" data-key="4">4</button>
        <button type="button" class="keypad-btn" data-key="5">5</button>
        <button type="button" class="keypad-btn" data-key="6">6</button>
        <button type="button" class="keypad-btn" data-key="7">7</button>
        <button type="button" class="keypad-btn" data-key="8">8</button>
        <button type="button" class="keypad-btn" data-key="9">9</button>
        <div class="keypad-dot-cell" aria-hidden="true"><span></span></div>
        <button type="button" class="keypad-btn" data-key="0">0</button>
        <button type="button" class="keypad-btn keypad-backspace" data-key="backspace" aria-label="Hapus digit">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
            <line x1="18" y1="9" x2="12" y2="15"/>
            <line x1="12" y1="9" x2="18" y2="15"/>
          </svg>
        </button>
      </div>
    </section>
  </div>
</main>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const boxes = Array.from(document.querySelectorAll('#otpUmkmBoxes .otp-box'));
    const submitBtn = document.getElementById('otpUmkmSubmit');
    const hiddenInput = document.getElementById('otpUmkmHidden');
    const keypadButtons = document.querySelectorAll('.keypad-btn');
    const resendWrapper = document.getElementById('otpUmkmResend');

    function syncOtpState(activeIndex = null) {
      const code = boxes.map(b => b.value).join('');
      hiddenInput.value = code;

      const targetFocus = activeIndex !== null
        ? activeIndex
        : Math.min(code.length, boxes.length - 1);

      boxes.forEach((box, idx) => {
        box.classList.toggle('is-filled', box.value !== '');
        box.classList.toggle('is-focused', idx === targetFocus);
      });

      const isComplete = code.length === boxes.length;
      submitBtn.disabled = !isComplete;
      submitBtn.classList.toggle('is-ready', isComplete);
    }

    boxes.forEach((box, idx) => {
      box.addEventListener('focus', () => syncOtpState(idx));

      box.addEventListener('input', (e) => {
        const cleaned = e.target.value.replace(/[^0-9]/g, '');
        if (cleaned.length > 1) {
          cleaned.slice(0, boxes.length).split('').forEach((digit, i) => {
            if (boxes[i]) boxes[i].value = digit;
          });
          const nextIdx = Math.min(cleaned.length, boxes.length - 1);
          boxes[nextIdx].focus();
          syncOtpState(nextIdx);
          return;
        }

        e.target.value = cleaned;
        if (cleaned && idx < boxes.length - 1) {
          boxes[idx + 1].focus();
          syncOtpState(idx + 1);
        } else {
          syncOtpState(idx);
        }
      });

      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace') {
          if (box.value) {
            box.value = '';
            syncOtpState(idx);
          } else if (idx > 0) {
            boxes[idx - 1].value = '';
            boxes[idx - 1].focus();
            syncOtpState(idx - 1);
          }
        } else if (e.key === 'ArrowLeft' && idx > 0) {
          boxes[idx - 1].focus();
        } else if (e.key === 'ArrowRight' && idx < boxes.length - 1) {
          boxes[idx + 1].focus();
        }
      });

      box.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData)
          .getData('text')
          .replace(/[^0-9]/g, '')
          .slice(0, boxes.length);

        if (!pasted) return;
        pasted.split('').forEach((digit, i) => {
          boxes[i].value = digit;
        });
        const nextIdx = Math.min(pasted.length, boxes.length - 1);
        boxes[nextIdx].focus();
        syncOtpState(nextIdx);
      });
    });

    keypadButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const key = btn.dataset.key;
        if (key === 'backspace') {
          const lastFilledIndex = boxes.map(b => b.value !== '').lastIndexOf(true);
          if (lastFilledIndex !== -1) {
            boxes[lastFilledIndex].value = '';
            syncOtpState(lastFilledIndex);
          }
        } else {
          const emptyIndex = boxes.findIndex(b => b.value === '');
          if (emptyIndex !== -1) {
            boxes[emptyIndex].value = key;
            const nextEmpty = boxes.findIndex(b => b.value === '');
            syncOtpState(nextEmpty !== -1 ? nextEmpty : boxes.length - 1);
          }
        }
      });
    });

    let remainingSeconds = 45;
    const countdown = setInterval(() => {
      remainingSeconds--;
      const timerEl = document.getElementById('otpUmkmTimer');
      if (remainingSeconds > 0 && timerEl) {
        const mins = Math.floor(remainingSeconds / 60);
        const secs = String(remainingSeconds % 60).padStart(2, '0');
        timerEl.textContent = `${mins}:${secs}`;
      } else {
        clearInterval(countdown);
        resendWrapper.innerHTML = `<button type="button" class="otp-resend-btn" onclick="location.reload()">Kirim ulang kode</button>`;
      }
    }, 1000);

    syncOtpState(0);
  });
</script>
@endpush