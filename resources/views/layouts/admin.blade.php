<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Batix')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

    {{-- ── Sidebar ── --}}
    <aside class="admin-sidebar" id="sidebar">

        <div class="admin-sidebar__header">
            <a href="/" class="admin-sidebar__logo">Batix</a>
            <span class="admin-sidebar__badge">Admin</span>
        </div>

        <nav class="admin-sidebar__nav" aria-label="Admin navigation">

            <p class="admin-sidebar__nav-label">Utama</p>
            <ul role="list">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
   class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="admin-nav-link__icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        </span>
                        Dashboard
                    </a>
                </li>
            </ul>

            <p class="admin-sidebar__nav-label">Produk</p>
            <ul role="list">
                <li>
                    <a href="/admin/produk" class="admin-nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}">
                        <span class="admin-nav-link__icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        Motif & Produk
                    </a>
                </li>
            </ul>

            <p class="admin-sidebar__nav-label">Transaksi</p>
            <ul role="list">
                <li>
                    <a href="/admin/transaksi" class="admin-nav-link {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                        <span class="admin-nav-link__icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        Transaksi
                    </a>
                </li>
            </ul>
            <p class="admin-sidebar__nav-label">Sync Produk</p>
            <ul role="list">
                <li>
                    <a href="/admin/sync" class="admin-nav-link {{ request()->is('admin/sync*') ? 'active' : '' }}">
                        <span class="admin-nav-link__icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        Sync Produk
                    </a>
                </li>
            </ul>

        </nav>

        <div class="admin-sidebar__footer">
            <div class="admin-sidebar__user">
                <img src="https://picsum.photos/seed/adminuser/40/40" alt="Admin" class="admin-sidebar__user-avatar">
                <div>
                    <p class="admin-sidebar__user-name">Admin</p>
                    <p class="admin-sidebar__user-role">adminbatix@gmail.com</p>
                </div>
            </div>
            <a href="/" class="admin-sidebar__logout" title="Keluar">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </a>
        </div>

    </aside>

    {{-- ── Main Wrapper ── --}}
    <div class="admin-main" id="admin-main">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div class="admin-topbar__left">
                <button class="admin-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="admin-topbar__breadcrumb">
                    <span>Batix</span>
                    <span class="sep">/</span>
                    <span class="current">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>
            <div class="admin-topbar__right">
                <div class="admin-topbar__date">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
                <div class="admin-topbar__notif" title="Notifikasi">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="admin-topbar__notif-dot"></span>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="admin-content">
            @yield('content')
        </div>

    </div>

    {{-- <script src="{{ asset('js/admin.js') }}" defer></script> --}}
    @stack('scripts')
</body>
</html>