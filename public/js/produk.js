/* ============================================================
   produk.js — Admin Motif & Produk
   ============================================================ */

// ── State ──────────────────────────────────────────────────
let categories    = window.BATIK_CATEGORIES || [];
let deleteType    = '';
let deleteTarget  = '';
let activeCategory = '';

// ══════════════════════════════════════════════════════════
// CARD ENTRANCE ANIMATION
// ══════════════════════════════════════════════════════════
function initCardAnimations() {
    const cards = document.querySelectorAll('.motif-card.product-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const card  = entry.target;
                const index = parseInt(card.dataset.animIndex || '0', 10);
                const delay = Math.min(index * 60, 400);
                setTimeout(() => card.classList.add('show'), delay);
                observer.unobserve(card);
            }
        });
    }, { threshold: 0.06, rootMargin: '0px 0px -20px 0px' });

    cards.forEach((card) => observer.observe(card));
}

// ══════════════════════════════════════════════════════════
// SLIDESHOW  (Ken Burns, 5s interval)
// ══════════════════════════════════════════════════════════
const KB_CLASSES = ['kenburns-r', 'kenburns-l'];

function initSlideshows() {
    document.querySelectorAll('.motif-card.product-item').forEach((card, cardIndex) => {
        const wrap = card.querySelector('.card-image-wrap');
        if (!wrap) return;

        const imgs = Array.from(wrap.querySelectorAll('.slide-img'));
        if (imgs.length <= 1) return;

        let current = 0;

        function applyKenBurns(imgEl, idx) {
            imgEl.classList.remove(...KB_CLASSES);
            imgEl.classList.add(KB_CLASSES[idx % 2]);
        }

        function goTo() {
            const prev = current;
            current = (current + 1) % imgs.length;

            imgs[prev].classList.remove('active', ...KB_CLASSES);
            imgs[current].classList.add('active');
            applyKenBurns(imgs[current], current);
        }

        imgs[current].classList.add('active');
        applyKenBurns(imgs[current], current);

        const initialDelay = (cardIndex * 317) % 5000;
        let timer = null;

        function startTimer() {
            clearInterval(timer);
            timer = setInterval(goTo, 5000);
        }

        setTimeout(() => {
            startTimer();
            document.addEventListener('visibilitychange', () => {
                document.hidden ? clearInterval(timer) : startTimer();
            });
        }, initialDelay);
    });
}

// ══════════════════════════════════════════════════════════
// FILTER DROPDOWN
// ══════════════════════════════════════════════════════════
function initFilterDropdown() {
    const btn  = document.getElementById('produkFilterBtn');
    const menu = document.getElementById('produkFilterMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = menu.classList.contains('open');
        menu.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
    });

    document.addEventListener('click', () => {
        menu.classList.remove('open');
        btn.classList.remove('open');
    });

    menu.addEventListener('click', (e) => e.stopPropagation());

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            menu.classList.remove('open');
            btn.classList.remove('open');
        }
    });
}

function selectCategory(value, label, el) {
    // Update active state
    document.querySelectorAll('.produk-filter-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');

    // Update button label
    document.getElementById('produkFilterLabel').textContent = label;

    // Close menu
    document.getElementById('produkFilterMenu').classList.remove('open');
    document.getElementById('produkFilterBtn').classList.remove('open');

    activeCategory = value;
    filterProducts();
}

