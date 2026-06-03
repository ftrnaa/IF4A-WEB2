/* ============================================================
   BatikAI — Forgot Password JavaScript
   File: public/js/forgot-password.js
   ============================================================ */

/* ─────────────────────────────────────────────────────────
   STATE
───────────────────────────────────────────────────────── */
const FP = {
  currentStep : 1,
  email       : '',
  otp         : '',
  otpTimer    : null,
  resendTimer : null,
};
document.addEventListener("DOMContentLoaded", function () {
    const step = window.initialStep || "step1";

    if (step === "step2") {
        fpGoToStep(2);
    }

    if (step === "step3") {
        fpGoToStep(3);
    }
});
/* ─────────────────────────────────────────────────────────
   INIT
───────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  initStep1();
  initStep2();
  initStep3();

  // Jika Laravel redirect kembali dengan error OTP → tetap di step 2
  const hasOtpError = document.querySelector('.fp-alert--error') &&
                      document.getElementById('step-2');
  if (hasOtpError && sessionStorage.getItem('fp_email')) {
    fpGoToStep(2, sessionStorage.getItem('fp_email'));
  }
});

/* ─────────────────────────────────────────────────────────
   STEP NAVIGATION
───────────────────────────────────────────────────────── */
function fpGoToStep(step, email) {
  // Sembunyikan semua step
  document.querySelectorAll('.fp-step').forEach(el => {
    el.style.display = 'none';
  });

  const target = document.getElementById(`step-${step}`) ||
                 document.getElementById('step-success');
  if (target) {
    target.style.display = 'block';
    // Re-trigger animation
    target.style.animation = 'none';
    requestAnimationFrame(() => {
      target.style.animation = 'fp-fadeUp .42s cubic-bezier(.22,.68,0,1.2) both';
    });
  }

  FP.currentStep = step;
  fpUpdateProgress(step);
  fpUpdatePanel(step);

  if (email) FP.email = email;
}

/* ─────────────────────────────────────────────────────────
   PROGRESS INDICATOR
───────────────────────────────────────────────────────── */
function fpUpdateProgress(step) {
  for (let i = 1; i <= 3; i++) {
    const dot  = document.getElementById(`prog-${i}`);
    const line = document.getElementById(`prog-line-${i}`);
    if (!dot) continue;

    dot.classList.remove('fp-progress__step--active', 'fp-progress__step--done');

    if (i < step)      dot.classList.add('fp-progress__step--done');
    else if (i === step) dot.classList.add('fp-progress__step--active');

    if (line) {
      line.classList.toggle('fp-progress__line--done', i < step);
    }
  }
}

/* ─────────────────────────────────────────────────────────
   LEFT PANEL TEXT UPDATE
───────────────────────────────────────────────────────── */
const PANEL_CONTENT = {
  1: {
    title : 'Pulihkan<br><em>Akses</em> Anda<br>dengan Mudah',
    desc  : 'Kami akan mengirimkan kode OTP pemulihan sandi ke alamat surel Anda. Proses ini aman dan hanya membutuhkan beberapa menit.',
  },
  2: {
    title : 'Masukkan<br>Kode <em>OTP</em><br>Anda',
    desc  : 'Kode 6 digit telah dikirim ke surel Anda. Segera masukkan sebelum kode kedaluwarsa dalam 10 menit.',
  },
  3: {
    title : 'Buat Sandi<br>yang <em>Kuat</em><br>& Aman',
    desc  : 'Pilih sandi baru yang kuat. Gunakan kombinasi huruf besar, angka, dan simbol untuk keamanan maksimal.',
  },
};

function fpUpdatePanel(step) {
  const titleEl = document.getElementById('panel-title');
  const descEl  = document.getElementById('panel-desc');
  const content = PANEL_CONTENT[step];
  if (!titleEl || !descEl || !content) return;

  titleEl.style.opacity = '0';
  descEl.style.opacity  = '0';

  setTimeout(() => {
    titleEl.innerHTML = content.title;
    descEl.textContent = content.desc;
    titleEl.style.transition = 'opacity .4s ease';
    descEl.style.transition  = 'opacity .4s ease';
    titleEl.style.opacity = '1';
    descEl.style.opacity  = '1';
  }, 200);
}

