<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Verifikasi Kode OTP — Plan2Eat</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="icon" type="image/png" sizes="32x32" href="..\images\favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
  <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
</head>
<body class="auth-shell">

<main class="otp-page-wrapper">
  <div class="otp-card-shell">

    <header class="otp-topbar">
      <a href="{{ route('register.form', ['role' => $role ?? session('register_role', 'pelanggan')]) }}" class="otp-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"/>
          <polyline points="12 19 5 12 12 5"/>
        </svg>
      </a>

      <div class="otp-topbar-brand">
        <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat" style="height: 26px; width: auto;">
        <span class="otp-topbar-title">Verify Code</span>
      </div>
    </header>

    <section class="otp-main-body">

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
        Kode dikirim ke <strong>{{ $email }}</strong>
        <a href="{{ route('register.form', ['role' => $role ?? session('register_role', 'pelanggan')]) }}" class="otp-edit-link">
          Ubah
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
          </svg>
        </a>
      </p>

      <form id="otpForm" action="{{ route('verify-otp') }}" method="POST" class="otp-form" novalidate>
        @csrf

        <input type="hidden" name="otp_code" id="otpHiddenInput" value="">

        <div class="otp-boxes-row" id="otpBoxesGroup">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" class="otp-box is-focused" aria-label="Digit 1" autofocus>
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 2">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 3">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 4">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 5">
          <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box" aria-label="Digit 6">
        </div>

        <div class="otp-resend-row">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#414942" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
            <path d="M12 7v5l3 2"/>
          </svg>

          <span id="otpResendWrapper">
            Kirim ulang (<span id="otpTimer">0:45</span>)
          </span>

          <button type="submit" form="otpResendForm" id="otpResendBtn" class="otp-resend-btn" style="display: none;">
            Kirim ulang kode
          </button>
        </div>

        <button type="submit" id="otpSubmitBtn" class="btn-verify-otp" disabled>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10" fill="#FFFFFF"/>
            <polyline points="8 12.5 10.8 15.2 16.5 9.5" stroke="#05371F" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Verifikasi</span>
        </button>
      </form>

      {{-- Form terpisah (tidak nested) untuk Kirim Ulang OTP --}}
      <form id="otpResendForm" action="{{ route('resend-otp') }}" method="POST" hidden>
        @csrf
      </form>
    </section>

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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const boxes = Array.from(document.querySelectorAll('#otpBoxesGroup .otp-box'));
    const submitBtn = document.getElementById('otpSubmitBtn');
    const hiddenInput = document.getElementById('otpHiddenInput');
    const keypadButtons = document.querySelectorAll('.keypad-btn');
    const resendWrapper = document.getElementById('otpResendWrapper');
    const resendBtn = document.getElementById('otpResendBtn');

    function syncOtpState(activeIndex = null) {
        const code = boxes.map(box => box.value).join('');
        hiddenInput.value = code;

        const targetFocus = activeIndex !== null
            ? activeIndex
            : Math.min(code.length, boxes.length - 1);

        boxes.forEach((box, index) => {
            box.classList.toggle('is-filled', box.value !== '');
            box.classList.toggle('is-focused', index === targetFocus);
        });

        const isComplete = code.length === boxes.length;
        submitBtn.disabled = !isComplete;
        submitBtn.classList.toggle('is-ready', isComplete);
    }

    boxes.forEach((box, index) => {
        box.addEventListener('focus', () => {
            syncOtpState(index);
        });

        box.addEventListener('input', (event) => {
            const cleaned = event.target.value.replace(/[^0-9]/g, '');

            if (cleaned.length > 1) {
                const digits = cleaned.slice(0, boxes.length).split('');

                boxes.forEach((item, i) => {
                    item.value = digits[i] || '';
                });

                const nextIndex = Math.min(digits.length, boxes.length - 1);
                boxes[nextIndex].focus();
                syncOtpState(nextIndex);
                return;
            }

            event.target.value = cleaned;

            if (cleaned && index < boxes.length - 1) {
                boxes[index + 1].focus();
                syncOtpState(index + 1);
            } else {
                syncOtpState(index);
            }
        });

        box.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace') {
                if (box.value) {
                    box.value = '';
                    syncOtpState(index);
                } else if (index > 0) {
                    boxes[index - 1].value = '';
                    boxes[index - 1].focus();
                    syncOtpState(index - 1);
                }
            } else if (event.key === 'ArrowLeft' && index > 0) {
                boxes[index - 1].focus();
            } else if (event.key === 'ArrowRight' && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }
        });

        box.addEventListener('paste', (event) => {
            event.preventDefault();

            const pasted = (event.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/[^0-9]/g, '')
                .slice(0, boxes.length);

            if (!pasted) return;

            boxes.forEach((item, i) => {
                item.value = pasted[i] || '';
            });

            const nextIndex = Math.min(pasted.length, boxes.length - 1);
            boxes[nextIndex].focus();
            syncOtpState(nextIndex);
        });
    });

    keypadButtons.forEach(button => {
        button.addEventListener('click', () => {
            const key = button.dataset.key;

            if (key === 'backspace') {
                const lastFilledIndex = boxes
                    .map(box => box.value !== '')
                    .lastIndexOf(true);

                if (lastFilledIndex !== -1) {
                    boxes[lastFilledIndex].value = '';
                    boxes[lastFilledIndex].focus();
                    syncOtpState(lastFilledIndex);
                }

                return;
            }

            const emptyIndex = boxes.findIndex(box => box.value === '');

            if (emptyIndex !== -1) {
                boxes[emptyIndex].value = key;

                const nextEmpty = boxes.findIndex(box => box.value === '');
                const nextIndex = nextEmpty !== -1
                    ? nextEmpty
                    : boxes.length - 1;

                boxes[nextIndex].focus();
                syncOtpState(nextIndex);
            }
        });
    });

    let remainingSeconds = 45;

    const countdown = setInterval(() => {
        remainingSeconds--;

        const timerEl = document.getElementById('otpTimer');

        if (remainingSeconds > 0 && timerEl) {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = String(remainingSeconds % 60).padStart(2, '0');

            timerEl.textContent = `${minutes}:${seconds}`;
        } else {
            clearInterval(countdown);

            if (resendWrapper) {
                resendWrapper.style.display = 'none';
            }

            if (resendBtn) {
                resendBtn.style.display = 'inline-block';
            }
        }
    }, 1000);

    syncOtpState(0);
});
</script>
@include('partials.popup_notification')
</body>
</html>
