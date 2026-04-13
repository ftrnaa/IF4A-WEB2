@extends('layouts.auth', [
    'title' => 'Masuk — BatikAI',
    'panelTitle' => 'Selamat',
    'panelTitleItalic' => 'Kembali',
    'panelDesc' => 'Masuk ke akunmu dan lanjutkan menjelajahi ribuan motif batik AI pilihan dari warisan Nusantara.',
    'panelQuote' => '"Setiap helai kain menyimpan cerita yang menunggu untuk ditemukan kembali."',
])
    
>
@section('content')

    {{-- Header --}}
    <p class="auth-form__eyebrow">Sudah punya akun</p>
    <h1 class="auth-form__title">Masuk ke <em>BatikAI</em></h1>
    <p class="auth-form__subtitle">
        Belum punya akun?
        <a href="/daftar">Daftar sekarang</a>
    </p>

    {{-- Social Login --}}
    <div class="auth-social" style="margin-bottom:1.5rem">
        <button class="btn-social" type="button">
            <span class="btn-social__icon">G</span>
            Lanjutkan dengan Google
        </button>
    </div>

    {{-- Divider --}}
    <div class="auth-divider">atau masuk dengan email</div>

    {{-- Form --}}
    <form class="auth-form" action="/masuk" method="POST" novalidate>
        @csrf

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
            <div class="form-group--row">
                <label class="form-label" for="password">Kata Sandi</label>
                <a href="/lupa-sandi" class="forgot-link">Lupa sandi?</a>
            </div>
            <div class="form-input-wrap">
                <input
                    class="form-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="toggle-pass" aria-label="Tampilkan sandi" onclick="togglePassword('password', this)">
                    👁
                </button>
            </div>
        </div>

        {{-- Remember me --}}
        <div class="form-check">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember">Ingat saya di perangkat ini</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth">
            Masuk Sekarang
        </button>

    </form>

    {{-- Back to home --}}
    <p style="text-align:center; margin-top:1.8rem; font-size:.8rem; color:var(--clr-text-muted)">
        <a href="/" style="color:var(--clr-brown); text-decoration:underline; text-underline-offset:2px">
            ← Kembali ke Beranda
        </a>
    </p>

@endsection