/* ─────────────────────────────────────────────────────────
   STEP 1 — EMAIL FORM
───────────────────────────────────────────────────────── */
function initStep1() {
  const form      = document.getElementById('fp-form-email');
  const submitBtn = document.getElementById('fp-btn-1');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const emailInput = document.getElementById('email');
    const email = emailInput?.value.trim() ?? '';

    if (!email || !fpIsValidEmail(email)) {
      e.preventDefault();
      fpSetFieldError(emailInput, 'Masukkan alamat surel yang valid.');
      return;
    }

    fpClearFieldError(emailInput);
    FP.email = email;
    sessionStorage.setItem('fp_email', email);

    fpSetLoading(submitBtn, true, 'Mengirim…');

    // Untuk demo/preview tanpa backend: batalkan submit & lanjut ke step 2
    // Hapus blok di bawah ini jika sudah ada route Laravel
    // ──────────────────────────────────────────────────────
    if (window.FP_DEMO_MODE) {
      e.preventDefault();
      setTimeout(() => {
        fpSetLoading(submitBtn, false);
        fpGoToStep(2, email);
        fpPopulateStep2(email);
        fpStartOtpTimer();
        fpStartResendCountdown();
      }, 1200);
    }
    // ──────────────────────────────────────────────────────
  });
}

/* ─────────────────────────────────────────────────────────
   STEP 2 — OTP FORM
───────────────────────────────────────────────────────── */
function initStep2() {
  const form      = document.getElementById('fp-form-otp');
  const submitBtn = document.getElementById('fp-btn-2');
  if (!form) return;

  initOtpBoxes();

  form.addEventListener('submit', function (e) {
    const otp = collectOtp();

    if (otp.length < 6) {
      e.preventDefault();
      document.querySelector('.fp-otp-error').style.display = 'flex';
      shakeOtpBoxes();
      return;
    }

    document.querySelector('.fp-otp-error').style.display = 'none';
    FP.otp = otp;

    // Isi hidden inputs untuk dikirim ke server
    document.getElementById('otp-hidden-value').value   = otp;
    document.getElementById('otp-email-hidden').value   = FP.email;
    document.getElementById('reset-otp-hidden').value   = otp;
    document.getElementById('reset-email-hidden').value = FP.email;

    fpSetLoading(submitBtn, true, 'Memverifikasi…');

    // Demo mode: lanjut ke step 3
    if (window.FP_DEMO_MODE) {
      e.preventDefault();
      setTimeout(() => {
        fpSetLoading(submitBtn, false);
        clearInterval(FP.otpTimer);
        fpGoToStep(3);
      }, 1000);
    }
  });

  // Resend OTP
  const resendBtn = document.getElementById('fp-resend-otp');
  if (resendBtn) {
    resendBtn.addEventListener('click', () => {
      resendBtn.disabled = true;
      resendBtn.textContent = 'Mengirim ulang…';

      // Demo: simulasi kirim ulang
      if (window.FP_DEMO_MODE) {
        setTimeout(() => {
          fpStartOtpTimer();
          fpStartResendCountdown();
          clearOtpBoxes();
        }, 800);
      } else {
        // Submit form email ulang secara programatik
        const emailForm = document.getElementById('fp-form-email');
        if (emailForm) {
          document.getElementById('email').value = FP.email;
          emailForm.submit();
        }
      }
    });
  }
}

function fpPopulateStep2(email) {
  const display = document.getElementById('otp-email-display');
  if (display) display.textContent = email;
  document.getElementById('otp-email-hidden').value = email;
  clearOtpBoxes();
}

/* ─── OTP Boxes Interaction ──────────────────────────────── */
function initOtpBoxes() {
  const boxes = document.querySelectorAll('.fp-otp-box');
  if (!boxes.length) return;

  boxes.forEach((box, idx) => {
    box.addEventListener('keydown', e => {
      // Backspace → hapus & pindah ke kiri
      if (e.key === 'Backspace') {
        if (box.value === '' && idx > 0) {
          boxes[idx - 1].focus();
          boxes[idx - 1].value = '';
          boxes[idx - 1].classList.remove('is-filled');
        } else {
          box.value = '';
          box.classList.remove('is-filled');
        }
        return;
      }

      // Izinkan navigasi keyboard
      if (['ArrowLeft','ArrowRight','Tab'].includes(e.key)) return;

      // Hanya angka
      if (!/^\d$/.test(e.key)) {
        e.preventDefault();
      }
    });

    box.addEventListener('input', e => {
      const val = box.value.replace(/\D/g, '').slice(-1);
      box.value = val;

      if (val) {
        box.classList.add('is-filled');
        if (idx < boxes.length - 1) boxes[idx + 1].focus();
      } else {
        box.classList.remove('is-filled');
      }

      // Sembunyikan error saat mulai isi
      document.querySelector('.fp-otp-error').style.display = 'none';
      boxes.forEach(b => b.classList.remove('is-error'));
    });

    // Paste handler (salin 6 digit sekaligus)
    box.addEventListener('paste', e => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData)
        .getData('text')
        .replace(/\D/g, '')
        .slice(0, 6);

      [...pasted].forEach((ch, i) => {
        if (boxes[i]) {
          boxes[i].value = ch;
          boxes[i].classList.add('is-filled');
        }
      });

      const nextEmpty = [...boxes].findIndex(b => !b.value);
      if (nextEmpty !== -1) boxes[nextEmpty].focus();
      else boxes[boxes.length - 1].focus();
    });
  });

  // Auto-focus kotak pertama saat step 2 aktif
  setTimeout(() => boxes[0]?.focus(), 300);
}

