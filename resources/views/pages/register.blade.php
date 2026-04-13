
@extends('layouts.auth', [
    'title' => 'Daftar — BatikAI',
    'panelTitle' => 'Bergabung',
    'panelTitleItalic' => 'Bersama Kami',
    'panelDesc' => 'Buat akun gratis dan dapatkan akses ke ribuan motif batik AI.',
    'panelQuote' => '"Batik adalah identitas, bukan sekadar mode."',
    'panelQuoteAuthor' => '— Warisan Nusantara'
])
@section('content')
>

    {{-- Header --}}
    <p class="auth-form__eyebrow">Mulai perjalananmu</p>
    <h1 class="auth-form__title">Daftar <em>Gratis</em></h1>
    <p class="auth-form__subtitle">
        Sudah punya akun?
        <a href="/masuk">Masuk di sini</a>
    </p>

    {{-- Social Register --}}
    <div class="auth-social" style="margin-bottom:1.5rem">
        <button class="btn-social" type="button">
            <span class="btn-social__icon">G</span>
            Daftar dengan Google
        </button>
    </div>

    {{-- Divider --}}
    <div class="auth-divider">atau daftar dengan email</div>

    {{-- Form --}}
    <form class="auth-form" action="/daftar" method="POST" novalidate>
        @csrf

        {{-- Name Row --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="first_name">Nama Depan</label>
                <input
                    class="form-input"
                    type="text"
                    id="first_name"
                    name="first_name"
                    placeholder="Budi"
                    autocomplete="given-name"
                    required
                >
            </div>
            <div class="form-group">
                <label class="form-label" for="last_name">Nama Belakang</label>
                <input
                    class="form-input"
                    type="text"
                    id="last_name"
                    name="last_name"
                    placeholder="Santoso"
                    autocomplete="family-name"
                >
            </div>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input
                class="form-input"
                type="email"
                id="email"
                name="email"
                placeholder="nama@email.com"
                autocomplete="email"
                required
            >
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label class="form-label" for="password">Kata Sandi</label>
            <div class="form-input-wrap">
                <input
                    class="form-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    autocomplete="new-password"
                    required
                    oninput="checkStrength(this.value)"
                >
                <button type="button" class="toggle-pass" aria-label="Tampilkan sandi" onclick="togglePassword('password', this)">
                    👁
                </button>
            </div>
            {{-- Password Strength --}}
            <div class="pass-strength" id="pass-strength" style="display:none">
                <div class="pass-strength__bars">
                    <div class="pass-strength__bar" id="bar1"></div>
                    <div class="pass-strength__bar" id="bar2"></div>
                    <div class="pass-strength__bar" id="bar3"></div>
                    <div class="pass-strength__bar" id="bar4"></div>
                </div>
                <p class="pass-strength__label" id="strength-label">Terlalu lemah</p>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
            <div class="form-input-wrap">
                <input
                    class="form-input"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi kata sandi"
                    autocomplete="new-password"
                    required
                >
                <button type="button" class="toggle-pass" aria-label="Tampilkan sandi" onclick="togglePassword('password_confirmation', this)">
                    👁
                </button>
            </div>
        </div>

        {{-- Terms --}}
        <div class="form-check">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">
                Saya setuju dengan
                <a href="/syarat-ketentuan">Syarat & Ketentuan</a>
                serta
                <a href="/kebijakan-privasi">Kebijakan Privasi</a>
                BatikAI
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth">
            Buat Akun Gratis
        </button>

    </form>

    {{-- Back to home --}}
    <p style="text-align:center; margin-top:1.8rem; font-size:.8rem; color:var(--clr-text-muted)">
        <a href="/" style="color:var(--clr-brown); text-decoration:underline; text-underline-offset:2px">
            ← Kembali ke Beranda
        </a>
    </p>

@endsection