<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BatikAI' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>
<body>

    <div class="auth-page">

        {{-- ── Left Visual Panel ── --}}
        <aside class="auth-panel" aria-hidden="true">
            <div class="auth-panel__bg"></div>
            <div class="auth-panel__overlay"></div>
            <div class="auth-panel__pattern"></div>

            <div class="auth-panel__top">
                <a href="/" class="auth-panel__logo">Batik<span>AI</span></a>
            </div>

            <div class="auth-panel__content">
                <p class="auth-panel__tag">
                    <span class="auth-panel__tag-dot"></span>
                    Warisan & Kecerdasan
                </p>
                <h2 class="auth-panel__title">
                    {{ $panelTitle ?? 'Motif Batik' }}<br>
                    <em>{{ $panelTitleItalic ?? 'Tanpa Batas' }}</em>
                </h2>
                <p class="auth-panel__desc">
                    {{ $panelDesc ?? 'Ribuan motif batik AI terinspirasi warisan Nusantara. Pilih, lisensi, dan gunakan secara bebas.' }}
                </p>
                <div class="auth-panel__stats">
                    <div>
                        <p class="auth-panel__stat-num">10.000+</p>
                        <p class="auth-panel__stat-lbl">Motif tersedia</p>
                    </div>
                    <div>
                        <p class="auth-panel__stat-num">500+</p>
                        <p class="auth-panel__stat-lbl">Referensi otentik</p>
                    </div>
                    <div>
                        <p class="auth-panel__stat-num">100%</p>
                        <p class="auth-panel__stat-lbl">Bebas komersial</p>
                    </div>
                </div>
            </div>

            <div class="auth-panel__bottom">
                <blockquote class="auth-panel__quote">
                    <p>{{ $panelQuote ?? '"Batik bukan sekadar kain, ia adalah doa yang ditulis dengan canting."' }}</p>
                    <cite>{{ $panelQuoteAuthor ?? '— Pepatah Nusantara' }}</cite>
                </blockquote>
            </div>
        </aside>

        {{-- ── Right Form Panel ── --}}
        <main class="auth-form-panel">
            <div class="auth-form-wrap">
                @yield('content')
            </div>
        </main>

    </div>

    <script src="{{ asset('js/auth.js') }}" defer></script>
    @stack('scripts')
</body>
</html>