function collectOtp() {
  return [...document.querySelectorAll('.fp-otp-box')]
    .map(b => b.value.trim())
    .join('');
}

function clearOtpBoxes() {
  document.querySelectorAll('.fp-otp-box').forEach(b => {
    b.value = '';
    b.classList.remove('is-filled', 'is-error');
  });
}

function shakeOtpBoxes() {
  document.querySelectorAll('.fp-otp-box').forEach(b => {
    b.classList.add('is-error');
    setTimeout(() => b.classList.remove('is-error'), 400);
  });
}

/* ─── OTP Countdown Timer (10 menit) ────────────────────── */
function fpStartOtpTimer() {
  clearInterval(FP.otpTimer);
  const el = document.getElementById('otp-countdown');
  if (!el) return;

  let totalSeconds = 10 * 60;

  FP.otpTimer = setInterval(() => {
    totalSeconds--;
    const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
    const s = String(totalSeconds % 60).padStart(2, '0');
    el.textContent = `${m}:${s}`;

    el.classList.toggle('is-urgent', totalSeconds <= 60);

    if (totalSeconds <= 0) {
      clearInterval(FP.otpTimer);
      el.textContent = '00:00';
      // Tampilkan pesan kedaluwarsa
      const errEl = document.querySelector('.fp-otp-error');
      if (errEl) {
        errEl.innerHTML = '<span>⚠</span> Kode OTP telah kedaluwarsa. Silakan kirim ulang.';
        errEl.style.display = 'flex';
      }
    }
  }, 1000);
}

/* ─── Resend Countdown (60 detik) ───────────────────────── */
function fpStartResendCountdown() {
  clearInterval(FP.resendTimer);
  const btn      = document.getElementById('fp-resend-otp');
  const countEl  = document.getElementById('resend-countdown');
  if (!btn || !countEl) return;

  btn.disabled = true;
  let secs = 60;
  countEl.textContent = secs;
  btn.innerHTML = `Kirim Ulang (<span id="resend-countdown">${secs}</span>s)`;

  FP.resendTimer = setInterval(() => {
    secs--;
    const span = document.getElementById('resend-countdown');
    if (span) span.textContent = secs;

    if (secs <= 0) {
      clearInterval(FP.resendTimer);
      btn.disabled = false;
      btn.textContent = 'Kirim Ulang OTP';
    }
  }, 1000);
}

/* ─────────────────────────────────────────────────────────
   STEP 3 — RESET PASSWORD FORM
───────────────────────────────────────────────────────── */
function initStep3() {
  const form      = document.getElementById('fp-form-reset');
  const submitBtn = document.getElementById('fp-btn-3');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const passInput    = document.getElementById('new-password');
    const confirmInput = document.getElementById('confirm-password');
    const confirmErr   = document.querySelector('.fp-confirm-error');
    let valid = true;

    // Validasi panjang
    if (!passInput.value || passInput.value.length < 8) {
      fpSetFieldError(passInput, 'Sandi minimal 8 karakter.');
      valid = false;
    } else {
      fpClearFieldError(passInput);
    }

    // Validasi cocok
    if (passInput.value !== confirmInput.value) {
      confirmInput.classList.add('is-error');
      if (confirmErr) confirmErr.style.display = 'flex';
      valid = false;
    } else {
      confirmInput.classList.remove('is-error');
      if (confirmErr) confirmErr.style.display = 'none';
    }

    if (!valid) { e.preventDefault(); return; }

    // Pastikan hidden fields terisi
    document.getElementById('reset-email-hidden').value = FP.email;
    document.getElementById('reset-otp-hidden').value   = FP.otp;

    fpSetLoading(submitBtn, true, 'Menyimpan…');

    // Demo mode: tampilkan success screen
    if (window.FP_DEMO_MODE) {
      e.preventDefault();
      setTimeout(() => {
        fpGoToStep('success');
        fpUpdateProgress(4); // semua done
      }, 1200);
    }
  });

  // Real-time confirm match indicator
  const confirmInput = document.getElementById('confirm-password');
  const confirmErr   = document.querySelector('.fp-confirm-error');
  confirmInput?.addEventListener('input', () => {
    const passVal = document.getElementById('new-password').value;
    if (confirmInput.value.length >= passVal.length) {
      const match = passVal === confirmInput.value;
      confirmInput.classList.toggle('is-error', !match);
      if (confirmErr) confirmErr.style.display = match ? 'none' : 'flex';
    }
  });
}

