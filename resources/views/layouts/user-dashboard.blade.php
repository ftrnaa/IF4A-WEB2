<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard — BatikAI')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-dashboard.css') }}">
    @stack('styles')
</head>
<body class="user-dash-body">

    {{-- ── Sidebar ── --}}
    <aside class="user-sidebar" id="user-sidebar">

        <div class="user-sidebar__header">
            <a href="/" class="user-sidebar__logo">Batik<span>AI</span></a>
            <div class="user-sidebar__profile">
                <div class="user-sidebar__avatar-wrap">
                    <img src="https://picsum.photos/seed/userme/80/80"
                         alt="Profil" class="user-sidebar__avatar">
                    <span class="user-sidebar__avatar-status"></span>
                </div>
                <div>
                    <p class="user-sidebar__name">Rina Susanti</p>
                    <p class="user-sidebar__member">✦ Member</p>
                </div>
            </div>
        </div>

        <nav class="user-sidebar__nav">
            <p class="user-sidebar__nav-label">Menu</p>
            <ul role="list">
                <li>
                    <a href="/dashboard" class="user-nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        </span>
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="/dashboard/lisensi" class="user-nav-link {{ request()->is('dashboard/lisensi*') ? 'active' : '' }}">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        Lisensi Saya
                        <span class="user-nav-link__badge">3</span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/sertifikat" class="user-nav-link {{ request()->is('dashboard/sertifikat*') ? 'active' : '' }}">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </span>
                        Sertifikat
                    </a>
                </li>
                <li>
                    <a href="/dashboard/riwayat" class="user-nav-link {{ request()->is('dashboard/riwayat*') ? 'active' : '' }}">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </span>
                        Riwayat Beli
                    </a>
                </li>
            </ul>

            <p class="user-sidebar__nav-label">Akun</p>
            <ul role="list">
                <li>
                    <a href="/dashboard/profil" class="user-nav-link {{ request()->is('dashboard/profil*') ? 'active' : '' }}">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a href="/koleksi" class="user-nav-link">
                        <span class="user-nav-link__icon">
                            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        Jelajahi Koleksi
                    </a>
                </li>
            </ul>
        </nav>

        <div class="user-sidebar__footer">
            <a href="/" class="user-sidebar__logout">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>
        </div>

    </aside>

    {{-- ── Main ── --}}
    <div class="user-main" id="user-main">

        <header class="user-topbar">
            <div class="user-topbar__left">
                <button class="user-topbar__toggle" onclick="toggleUserSidebar()" aria-label="Menu">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="user-topbar__breadcrumb">
                    <span>Dashboard</span>
                    <span class="sep">/</span>
                    <span class="current">@yield('breadcrumb', 'Beranda')</span>
                </div>
            </div>
            <div class="user-topbar__right">
                <a href="/koleksi" class="user-topbar__shop">🛍 Beli Motif</a>
                <div class="user-topbar__notif">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="user-topbar__notif-dot"></span>
                </div>
            </div>
        </header>

        <div class="user-content">
            @yield('content')
        </div>

    </div>

    {{-- Toast --}}
    <div class="user-toast" id="user-toast"></div>

    <script src="{{ asset('js/user-dashboard.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
