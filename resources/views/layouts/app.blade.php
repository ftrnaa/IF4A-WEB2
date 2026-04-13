<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Batik Nusantara AI — Ribuan motif batik dihasilkan AI, terinspirasi warisan leluhur.' }}">

    <title>{{ $title ?? 'Batik Nusantara AI' }}</title>

    {{-- Google Fonts (preconnect) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    {{-- Stack for page-specific head content --}}
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <x-navbar />

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- App JS --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- Stack for page-specific scripts --}}
    @stack('scripts')
</body>
</html>