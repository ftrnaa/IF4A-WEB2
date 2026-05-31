/* ============================================================
   BatikAI — App JavaScript
   ============================================================ */
console.log('APP JS LOADED');
// ── Mobile Menu Toggle ────────────────────────────────────
window.toggleMobileMenu = function() {
    const menu = document.getElementById('mobile-menu');
    const isOpen = menu.classList.toggle('is-open');
    menu.setAttribute('aria-hidden', String(!isOpen));
}

// ── User Dropdown ─────────────────────────────────────────
window.toggleDropdown = function(event) {
    console.log("DROPDOWN CLICKED");

    event.stopPropagation();

    const wrapper = document.getElementById('userDropdown');
    const menu = document.getElementById('dropdownMenu');

    console.log(wrapper);
    console.log(menu);

    const isOpen = menu.classList.toggle('show');

    console.log("isOpen:", isOpen);

    wrapper.classList.toggle('is-open', isOpen);
}

// Tutup dropdown klik di luar
document.addEventListener('click', function (e) {
    const wrapper = document.getElementById('userDropdown');
    const menu    = document.getElementById('dropdownMenu');
    if (!wrapper || !menu) return;
    if (!wrapper.contains(e.target)) {
        menu.classList.remove('show');
        wrapper.classList.remove('is-open');
        wrapper.querySelector('.user-btn')?.setAttribute('aria-expanded', 'false');
    }
});

// Tutup dropdown tekan Escape
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const wrapper = document.getElementById('userDropdown');
    const menu    = document.getElementById('dropdownMenu');
    if (!menu) return;
    menu.classList.remove('show');
    wrapper?.classList.remove('is-open');
    wrapper?.querySelector('.user-btn')?.setAttribute('aria-expanded', 'false');
});

// ── Mobile menu tutup klik di luar ───────────────────────
document.addEventListener('click', function (e) {
    const menu   = document.getElementById('mobile-menu');
    const toggle = document.querySelector('.navbar-toggle');
    if (!menu || !toggle) return;
    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
        menu.classList.remove('is-open');
        menu.setAttribute('aria-hidden', 'true');
    }
});

// ── Scroll-triggered fade-in ──────────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.product-card, .how__step, .about__feature').forEach((el) => {
    observer.observe(el);
});

// ── Slideshow otomatis ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.motif-card').forEach((card) => {
        const wrap = card.querySelector('.card-image-wrap');
        if (!wrap) return;
        const imgs = wrap.querySelectorAll('.slide-img');
        const dots = wrap.querySelectorAll('.dot');
        if (imgs.length <= 1) return;
        let current = 0;
        function goTo(index) {
            imgs[current].classList.remove('active');
            dots[current]?.classList.remove('active');
            current = index % imgs.length;
            imgs[current].classList.add('active');
            dots[current]?.classList.add('active');
        }
        setTimeout(() => {
            setInterval(() => goTo(current + 1), 5000);
        }, Math.random() * 5000);
    });
});
window.toggleDropdown = function(event) {
    alert('Dropdown diklik');
};
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('userBtn');
    const wrapper = document.getElementById('userDropdown');
    const menu = document.getElementById('dropdownMenu');

    if (btn && wrapper && menu) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            const isOpen = menu.classList.toggle('show');

            wrapper.classList.toggle('is-open', isOpen);
            btn.setAttribute('aria-expanded', isOpen);
        });
    }

});