// ══════════════════════════════════════════════════════════
// SEARCH / FILTER
// ══════════════════════════════════════════════════════════
function filterProducts() {
    const search = (document.getElementById('filter-search')?.value || '').toLowerCase().trim();
    const items  = document.querySelectorAll('.product-item');
    let visible  = 0;

    items.forEach(item => {
        const matchSearch   = !search   || item.dataset.name.includes(search);
        const matchCategory = !activeCategory || item.dataset.cat === activeCategory;
        const show = matchSearch && matchCategory;

        item.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    const noResults = document.getElementById('no-results');
    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';

    // Update counter
    const counterEl = document.getElementById('produkCountVisible');
    if (counterEl) {
        counterEl.style.transition = 'opacity 0.2s';
        counterEl.style.opacity    = '0';
        setTimeout(() => {
            counterEl.textContent  = visible;
            counterEl.style.opacity = '1';
        }, 150);
    }

    const clearBtn = document.getElementById('search-clear-btn');
    if (clearBtn) clearBtn.classList.toggle('visible', search.length > 0);
}

function clearSearch() {
    const input = document.getElementById('filter-search');
    if (input) { input.value = ''; filterProducts(); }
}

// ══════════════════════════════════════════════════════════
// IMAGE LIGHTBOX
// ══════════════════════════════════════════════════════════
let lightboxIndex    = 0; // which product
let lightboxSlide    = 0; // which slide in that product
let lightboxData     = null;

function openImageModal(productIndex) {
    lightboxData  = window.PRODUK_DATA?.[productIndex];
    if (!lightboxData) return;

    lightboxIndex = productIndex;
    lightboxSlide = 0;

    // Inject slides
    const slideshow = document.getElementById('imageModalSlideshow');
    slideshow.innerHTML = '';
    lightboxData.slides.forEach((src, i) => {
        const img = document.createElement('img');
        img.src   = src;
        img.className = 'image-modal-slide' + (i === 0 ? ' active' : '');
        img.onerror = function () { this.src = 'https://via.placeholder.com/680x420?text=BatikAI'; };
        slideshow.appendChild(img);
    });

    // Info
    document.getElementById('imageModalName').textContent = lightboxData.name;
    document.getElementById('imageModalCat').textContent  = lightboxData.kategori;

    // Dots
    renderLightboxDots();

    // Nav buttons
    updateLightboxNav();

    openModal('image-modal');
}

function renderLightboxDots() {
    const dotsWrap = document.getElementById('imageModalDots');
    dotsWrap.innerHTML = '';
    if (!lightboxData) return;

    lightboxData.slides.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = 'image-modal-dot' + (i === lightboxSlide ? ' active' : '');
        dot.onclick   = () => goToLightboxSlide(i);
        dotsWrap.appendChild(dot);
    });
}

function goToLightboxSlide(index) {
    const slides = document.querySelectorAll('.image-modal-slide');
    if (!slides.length) return;

    slides[lightboxSlide].classList.remove('active');
    lightboxSlide = index;
    slides[lightboxSlide].classList.add('active');

    // Update dots
    document.querySelectorAll('.image-modal-dot').forEach((d, i) => {
        d.classList.toggle('active', i === lightboxSlide);
    });

    updateLightboxNav();
}

function imageModalNav(dir) {
    if (!lightboxData) return;
    const total = lightboxData.slides.length;
    let next = lightboxSlide + dir;
    if (next < 0) next = total - 1;
    if (next >= total) next = 0;
    goToLightboxSlide(next);
}

function updateLightboxNav() {
    const total = lightboxData?.slides?.length || 0;
    const prev  = document.getElementById('imageModalPrev');
    const next  = document.getElementById('imageModalNext');
    const nav   = document.querySelector('.image-modal-nav');

    if (prev) prev.disabled = total <= 1;
    if (next) next.disabled = total <= 1;
    if (nav)  nav.style.display = total <= 1 ? 'none' : 'flex';
}

function closeImageModal() {
    closeModal('image-modal');
    lightboxData = null;
}

// ══════════════════════════════════════════════════════════
// PRODUCT MODAL — edit description
// ══════════════════════════════════════════════════════════
function openProductModal(name, cat, price, description, cardIndex) {
    document.getElementById('product-modal-title').textContent = 'Edit Motif — ' + name;
    document.getElementById('pf-name').value   = name;
    document.getElementById('pf-cat').value    = cat;
    document.getElementById('pf-price').value  = 'Rp ' + Number(price).toLocaleString('id-ID');
    document.getElementById('pf-desc').value   = description;
    document.getElementById('pf-card-index').value = cardIndex;
    openModal('product-modal');
}

