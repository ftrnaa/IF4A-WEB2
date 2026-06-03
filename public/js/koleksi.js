/* ============================================================
   koleksi.js — Koleksi Batik Nusantara  (enhanced)
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------
       UTIL — Fisher-Yates shuffle (array in-place)
    ------------------------------------------------ */
    function shuffle(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }

    /* ------------------------------------------------
       1. CARD ENTRANCE ANIMATION
          Staggered slide-up per card dengan Intersection Observer
    ------------------------------------------------ */
    const cards   = document.querySelectorAll('.motif-card');
    const counter = document.getElementById('countVisible');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const card  = entry.target;
                const index = parseInt(card.dataset.animIndex || '0', 10);
                const delay = Math.min(index * 65, 400);
                setTimeout(() => {
                    card.classList.add('show');
                }, delay);
                observer.unobserve(card);
            }
        });
    }, { threshold: 0.06, rootMargin: '0px 0px -20px 0px' });

    cards.forEach((card, i) => {
        card.dataset.animIndex = i;
        observer.observe(card);
    });

    /* ------------------------------------------------
       2. AUTO SLIDESHOW — fixed order, Ken Burns effect
    ------------------------------------------------ */
    const KB_CLASSES = ['kenburns-r', 'kenburns-l'];

    cards.forEach((card, cardIndex) => {

        const wrap = card.querySelector('.card-image-wrap');
        if (!wrap) return;

        const imgs = Array.from(wrap.querySelectorAll('.slide-img'));
        const dots = Array.from(wrap.querySelectorAll('.dot'));

        if (imgs.length <= 1) return;

        const cardId     = card.getAttribute('href') || `card-${cardIndex}`;
        const storageKey = `slide-index-${cardId}`;

        let current = parseInt(localStorage.getItem(storageKey) || '0', 10);
        if (current >= imgs.length) current = 0;

        function applyKenBurns(imgEl, idx) {
            imgEl.classList.remove(...KB_CLASSES);
            imgEl.classList.add(KB_CLASSES[idx % 2]);
        }

        function goTo() {
            const prev = current;
            current++;
            if (current >= imgs.length) current = 0;

            imgs[prev].classList.remove('active', ...KB_CLASSES);
            dots[prev]?.classList.remove('active');

            imgs[current].classList.add('active');
            applyKenBurns(imgs[current], current);
            dots[current]?.classList.add('active');
        }

        imgs[current].classList.add('active');
        applyKenBurns(imgs[current], current);
        dots[current]?.classList.add('active');

        const initialDelay = (cardIndex * 317) % 5000;
        let timer = null;

        function startTimer() {
            clearInterval(timer);
            timer = setInterval(goTo, 5000);
        }

        function handleVisibility() {
            if (document.hidden) {
                clearInterval(timer);
            } else {
                startTimer();
            }
        }

        setTimeout(() => {
            startTimer();
            document.addEventListener('visibilitychange', handleVisibility);
        }, initialDelay);
    });

    /* ------------------------------------------------
       3. COUNTER — hitung card yang tampil
    ------------------------------------------------ */
    function updateCounter() {
        if (!counter) return;
        const visible = [...document.querySelectorAll('.motif-card')]
            .filter(c => c.style.display !== 'none').length;

        counter.style.transition = 'opacity 0.2s';
        counter.style.opacity    = '0';
        setTimeout(() => {
            counter.textContent   = visible;
            counter.style.opacity = '1';
        }, 150);
    }

    /* ------------------------------------------------
       4. FILTER DROPDOWN TOGGLE
    ------------------------------------------------ */
    const dropdownBtn  = document.getElementById('filterDropdownBtn');
    const dropdownMenu = document.getElementById('filterDropdownMenu');

    if (dropdownBtn && dropdownMenu) {

        dropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = dropdownMenu.classList.contains('open');
            dropdownMenu.classList.toggle('open', !isOpen);
            dropdownBtn.classList.toggle('open', !isOpen);
        });

        // Tutup kalau klik di luar dropdown
        document.addEventListener('click', function () {
            dropdownMenu.classList.remove('open');
            dropdownBtn.classList.remove('open');
        });

        // Cegah menu menutup kalau klik di dalam menu
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('open');
                dropdownBtn.classList.remove('open');
                dropdownBtn.blur();
            }
        });
    }

    /* ------------------------------------------------
       5. AUTO SEARCH — debounce 500ms
    ------------------------------------------------ */
    const searchInput = document.getElementById('searchInput');
    const searchForm  = document.getElementById('searchForm');

    if (searchInput && searchForm) {
        let debounceTimer = null;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);

            this.style.borderColor = 'var(--gold-light)';
            this.style.boxShadow   = '0 0 0 4px rgba(184,146,74,0.10)';

            debounceTimer = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });

        searchInput.addEventListener('blur', function () {
            clearTimeout(debounceTimer);
            this.style.borderColor = '';
            this.style.boxShadow   = '';
        });
    }

    /* ------------------------------------------------
       6. HOVER — magnetic card tilt effect
    ------------------------------------------------ */
    const TILT_MAX = 4;

    cards.forEach((card) => {
        card.addEventListener('mousemove', function (e) {
            const rect  = this.getBoundingClientRect();
            const cx    = rect.left + rect.width  / 2;
            const cy    = rect.top  + rect.height / 2;
            const dx    = (e.clientX - cx) / (rect.width  / 2);
            const dy    = (e.clientY - cy) / (rect.height / 2);
            const tiltX = (-dy * TILT_MAX).toFixed(2);
            const tiltY = ( dx * TILT_MAX).toFixed(2);

            this.style.transform =
                `translateY(-8px) scale(1.01) perspective(800px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });

    /* ------------------------------------------------
       7. STICKY FILTER BAR SENTINEL
    ------------------------------------------------ */
    const filterBar = document.querySelector('.filter-bar');
    if (filterBar) {
        const observer2 = new IntersectionObserver(
            ([e]) => filterBar.classList.toggle('is-sticky', e.intersectionRatio < 1),
            { threshold: [1] }
        );
        const sentinel = document.createElement('div');
        sentinel.style.cssText = 'height:1px;pointer-events:none;';
        filterBar.insertAdjacentElement('beforebegin', sentinel);
        observer2.observe(sentinel);
    }

});