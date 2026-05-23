/**
 * detail-motif.js — Koleksi Batik Nusantara  (enhanced)
 * Path: public/js/detail-motif.js
 */

/* ============================================================
   STATE
============================================================ */
let current = 0;
let total   = 0;
let isDragging = false;
let dragStartX = 0;
let dragDelta  = 0;
let autoTimer  = null;

/* ============================================================
   SLIDER — init
============================================================ */
function initSlider(totalSlides) {
    total = totalSlides;

    // Klik pada gambar → buka lightbox
    document.querySelectorAll('.slide img').forEach((img, i) => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => openLightbox(i));
    });

    buildLightbox();
    startAutoPlay();
    initDrag();
    initParallax();
}

/* ============================================================
   SLIDER — navigation
============================================================ */
function goToSlide(n, skipAnim) {
    current = ((n % total) + total) % total;

    const slidesEl = document.getElementById('slides');
    if (slidesEl) {
        if (skipAnim) {
            slidesEl.style.transition = 'none';
            slidesEl.style.transform  = `translateX(-${current * 100}%)`;
            // Re-enable transition after reflow
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    slidesEl.style.transition = '';
                });
            });
        } else {
            slidesEl.style.transform = `translateX(-${current * 100}%)`;
        }
    }

    // Sync dots
    document.querySelectorAll('#dots .dot').forEach((d, i) => {
        d.classList.toggle('active', i === current);
    });

    // Sync thumbnails
    document.querySelectorAll('#thumbStrip .thumb-item').forEach((t, i) => {
        t.classList.toggle('active', i === current);
        if (i === current) {
            t.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        }
    });

    // Counter
    const counter = document.getElementById('slideCounter');
    if (counter) {
        counter.style.opacity = '0';
        setTimeout(() => {
            counter.textContent = `${current + 1} / ${total}`;
            counter.style.opacity = '1';
        }, 150);
        counter.style.transition = 'opacity 0.2s';
    }

    // Reset autoplay on manual nav
    resetAutoPlay();
}

function changeSlide(dir) {
    goToSlide(current + dir);
}

/* ============================================================
   AUTO PLAY — advance tiap 6 detik saat tidak di-hover
============================================================ */
function startAutoPlay() {
    if (total <= 1) return;
    const wrap = document.getElementById('galleryWrap');
    if (!wrap) return;

    autoTimer = setInterval(() => {
        if (!wrap.matches(':hover') && !document.hidden) {
            goToSlide(current + 1);
        }
    }, 6000);
}

function resetAutoPlay() {
    clearInterval(autoTimer);
    startAutoPlay();
}

// Pause saat tab tersembunyi
document.addEventListener('visibilitychange', () => {
    if (document.hidden) clearInterval(autoTimer);
    else startAutoPlay();
});

/* ============================================================
   DRAG — mouse drag pada gallery
============================================================ */
function initDrag() {
    const wrap     = document.getElementById('galleryWrap');
    const slidesEl = document.getElementById('slides');
    if (!wrap || !slidesEl) return;

    function onDragStart(clientX) {
        isDragging  = true;
        dragStartX  = clientX;
        dragDelta   = 0;
        slidesEl.style.transition = 'none';
        wrap.style.cursor = 'grabbing';
    }

    function onDragMove(clientX) {
        if (!isDragging) return;
        dragDelta = clientX - dragStartX;
        const base = -(current * 100);
        const pct  = (dragDelta / wrap.offsetWidth) * 100;
        slidesEl.style.transform = `translateX(calc(${base}% + ${dragDelta}px))`;
    }

    function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        slidesEl.style.transition = '';
        wrap.style.cursor = 'zoom-in';

        if (Math.abs(dragDelta) > wrap.offsetWidth * 0.12) {
            goToSlide(current + (dragDelta < 0 ? 1 : -1));
        } else {
            // Snap back
            slidesEl.style.transform = `translateX(-${current * 100}%)`;
        }
    }

    // Mouse
    wrap.addEventListener('mousedown',  (e) => onDragStart(e.clientX));
    window.addEventListener('mousemove', (e) => onDragMove(e.clientX));
    window.addEventListener('mouseup',   ()  => onDragEnd());

    // Touch
    wrap.addEventListener('touchstart', (e) => onDragStart(e.touches[0].clientX), { passive: true });
    wrap.addEventListener('touchmove',  (e) => onDragMove(e.touches[0].clientX),  { passive: true });
    wrap.addEventListener('touchend',   ()  => onDragEnd());

    // Prevent accidental image drag
    wrap.addEventListener('dragstart', (e) => e.preventDefault());
}

