<footer class="footer" role="contentinfo">
    <div class="container">

        {{-- Footer Grid --}}
        <div class="footer__grid">

            {{-- Brand --}}
            <div class="footer__col">
                <p class="footer__logo">Batik<span>AI</span></p>
                <p class="footer__tagline">
                    Platform motif batik berbasis AI, terinspirasi warisan Nusantara.
                    Bebas digunakan secara komersial.
                </p>
                <div class="footer__socials">
                    <a href="#" aria-label="Instagram">Instagram</a>
                    <a href="#" aria-label="Twitter / X">Twitter</a>
                </div>
            </div>

            {{-- Koleksi --}}
            <div class="footer__col">
                <p class="footer__col-title">Koleksi</p>
                <ul>
                    <li><a href="/koleksi">Semua Motif</a></li>
                    <li><a href="/koleksi?kategori=klasik">Motif Klasik</a></li>
                    <li><a href="/koleksi?kategori=modern">Motif Modern</a></li>
                    <li><a href="/koleksi?kategori=kontemporer">Motif Kontemporer</a></li>
                </ul>
            </div>

            {{-- Perusahaan --}}
            <div class="footer__col">
                <p class="footer__col-title">Perusahaan</p>
                <ul>
                    <li><a href="/tentang">Tentang Kami</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/karier">Karier</a></li>
                    <li><a href="/kontak">Kontak</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="footer__col">
                <p class="footer__col-title">Legal</p>
                <ul>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Lisensi Motif</a></li>
                </ul>
            </div>

        </div>

        {{-- Footer Bottom --}}
        <div class="footer__bottom">
            <p>&copy; {{ date('Y') }} BatikAI. Semua hak dilindungi.</p>
            <p>Dibuat dengan ❤ untuk Warisan Nusantara</p>
        </div>

    </div>
</footer>