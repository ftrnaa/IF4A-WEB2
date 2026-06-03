<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lupa Sandi — BatikAI</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>

<div class="auth-page">

  {{-- ── Left Visual Panel ────────────────────────────────── --}}
  <aside class="auth-panel">
    <div class="auth-panel__bg"></div>
    <div class="auth-panel__overlay"></div>
    <div class="auth-panel__pattern"></div>

    <div class="auth-panel__top">
      <a href="{{ url('/') }}" class="auth-panel__logo">Batik<span>AI</span></a>
    </div>

    <div class="auth-panel__content">
      <span class="auth-panel__tag">
        <span class="auth-panel__tag-dot"></span>
        Keamanan Akun
      </span>

      <h1 class="auth-panel__title" id="panel-title">
        Pulihkan<br>
        <em>Akses</em> Anda<br>
        dengan Mudah
      </h1>

      <p class="auth-panel__desc" id="panel-desc">
        Kami akan mengirimkan kode OTP pemulihan sandi ke alamat surel Anda.
        Proses ini aman dan hanya membutuhkan beberapa menit.
      </p>

      {{-- Step Progress Indicator --}}
      <div class="fp-progress">
        <div class="fp-progress__step fp-progress__step--active" id="prog-1">
          <div class="fp-progress__dot">1</div>
          <span class="fp-progress__lbl">Email</span>
        </div>
        <div class="fp-progress__line" id="prog-line-1"></div>
        <div class="fp-progress__step" id="prog-2">
          <div class="fp-progress__dot">2</div>
          <span class="fp-progress__lbl">Verifikasi OTP</span>
        </div>
        <div class="fp-progress__line" id="prog-line-2"></div>
        <div class="fp-progress__step" id="prog-3">
          <div class="fp-progress__dot">3</div>
          <span class="fp-progress__lbl">Sandi Baru</span>
        </div>
      </div>

      <div class="auth-panel__stats">
        <div>
          <p class="auth-panel__stat-num">3</p>
          <p class="auth-panel__stat-lbl">Langkah Mudah</p>
        </div>
        <div>
          <p class="auth-panel__stat-num">100%</p>
          <p class="auth-panel__stat-lbl">Terenkripsi</p>
        </div>
        <div>
          <p class="auth-panel__stat-num">10 mnt</p>
          <p class="auth-panel__stat-lbl">Masa Berlaku OTP</p>
        </div>
      </div>
    </div>

    <div class="auth-panel__bottom">
      <div class="auth-panel__quote">
        <p>"Batik adalah perjalanan panjang — jangan biarkan satu langkah terhenti hanya karena lupa sandi."</p>
        <cite>— Tim BatikAI</cite>
      </div>
    </div>
  </aside>

  {{-- ── Right Form Panel ─────────────────────────────────── --}}
  <main class="auth-form-panel">
    <div class="auth-form-wrap">

      {{-- ═══════════════════════════════════════════
           STEP 1 — Masukkan Email
      ════════════════════════════════════════════ --}}
      <div id="step-1" class="fp-step fp-step--active">
        <p class="auth-form__eyebrow">Langkah 1 dari 3</p>
        <h2 class="auth-form__title">Lupa <em>Sandi?</em></h2>
        <p class="auth-form__subtitle">
          Masukkan surel yang terdaftar. Kami akan mengirim kode OTP 6 digit ke kotak masuk Anda.<br>
          Ingat sandi Anda? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

        @if ($errors->has('email') && !session('otp_sent'))
          <div class="fp-alert fp-alert--error">
            <span class="fp-alert__icon">!</span>
            <p>{{ $errors->first('email') }}</p>
          </div>
        @endif

        <form class="auth-form" id="fp-form-email" method="POST" action="{{ route('password.sendOtp') }}" novalidate>
          @csrf

          <div class="form-group">
            <label class="form-label" for="email">Alamat Surel</label>
            <div class="form-input-wrap fp-input-wrap">
              <span class="fp-input-icon">✉</span>
              <input
                class="form-input fp-input-icon-pad @error('email') is-error @enderror"
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@surel.com"
                autocomplete="email"
                required
              />
            </div>
            @error('email')
              <span class="form-error"><span>⚠</span> {{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="btn-auth" id="fp-btn-1">
            <span class="btn-auth__text">Kirim OTP</span>
            <span class="btn-auth__loader" style="display:none"><span class="fp-spinner"></span> Mengirim…</span>
          </button>
        </form>

        <div class="fp-help">
          <p>Tidak menerima surel?</p>
          <ul>
            <li>Periksa folder <strong>spam</strong> atau <strong>junk</strong></li>
            <li>Pastikan surel yang dimasukkan sudah benar</li>
            <li>Kode OTP berlaku selama <strong>10 menit</strong></li>
          </ul>
        </div>
      </div>

      {{-- ═══════════════════════════════════════════
           STEP 2 — Masukkan OTP
      ════════════════════════════════════════════ --}}
      <div id="step-2" class="fp-step" style="display:none;">
        <p class="auth-form__eyebrow">Langkah 2 dari 3</p>
        <h2 class="auth-form__title">Verifikasi <em>OTP</em></h2>
        <p class="auth-form__subtitle">
          Masukkan kode 6 digit yang telah dikirim ke<br>
          <strong id="otp-email-display" class="fp-email-highlight"></strong>
        </p>

        @if ($errors->has('otp'))
          <div class="fp-alert fp-alert--error">
            <span class="fp-alert__icon">!</span>
            <p>{{ $errors->first('otp') }}</p>
          </div>
        @endif

        <form class="auth-form" id="fp-form-otp" method="POST" action="{{ route('password.verifyOtp') }}" novalidate>
          @csrf
          <input type="hidden" name="email" id="otp-email-hidden" value="{{ old('email') }}" />

          {{-- OTP 6 Digit Input --}}
          <div class="form-group">
            <label class="form-label">Kode OTP</label>
            <div class="fp-otp-wrap">
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" data-index="0" />
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="1" />
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="2" />
              <span class="fp-otp-sep">—</span>
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="3" />
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="4" />
              <input class="fp-otp-box" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="5" />
            </div>
            <input type="hidden" name="otp" id="otp-hidden-value" />
            <span class="form-error fp-otp-error" style="display:none;"><span>⚠</span> Isi semua 6 digit kode OTP.</span>
          </div>

          {{-- Countdown & Resend --}}
          <div class="fp-otp-timer">
            <span class="fp-otp-timer__label">Kode kedaluwarsa dalam</span>
            <span class="fp-otp-timer__count" id="otp-countdown">10:00</span>
          </div>

          <button type="submit" class="btn-auth" id="fp-btn-2">
            <span class="btn-auth__text">Verifikasi OTP</span>
            <span class="btn-auth__loader" style="display:none"><span class="fp-spinner"></span> Memverifikasi…</span>
          </button>
        </form>

        <div class="fp-resend-row">
          <span>Tidak menerima kode?</span>
          <button class="fp-resend-link" id="fp-resend-otp" disabled>
            Kirim Ulang (<span id="resend-countdown">60</span>s)
          </button>
        </div>

        <button class="fp-back-btn" onclick="fpGoToStep(1)">← Ganti Surel</button>
      </div>

      {{-- ═══════════════════════════════════════════
           STEP 3 — Password Baru
      ════════════════════════════════════════════ --}}
      <div id="step-3" class="fp-step" style="display:none;">
        <p class="auth-form__eyebrow">Langkah 3 dari 3</p>
        <h2 class="auth-form__title">Sandi <em>Baru</em></h2>
        <p class="auth-form__subtitle">
          Buat sandi baru yang kuat untuk akun Anda.
          Gunakan kombinasi huruf, angka, dan simbol.
        </p>

        @if ($errors->has('password'))
          <div class="fp-alert fp-alert--error">
            <span class="fp-alert__icon">!</span>
            <p>{{ $errors->first('password') }}</p>
          </div>
        @endif

        <form class="auth-form" id="fp-form-reset" method="POST" action="{{ route('password.resetWithOtp') }}" novalidate>
          @csrf
          <input type="hidden" name="email" id="reset-email-hidden" value="{{ old('email') }}" />
          <input type="hidden" name="otp" id="reset-otp-hidden" />

          <div class="form-group">
            <label class="form-label" for="new-password">Sandi Baru</label>
            <div class="form-input-wrap">
              <input
                class="form-input"
                type="password"
                id="new-password"
                name="password"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                required
                oninput="checkStrengthFP(this.value)"
              />
              <button type="button" class="toggle-pass" onclick="togglePassword('new-password', this)" aria-label="Tampilkan sandi">👁</button>
            </div>
            {{-- Password Strength --}}
            <div class="pass-strength" id="pass-strength-fp" style="display:none;">
              <div class="pass-strength__bars">
                <div class="pass-strength__bar" id="fp-bar1"></div>
                <div class="pass-strength__bar" id="fp-bar2"></div>
                <div class="pass-strength__bar" id="fp-bar3"></div>
                <div class="pass-strength__bar" id="fp-bar4"></div>
              </div>
              <span class="pass-strength__label" id="fp-strength-label"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirm-password">Konfirmasi Sandi Baru</label>
            <div class="form-input-wrap">
              <input
                class="form-input"
                type="password"
                id="confirm-password"
                name="password_confirmation"
                placeholder="Ulangi sandi baru"
                autocomplete="new-password"
                required
              />
              <button type="button" class="toggle-pass" onclick="togglePassword('confirm-password', this)" aria-label="Tampilkan sandi">👁</button>
            </div>
            <span class="form-error fp-confirm-error" style="display:none;"><span>⚠</span> Sandi tidak cocok.</span>
          </div>

          <button type="submit" class="btn-auth" id="fp-btn-3">
            <span class="btn-auth__text">Simpan Sandi Baru</span>
            <span class="btn-auth__loader" style="display:none"><span class="fp-spinner"></span> Menyimpan…</span>
          </button>
        </form>
      </div>

      {{-- ═══════════════════════════════════════════
           STEP 4 — Sukses
      ════════════════════════════════════════════ --}}
      <div id="step-success" class="fp-step" style="display:none;">
        <div class="fp-success">
          <div class="fp-success__icon-wrap">
            <span class="fp-success__icon">✓</span>
            <div class="fp-sent__ring"></div>
          </div>
          <h2 class="auth-form__title" style="margin-top:1.5rem;text-align:center;">
            Sandi Berhasil<br><em>Diperbarui!</em>
          </h2>
          <p class="auth-form__subtitle" style="text-align:center;margin-bottom:1.75rem;">
            Sandi akun Anda telah berhasil diubah.<br>
            Silakan masuk kembali menggunakan sandi baru Anda.
          </p>
          <a href="{{ route('login') }}" class="btn-auth" style="text-align:center;text-decoration:none;display:block;">
            Masuk Sekarang
          </a>
        </div>
      </div>

    </div>
  </main>

</div>
<script>
    window.initialStep = "{{ session('step', 'step1') }}";
</script>
<script src="{{ asset('js/forgot-password.js') }}"></script>
</body>
</html>