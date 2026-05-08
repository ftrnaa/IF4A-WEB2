/**
 * detail-motif.js — Koleksi Batik Nusantara
 * Path: public/js/detail-motif.js
 */

let current = 0;
let total   = 0;

/* ------------------------------------------------
   SLIDER
------------------------------------------------ */
function initSlider(totalSlides) {
    total = totalSlides;

    // Klik pada gambar slide → buka lightbox
    document.querySelectorAll('.slide img').forEach((img, i) => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => openLightbox(i));
    });

    buildLightbox();
}

function goToSlide(n) {
    current = ((n % total) + total) % total;

    const slidesEl = document.getElementById('slides');
    if (slidesEl) slidesEl.style.transform = `translateX(-${current * 100}%)`;

    document.querySelectorAll('#dots .dot').forEach((d, i) =>
        d.classList.toggle('active', i === current));

    document.querySelectorAll('#thumbStrip .thumb-item').forEach((t, i) =>
        t.classList.toggle('active', i === current));

    const counter = document.getElementById('slideCounter');
    if (counter) counter.textContent = `${current + 1} / ${total}`;
}

function changeSlide(dir) {
    goToSlide(current + dir);
}

/* ------------------------------------------------
   LIGHTBOX
------------------------------------------------ */
let lbIndex  = 0;
let lbImages = [];

function buildLightbox() {
    // Kumpulkan semua src + label dari setiap slide
    document.querySelectorAll('.slide').forEach((slide) => {
        const img   = slide.querySelector('img');
        const label = slide.querySelector('.slide-label');
        lbImages.push({
            src:   img ? img.src : '',
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
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
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
    lb.querySelector('.lb-close').addEventListener('click', closeLightbox);
    lb.querySelector('.lb-prev').addEventListener('click', () => lbGo(lbIndex - 1));
    lb.querySelector('.lb-next').addEventListener('click', () => lbGo(lbIndex + 1));

    // Swipe di dalam lightbox
    let lbStartX = 0;
    const box = lb.querySelector('.lb-img-wrap');
    box.addEventListener('touchstart', (e) => { lbStartX = e.touches[0].clientX; }, { passive: true });
    box.addEventListener('touchend',   (e) => {
        const diff = lbStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) lbGo(lbIndex + (diff > 0 ? 1 : -1));
    });
}

function openLightbox(index) {
    lbGo(index);
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}

function lbGo(n) {
    lbIndex = ((n % lbImages.length) + lbImages.length) % lbImages.length;

    const img     = document.getElementById('lbImg');
    const label   = document.getElementById('lbLabel');
    const counter = document.getElementById('lbCounter');

    // Fade swap
    img.style.opacity = '0';
    img.style.transform = 'scale(0.97)';
    setTimeout(() => {
        img.src = lbImages[lbIndex].src;
        img.style.opacity = '1';
        img.style.transform = 'scale(1)';
    }, 180);

    label.textContent   = lbImages[lbIndex].label;
    counter.textContent = `${lbIndex + 1} / ${lbImages.length}`;

    const lb = document.getElementById('lightbox');
    lb.querySelector('.lb-prev').style.display = lbImages.length > 1 ? '' : 'none';
    lb.querySelector('.lb-next').style.display = lbImages.length > 1 ? '' : 'none';
}

/* ------------------------------------------------
   KEYBOARD
------------------------------------------------ */
document.addEventListener('keydown', function (e) {
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

/* ------------------------------------------------
   SWIPE pada gallery utama
------------------------------------------------ */
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