/* ─────────────────────────────────────────────────────────
   PASSWORD STRENGTH (reset page variant)
───────────────────────────────────────────────────────── */
function checkStrengthFP(value) {
  const wrap  = document.getElementById('pass-strength-fp');
  const label = document.getElementById('fp-strength-label');
  const bars  = [
    document.getElementById('fp-bar1'),
    document.getElementById('fp-bar2'),
    document.getElementById('fp-bar3'),
    document.getElementById('fp-bar4'),
  ];

  if (!wrap) return;

  if (!value) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'block';

  let score = 0;
  if (value.length >= 8)         score++;
  if (/[A-Z]/.test(value))       score++;
  if (/[0-9]/.test(value))       score++;
  if (/[^A-Za-z0-9]/.test(value)) score++;

  const levels = [
    { txt: 'Terlalu lemah', cls: 'active-weak',   color: '#E74C3C' },
    { txt: 'Cukup',         cls: 'active-fair',   color: '#E67E22' },
    { txt: 'Kuat',          cls: 'active-good',   color: '#F1C40F' },
    { txt: 'Sangat kuat',   cls: 'active-strong', color: '#27AE60' },
  ];

  bars.forEach(b => { if (b) b.className = 'pass-strength__bar'; });

  const idx = score - 1;
  if (idx >= 0) {
    for (let i = 0; i < score; i++) {
      bars[i]?.classList.add(levels[idx].cls);
    }
    if (label) {
      label.textContent = levels[idx].txt;
      label.style.color = levels[idx].color;
    }
  }
}

/* ─────────────────────────────────────────────────────────
   SHARED HELPERS
───────────────────────────────────────────────────────── */
function fpIsValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function fpSetLoading(btn, loading, loadText = 'Memproses…') {
  if (!btn) return;
  const txtEl    = btn.querySelector('.btn-auth__text');
  const loaderEl = btn.querySelector('.btn-auth__loader');

  if (loading) {
    if (txtEl)    txtEl.style.display    = 'none';
    if (loaderEl) {
      loaderEl.style.display = 'inline-flex';
      loaderEl.innerHTML     = `<span class="fp-spinner"></span> ${loadText}`;
    }
    btn.disabled = true;
  } else {
    if (txtEl)    txtEl.style.display    = 'inline';
    if (loaderEl) loaderEl.style.display = 'none';
    btn.disabled = false;
  }
}

function fpSetFieldError(input, message) {
  if (!input) return;
  fpClearFieldError(input);
  input.classList.add('is-error');
  const err = document.createElement('span');
  err.className = 'form-error fp-js-error';
  err.innerHTML = `<span>⚠</span> ${message}`;
  input.closest('.form-group')?.appendChild(err);
}

function fpClearFieldError(input) {
  if (!input) return;
  input.classList.remove('is-error');
  input.closest('.form-group')?.querySelector('.fp-js-error')?.remove();
}

/* ─────────────────────────────────────────────────────────
   RE-EXPORT togglePassword agar tombol 👁 tetap berfungsi
───────────────────────────────────────────────────────── */
if (typeof togglePassword === 'undefined') {
  function togglePassword(inputId, btn) {
    const input   = document.getElementById(inputId);
    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    btn.textContent = isHidden ? '🙈' : '👁';
    btn.setAttribute('aria-label', isHidden ? 'Sembunyikan sandi' : 'Tampilkan sandi');
  }
}