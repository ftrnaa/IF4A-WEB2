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
          Setiap kartu punya delay unik supaya masuk bergelombang
    ------------------------------------------------ */
    const cards = document.querySelectorAll('.motif-card');
    const counter = document.getElementById('countVisible');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const card  = entry.target;
                const index = parseInt(card.dataset.animIndex || '0', 10);
                // Stagger: max 400ms delay, lalu stop bertumpuk
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
      - Urutan gambar tetap
      - Efek Ken Burns: pan kiri-kanan bergantian
      - Transisi cross-fade
      - Interval 5 detik
------------------------------------------------ */

const KB_CLASSES = ['kenburns-r', 'kenburns-l'];

cards.forEach((card, cardIndex) => {

    const wrap = card.querySelector('.card-image-wrap');
    if (!wrap) return;

    const imgs = Array.from(wrap.querySelectorAll('.slide-img'));
    const dots = Array.from(wrap.querySelectorAll('.dot'));

    if (imgs.length <= 1) return;

    // mulai dari gambar pertama
  const cardId = card.getAttribute('href') || `card-${cardIndex}`;

const storageKey = `slide-index-${cardId}`;

let current = parseInt(
    localStorage.getItem(storageKey) || '0',
    10
);

if (current >= imgs.length) {
    current = 0;
}

    function applyKenBurns(imgEl, idx) {

        imgEl.classList.remove(...KB_CLASSES);

        // arah ken burns bergantian
        imgEl.classList.add(KB_CLASSES[idx % 2]);
    }

    function goTo() {

        const prev = current;

        // next slide
        current++;

        // balik ke awal
        if (current >= imgs.length) {
            current = 0;
        }

        // remove active lama
        imgs[prev].classList.remove('active', ...KB_CLASSES);
        dots[prev]?.classList.remove('active');

        // active baru
        imgs[current].classList.add('active');

        applyKenBurns(imgs[current], current);

        dots[current]?.classList.add('active');
    }

    // gambar pertama aktif
    imgs[current].classList.add('active');

    applyKenBurns(imgs[current], current);

    dots[current]?.classList.add('active');

    // delay awal beda tiap card
    const initialDelay = (cardIndex * 317) % 5000;

    let timer = null;

    function startTimer() {

        clearInterval(timer);

        timer = setInterval(() => {

            goTo();

        }, 5000);
    }

    // pause kalau tab tidak aktif
    function handleVisibility() {

        if (document.hidden) {

            clearInterval(timer);

        } else {

            startTimer();

        }
    }

    setTimeout(() => {

        startTimer();

        document.addEventListener(
            'visibilitychange',
            handleVisibility
        );

    }, initialDelay);

});
    /* ------------------------------------------------
       3. COUNTER — hitung card yang tampil
    ------------------------------------------------ */
    function updateCounter() {
        if (!counter) return;
        const visible = [...document.querySelectorAll('.motif-card')]
            .filter(c => c.style.display !== 'none').length;

        // Animate number change
        counter.style.transition = 'opacity 0.2s';
        counter.style.opacity    = '0';
        setTimeout(() => {
            counter.textContent  = visible;
            counter.style.opacity = '1';
        }, 150);
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

            // Visual feedback: border pulse saat mengetik
            this.style.borderColor = 'var(--gold-light)';
            this.style.boxShadow   = '0 0 0 4px rgba(184,146,74,0.10)';

            debounceTimer = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });

        // Hapus visual feedback jika user berhenti sebelum 500ms
        searchInput.addEventListener('blur', function () {
            clearTimeout(debounceTimer);
            this.style.borderColor = '';
            this.style.boxShadow   = '';
        });
    }

    /* ------------------------------------------------
       6. HOVER — magnetic card effect
          Kartu sedikit miring mengikuti posisi mouse
    ------------------------------------------------ */
    const TILT_MAX = 4; // derajat maksimum

    cards.forEach((card) => {
        card.addEventListener('mousemove', function (e) {
            const rect   = this.getBoundingClientRect();
            const cx     = rect.left + rect.width  / 2;
            const cy     = rect.top  + rect.height / 2;
            const dx     = (e.clientX - cx) / (rect.width  / 2);
            const dy     = (e.clientY - cy) / (rect.height / 2);
            const tiltX  = (-dy * TILT_MAX).toFixed(2);
            const tiltY  = ( dx * TILT_MAX).toFixed(2);

            this.style.transform =
                `translateY(-8px) scale(1.01) perspective(800px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });

    /* ------------------------------------------------
       7. SMOOTH FILTER TAB INDICATOR
          Tanda garis aktif ikut bergerak ke tab yang diklik
    ------------------------------------------------ */
    const filterBar = document.querySelector('.filter-bar');
    if (filterBar) {
        // Subtle border bottom animation on sticky
        const observer2 = new IntersectionObserver(
            ([e]) => filterBar.classList.toggle('is-sticky', e.intersectionRatio < 1),
            { threshold: [1] }
        );
        // Sentinel element
        const sentinel = document.createElement('div');
        sentinel.style.cssText = 'height:1px;pointer-events:none;';
        filterBar.insertAdjacentElement('beforebegin', sentinel);
        observer2.observe(sentinel);
    }

});