/* ============================================================
   PARALLAX — foto bergerak sedikit saat kursor bergerak
============================================================ */
function initParallax() {
    const wrap = document.getElementById('galleryWrap');
    if (!wrap) return;

    const FACTOR = 8;

    wrap.addEventListener('mousemove', (e) => {
        const rect = wrap.getBoundingClientRect();
        const cx   = rect.left + rect.width  / 2;
        const cy   = rect.top  + rect.height / 2;
        const dx   = (e.clientX - cx) / (rect.width  / 2); // -1 … 1
        const dy   = (e.clientY - cy) / (rect.height / 2); // -1 … 1

        const activeSlide = wrap.querySelectorAll('.slide')[current];
        if (!activeSlide) return;
        const img = activeSlide.querySelector('img');
        if (!img) return;

        img.style.transform = `scale(1.05) translate(${dx * FACTOR}px, ${dy * FACTOR}px)`;
    });

    wrap.addEventListener('mouseleave', () => {
        const activeSlide = wrap.querySelectorAll('.slide')[current];
        if (!activeSlide) return;
        const img = activeSlide.querySelector('img');
        if (img) img.style.transform = '';
    });
}

/* ============================================================
   LIGHTBOX — build
============================================================ */
let lbIndex  = 0;
let lbImages = [];

function buildLightbox() {
    document.querySelectorAll('.slide').forEach((slide) => {
        const img   = slide.querySelector('img');
        const label = slide.querySelector('.slide-label');
        lbImages.push({
            src:   img   ? img.src           : '',
            label: label ? label.textContent.trim() : '',
        });
    });

    const lb = document.createElement('div');
    lb.id = 'lightbox';
    lb.innerHTML = `
        <div class="lb-backdrop"></div>
        <div class="lb-box">
            <button class="lb-close" aria-label="Tutup">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <button class="lb-btn lb-prev" aria-label="Sebelumnya">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <div class="lb-img-wrap">
                <img id="lbImg" src="" alt="" draggable="false">
                <span id="lbLabel" class="lb-label"></span>
            </div>
            <button class="lb-btn lb-next" aria-label="Berikutnya">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <polyline points="9 6 15 12 9 18"/>
                </svg>
            </button>
            <div id="lbCounter" class="lb-counter"></div>
        </div>
    `;
    document.body.appendChild(lb);

    lb.querySelector('.lb-backdrop').addEventListener('click', closeLightbox);
    lb.querySelector('.lb-close').addEventListener('click',   closeLightbox);
    lb.querySelector('.lb-prev').addEventListener('click',    () => lbGo(lbIndex - 1));
    lb.querySelector('.lb-next').addEventListener('click',    () => lbGo(lbIndex + 1));

    // Swipe dalam lightbox
    const imgWrap = lb.querySelector('.lb-img-wrap');
    let lbStartX = 0;
    imgWrap.addEventListener('touchstart', (e) => { lbStartX = e.touches[0].clientX; }, { passive: true });
    imgWrap.addEventListener('touchend',   (e) => {
        const diff = lbStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) lbGo(lbIndex + (diff > 0 ? 1 : -1));
    });

    // Mouse wheel di lightbox
    lb.addEventListener('wheel', (e) => {
        e.preventDefault();
        lbGo(lbIndex + (e.deltaY > 0 ? 1 : -1));
    }, { passive: false });
}

function openLightbox(index) {
    lbGo(index);
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.style.opacity = '0';
    setTimeout(() => {
        lb.classList.remove('open');
        lb.style.opacity = '';
    }, 320);
    document.body.style.overflow = '';
}

function lbGo(n) {
    lbIndex = ((n % lbImages.length) + lbImages.length) % lbImages.length;

    const img     = document.getElementById('lbImg');
    const label   = document.getElementById('lbLabel');
    const counter = document.getElementById('lbCounter');

    // Fade swap
    img.style.opacity   = '0';
    img.style.transform = 'scale(0.96)';
    setTimeout(() => {
        img.src             = lbImages[lbIndex].src;
        img.style.opacity   = '1';
        img.style.transform = 'scale(1)';
    }, 200);

    label.textContent   = lbImages[lbIndex].label;
    counter.textContent = `${lbIndex + 1} / ${lbImages.length}`;

    const lb     = document.getElementById('lightbox');
    const hasMul = lbImages.length > 1;
    lb.querySelector('.lb-prev').style.display = hasMul ? '' : 'none';
    lb.querySelector('.lb-next').style.display = hasMul ? '' : 'none';
}

