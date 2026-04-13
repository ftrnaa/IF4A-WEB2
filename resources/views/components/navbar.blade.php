<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container navbar__inner">

        <a href="/" class="navbar__logo">Batik<span>AI</span></a>

        <ul class="navbar__nav" role="list">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="/koleksi" class="{{ request()->is('koleksi*') ? 'active' : '' }}">Koleksi</a></li>
            <li><a href="/tentang" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a></li>
        </ul>

        <div class="navbar__actions">
            <a href="/masuk" class="btn btn-outline">Masuk</a>
            <a href="/daftar" class="btn btn-primary">Daftar</a>
        </div>

        <button class="navbar-toggle" aria-label="Toggle menu" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>

    </div>

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