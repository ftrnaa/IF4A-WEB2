<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container navbar__inner">

        {{-- Logo --}}
        <a href="/" class="navbar__logo">Batik<span>AI</span></a>

        {{-- Desktop Nav --}}
        <ul class="navbar__nav" role="list">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="/koleksi" class="{{ request()->is('koleksi*') ? 'active' : '' }}">Koleksi</a></li>
            <li><a href="/tentang" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a></li>
        </ul>

        {{-- Auth Actions --}}
        <div class="navbar__actions">
            @auth
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-btn" id="userBtn" aria-haspopup="true" aria-expanded="false">
                        <span>{{ Auth::user()->first_name }}</span>
                        <svg class="chevron-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div class="dropdown-menu" id="dropdownMenu" role="menu">
                        {{-- User info header --}}
                        <div class="dropdown-header">
                            <p class="dropdown-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name ?? '' }}</p>
                            <p class="dropdown-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Member' }}</p>
                        </div>

                        {{-- Dashboard link --}}
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                                Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Dashboard
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>

                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item--danger" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/masuk" class="btn btn-outline">Masuk</a>
                <a href="/daftar" class="btn btn-primary">Daftar</a>
            @endauth
        </div>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggle" aria-label="Toggle menu" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div class="navbar__mobile" id="mobile-menu" aria-hidden="true">
        <ul role="list">
            <li><a href="/">Beranda</a></li>
            <li><a href="/koleksi">Koleksi</a></li>
            <li><a href="/tentang">Tentang</a></li>
            <li><a href="/masuk">Masuk</a></li>
            <li><a href="/daftar">Daftar</a></li>
        </ul>
    </div>
</nav>