/* ============================================================
   WISHLIST BUTTON — toggle state
============================================================ */
document.addEventListener('DOMContentLoaded', () => {

    const btnWishlist = document.querySelector('.btn-wishlist');
    if (btnWishlist) {
        let wished = false;
        btnWishlist.addEventListener('click', function () {
            wished = !wished;
            this.classList.toggle('wishlisted', wished);
            const textNode = [...this.childNodes].find(n => n.nodeType === 3 && n.textContent.trim());
            if (textNode) {
                textNode.textContent = wished ? ' Tersimpan' : ' Simpan ke Wishlist';
            }
        });
    }

    /* ------------------------------------------------
       STATS — count-up animation saat masuk viewport
    ------------------------------------------------ */
    const statNums = document.querySelectorAll('.stat-num[data-count]');
    if (statNums.length) {
        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el  = entry.target;
                const end = parseInt(el.dataset.count, 10);
                const dur = 1200;
                const step = 16;
                const steps = Math.round(dur / step);
                let n = 0;
                const inc = end / steps;
                const timer = setInterval(() => {
                    n = Math.min(n + inc, end);
                    el.textContent = Math.round(n).toLocaleString('id-ID');
                    if (n >= end) clearInterval(timer);
                }, step);
                statObserver.unobserve(el);
            });
        }, { threshold: 0.5 });
        statNums.forEach((el) => statObserver.observe(el));
    }

    /* ------------------------------------------------
       RELATED CARDS — staggered entrance
    ------------------------------------------------ */
    const relatedCards = document.querySelectorAll('.motif-grid .motif-card');
    if (relatedCards.length) {
        relatedCards.forEach((card) => {
            card.style.opacity   = '0';
            card.style.transform = 'translateY(24px)';
        });
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const card  = entry.target;
                const index = [...relatedCards].indexOf(card);
                const delay = index * 80;
                setTimeout(() => {
                    card.style.transition = 'opacity 0.55s cubic-bezier(0.22,1,0.36,1), transform 0.55s cubic-bezier(0.22,1,0.36,1)';
                    card.style.opacity    = '1';
                    card.style.transform  = 'translateY(0)';
                }, delay);
                cardObserver.unobserve(card);
            });
        }, { threshold: 0.1 });
        relatedCards.forEach((card) => cardObserver.observe(card));
    }

    /* ------------------------------------------------
       SECTION BOXES — staggered reveal
    ------------------------------------------------ */
    const boxes = document.querySelectorAll('.section-box');
    boxes.forEach((box, i) => {
        box.style.opacity   = '0';
        box.style.transform = 'translateY(18px)';
        setTimeout(() => {
            box.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            box.style.opacity    = '1';
            box.style.transform  = 'translateY(0)';
        }, 200 + i * 90);
    });

    /* ------------------------------------------------
       INFO PANEL — element stagger on load
    ------------------------------------------------ */
    const infoEls = document.querySelectorAll(
        '.motif-meta, .motif-keyword, .ornament, .motif-harga, .motif-harga-note, .btn-group, .stats-bar'
    );
    infoEls.forEach((el, i) => {
        el.style.opacity   = '0';
        el.style.transform = 'translateY(14px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
            el.style.opacity    = '1';
            el.style.transform  = 'translateY(0)';
        }, 120 + i * 70);
    });

    /* ------------------------------------------------
       MAGNETIC hover — tombol CTA ikut kursor
    ------------------------------------------------ */
    const btnBeli = document.querySelector('.btn-beli');
    if (btnBeli) {
        btnBeli.addEventListener('mousemove', (e) => {
            const rect = btnBeli.getBoundingClientRect();
            const dx   = (e.clientX - (rect.left + rect.width  / 2)) * 0.18;
            const dy   = (e.clientY - (rect.top  + rect.height / 2)) * 0.18;
            btnBeli.style.transform = `translateY(-2px) translate(${dx}px, ${dy}px)`;
        });
        btnBeli.addEventListener('mouseleave', () => {
            btnBeli.style.transform = '';
        });
    }

});

/* ============================================================
   KEYBOARD
============================================================ */
document.addEventListener('keydown', (e) => {
    const lb = document.getElementById('lightbox');
    if (lb && lb.classList.contains('open')) {
        if (e.key === 'ArrowLeft')  lbGo(lbIndex - 1);
        if (e.key === 'ArrowRight') lbGo(lbIndex + 1);
        if (e.key === 'Escape')     closeLightbox();
    } else {
        if (e.key === 'ArrowLeft')  changeSlide(-1);
        if (e.key === 'ArrowRight') changeSlide(1);
    }
});

/* ============================================================
   SWIPE pada gallery utama (touch — sudah di-handle di initDrag)
   Ini fallback jika initDrag tidak dipanggil
============================================================ */
(function () {
    const wrap = document.getElementById('galleryWrap');
    if (!wrap) return;
    let startX = 0;
    wrap.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, { passive: true });
    wrap.addEventListener('touchend',   (e) => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) changeSlide(diff > 0 ? 1 : -1);
    });
})();