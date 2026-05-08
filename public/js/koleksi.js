/**
 * koleksi.js — Koleksi Batik Nusantara
 * Path: public/js/koleksi.js
 */
document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------
       1. ENTRANCE ANIMATION — Intersection Observer
    ------------------------------------------------ */
    const cards = document.querySelectorAll('.motif-card');
    const counter = document.getElementById('countVisible');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    cards.forEach((card) => observer.observe(card));

    /* ------------------------------------------------
       2. AUTO SLIDESHOW — ganti gambar tiap 2 detik
       Setiap card punya timer sendiri
    ------------------------------------------------ */
    cards.forEach((card) => {
        const wrap  = card.querySelector('.card-image-wrap');
        if (!wrap) return;

        const imgs  = wrap.querySelectorAll('.slide-img');
        const dots  = wrap.querySelectorAll('.dot');
        if (imgs.length <= 1) return; // hanya 1 gambar, skip

        let current = 0;

        function goTo(index) {
            imgs[current].classList.remove('active');
            dots[current]?.classList.remove('active');
            current = index % imgs.length;
            imgs[current].classList.add('active');
            dots[current]?.classList.add('active');
        }

        // Mulai slideshow otomatis tiap 2 detik
        setInterval(() => {
            goTo(current + 1);
        }, 2000);
    });

    /* ------------------------------------------------
       3. COUNTER — hitung card yang tampil
    ------------------------------------------------ */
    function updateCounter() {
        if (!counter) return;
        const visible = document.querySelectorAll('.motif-card:not([style*="display: none"])').length;
        counter.textContent = visible;
    }

    /* ------------------------------------------------
       4. FILTER — client-side filter per kategori
    ------------------------------------------------ */
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach((tab) => {
        tab.addEventListener('click', function () {
            filterTabs.forEach((b) => b.classList.remove('active'));
            this.classList.add('active');
            const kategori = this.dataset.kategori;
            cards.forEach((card) => {
                const kat  = card.dataset.kategori;
                const show = kategori === 'semua' || kat === kategori;
                card.style.display = show ? 'block' : 'none';
            });
            updateCounter();
        });
    });

    /* ------------------------------------------------
       5. AUTO SEARCH — debounce 500ms
    ------------------------------------------------ */
    const searchInput = document.getElementById('searchInput');
    const searchForm  = document.getElementById('searchForm');
    if (searchInput && searchForm) {
        let debounceTimer = null;
        searchInput.addEventListener('keyup', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });
    }
});