function closeProductModal() { closeModal('product-modal'); }

function saveProduct() {
    const desc  = document.getElementById('pf-desc').value.trim();
    const index = document.getElementById('pf-card-index').value;
    const name  = document.getElementById('pf-name').value;

    if (!desc) {
        document.getElementById('pf-desc').style.borderColor = '#E74C3C';
        document.getElementById('pf-desc').focus();
        return;
    }
    document.getElementById('pf-desc').style.borderColor = '';

    const descEl = document.getElementById('desc-display-' + index);
    if (descEl) {
        descEl.textContent = desc;
        descEl.style.transition = 'color .3s';
        descEl.style.color = 'var(--clr-gold, #c8a96e)';
        setTimeout(() => { descEl.style.color = ''; }, 800);
    }

    closeProductModal();
    showToast('✓ Deskripsi "' + name + '" berhasil diperbarui');
}

// ══════════════════════════════════════════════════════════
// CATEGORY MODAL
// ══════════════════════════════════════════════════════════
function openCatModal(id, name, desc) {
    document.getElementById('cf-name').value = name || '';
    document.getElementById('cf-desc').value = desc || '';

    const form = document.getElementById('cat-form');
    if (form) form.dataset.editId = id || '';

    openModal('cat-modal');
}

function closeCatModal() { closeModal('cat-modal'); }

function saveCategory() {
    const name = document.getElementById('cf-name').value.trim();
    if (!name) {
        document.getElementById('cf-name').style.borderColor = '#E74C3C';
        document.getElementById('cf-name').focus();
        return;
    }
    document.getElementById('cf-name').style.borderColor = '';
    closeCatModal();
    showToast('✓ Kategori "' + name + '" berhasil disimpan');
}

// ══════════════════════════════════════════════════════════
// DELETE
// ══════════════════════════════════════════════════════════
function confirmDelete(type, name) {
    deleteType   = type;
    deleteTarget = name;
    const nameEl = document.getElementById('delete-target-name');
    if (nameEl) nameEl.textContent = name;
    openModal('delete-modal');
}

function closeDeleteModal() { closeModal('delete-modal'); }

function doDelete() {
    if (deleteType === 'kategori') {
        const idx = categories.findIndex(c => c.name === deleteTarget);
        if (idx > -1) categories.splice(idx, 1);
        document.querySelectorAll('#cat-table-body tr').forEach(row => {
            if (row.querySelector('.cat-name')?.textContent === deleteTarget) row.remove();
        });
    } else {
        document.querySelectorAll('.product-item').forEach(item => {
            if (item.dataset.name === deleteTarget.toLowerCase()) {
                item.style.transition = 'opacity .3s, transform .3s';
                item.style.opacity    = '0';
                item.style.transform  = 'scale(.95)';
                setTimeout(() => item.remove(), 310);
            }
        });
    }
    closeDeleteModal();
    showToast('🗑 "' + deleteTarget + '" berhasil dihapus');
}

// ══════════════════════════════════════════════════════════
// MODAL HELPERS
// ══════════════════════════════════════════════════════════
function openModal(id) {
    document.getElementById(id)?.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id)?.classList.remove('open');
    document.body.style.overflow = '';
}

// ══════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {

    initCardAnimations();
    initSlideshows();
    initFilterDropdown();

    // Close modals on overlay click
    ['product-modal', 'cat-modal', 'delete-modal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', e => {
            if (e.target.id === id) closeModal(id);
        });
    });

    // ESC key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['product-modal', 'cat-modal', 'delete-modal', 'image-modal'].forEach(id => closeModal(id));
        }
    });

    // Keyboard nav for lightbox
    document.addEventListener('keydown', e => {
        if (!document.getElementById('image-modal')?.classList.contains('open')) return;
        if (e.key === 'ArrowLeft')  imageModalNav(-1);
        if (e.key === 'ArrowRight') imageModalNav(1);
    });
});