/* ============================================================
   BatikAI — App JavaScript
   ============================================================ */

// ── Mobile Menu Toggle ────────────────────────────────────
function toggleMobileMenu() {
  const menu = document.getElementById('mobile-menu');
  const isOpen = menu.classList.toggle('is-open');
  menu.setAttribute('aria-hidden', String(!isOpen));
}

// Close menu on outside click
document.addEventListener('click', (e) => {
  const menu = document.getElementById('mobile-menu');
  const toggle = document.querySelector('.navbar-toggle');
  if (menu && !menu.contains(e.target) && !toggle.contains(e.target)) {
    menu.classList.remove('is-open');
    menu.setAttribute('aria-hidden', 'true');
  }
});

// ── Scroll-triggered fade-in ──────────────────────────────
const observerOptions = {
  threshold: 0.15,
  rootMargin: '0px 0px -40px 0px',
};
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in-view');
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);
document.querySelectorAll('.product-card, .how__step, .about__feature').forEach((el) => {
  observer.observe(el);
});

// ── Navbar scroll shrink ──────────────────────────────────
//const navbar = document.querySelector('.navbar');
//window.addEventListener('scroll', () => {
//  navbar.classList.toggle('scrolled', window.scrollY > 60);
//}, { passive: true });

// ── Slideshow otomatis — 5 detik, delay random per card ──
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

    // Delay awal random 0–5 detik supaya tidak serentak
    const initialDelay = Math.random() * 5000;
    setTimeout(() => {
      setInterval(() => goTo(current + 1), 5000);
    }, initialDelay);
  });
});