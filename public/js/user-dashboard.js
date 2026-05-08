/* ============================================================
   BatikAI — User Dashboard JavaScript
   ============================================================ */

// ── Sidebar Toggle (mobile) ───────────────────────────────
function toggleUserSidebar() {
  document.getElementById('user-sidebar').classList.toggle('open');
}

document.addEventListener('click', (e) => {
  const sidebar = document.getElementById('user-sidebar');
  const toggle  = document.querySelector('.user-topbar__toggle');
  if (sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
    sidebar.classList.remove('open');
  }
});

// ── Toast ─────────────────────────────────────────────────
function userToast(msg) {
  const toast = document.getElementById('user-toast');
  if (!toast) return;
  toast.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

// ── Certificate Modal ─────────────────────────────────────
function viewCert(name, date) {
  document.getElementById('cert-modal-name').textContent = name || '—';
  document.getElementById('cert-modal-date').textContent = date || '—';
  document.getElementById('cert-modal-title').textContent = name || 'Sertifikat';

  // Generate pseudo cert number from name
  const hash = name ? name.split('').reduce((a, c) => a + c.charCodeAt(0), 0) : 0;
  document.getElementById('cert-modal-no').textContent = 'CERT-' + (2025 + (hash % 2)) + '-' + String(hash % 999).padStart(3, '0');

  const overlay = document.getElementById('cert-modal');
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeCertModal() {
  document.getElementById('cert-modal').classList.remove('open');
  document.body.style.overflow = '';
}

// Close on overlay click
document.addEventListener('click', (e) => {
  const overlay = document.getElementById('cert-modal');
  if (overlay && e.target === overlay) closeCertModal();
});

// ── Password toggle ───────────────────────────────────────
function togglePassField(id, btn) {
  const input = document.getElementById(id);
  if (!input) return;
  const isPass = input.type === 'password';
  input.type = isPass ? 'text' : 'password';
  btn.textContent = isPass ? '🙈' : '👁';
}

// ── Avatar Preview ────────────────────────────────────────
function previewAvatar(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    const img = document.getElementById('profile-avatar-img');
    if (img) img.src = e.target.result;
    // Also update sidebar avatar
    const sidebarAvatar = document.querySelector('.user-sidebar__avatar');
    if (sidebarAvatar) sidebarAvatar.src = e.target.result;
    userToast('✓ Foto profil diperbarui. Simpan untuk menyimpan perubahan.');
  };
  reader.readAsDataURL(file);
}

// ── Profile Save ──────────────────────────────────────────
function saveProfile(e) {
  e.preventDefault();
  userToast('✓ Profil berhasil disimpan');
}

// ── Password Save ─────────────────────────────────────────
function savePassword(e) {
  e.preventDefault();
  const newPass     = document.getElementById('pass_new')?.value || '';
  const confirmPass = document.getElementById('pass_confirm')?.value || '';
  if (newPass.length < 8) {
    userToast('⚠ Sandi baru minimal 8 karakter');
    return;
  }
  if (newPass !== confirmPass) {
    userToast('⚠ Konfirmasi sandi tidak cocok');
    return;
  }
  userToast('✓ Kata sandi berhasil diubah');
  e.target.reset();
}

// ── ESC to close modal ────────────────────────────────────
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeCertModal();
});
/* ============================================================
   BatikAI — Product Link JS (tambahkan ke user-dashboard.js)
   ============================================================ */

// ── Product Link: Save ────────────────────────────────────
function saveProductLink(idx, motifName) {
    const input = document.getElementById('link-input-' + idx);
    if (!input) return;

    const url = input.value.trim();
    if (!url) {
        userToast('⚠ Masukkan link produk terlebih dahulu');
        return;
    }

    // Basic URL validation
    try {
        new URL(url);
    } catch {
        userToast('⚠ Link tidak valid. Pastikan diawali https://');
        return;
    }

    // Show loading state on preview card
    const inputWrap  = document.getElementById('link-input-wrap-' + idx);
    const previewWrap = document.getElementById('link-preview-' + idx);
    const previewCard = document.getElementById('preview-card-' + idx);

    // Update data-url attribute
    previewCard.dataset.url = url;

    // Switch visibility
    inputWrap.style.display  = 'none';
    previewWrap.style.display = '';

    // Reset to loading state
    document.getElementById('ppc-loading-' + idx).style.display = 'flex';
    const content = document.getElementById('ppc-content-' + idx);
    if (content) content.style.display = 'none';

    // Load preview
    loadProductPreview(idx, url);

    userToast('✓ Link produk "' + motifName + '" berhasil disimpan');
}

// ── Product Link: Edit (switch back to input) ─────────────
function editProductLink(idx) {
    const inputWrap   = document.getElementById('link-input-wrap-' + idx);
    const previewWrap = document.getElementById('link-preview-' + idx);
    const previewCard = document.getElementById('preview-card-' + idx);

    // Pre-fill input with current URL
    const input = document.getElementById('link-input-' + idx);
    if (input && previewCard) input.value = previewCard.dataset.url || '';

    inputWrap.style.display   = '';
    previewWrap.style.display = 'none';
    input && input.focus();
}

// ── Product Preview Loader ────────────────────────────────
function loadProductPreview(idx, rawUrl) {
    const loadingEl = document.getElementById('ppc-loading-' + idx);
    const contentEl = document.getElementById('ppc-content-' + idx);

    if (!loadingEl || !contentEl) return;

    loadingEl.style.display = 'flex';
    contentEl.style.display = 'none';

    // Extract domain info locally (no external fetch needed for basic preview)
    let parsedUrl;
    try {
        parsedUrl = new URL(rawUrl);
    } catch {
        showPreviewError(idx, 'URL tidak dapat diproses.');
        return;
    }

    const domain   = parsedUrl.hostname.replace('www.', '');
    const faviconUrl = 'https://www.google.com/s2/favicons?domain=' + domain + '&sz=32';

    // Try to fetch via allorigins proxy to get page title
    const proxyUrl = 'https://api.allorigins.win/get?url=' + encodeURIComponent(rawUrl);

    const timeoutMs = 5000;
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), timeoutMs);

    fetch(proxyUrl, { signal: controller.signal })
        .then(r => r.json())
        .then(data => {
            clearTimeout(timer);
            let title = '';
            if (data && data.contents) {
                // Extract <title> from HTML
                const match = data.contents.match(/<title[^>]*>([^<]+)<\/title>/i);
                title = match ? match[1].trim() : '';
                // Decode HTML entities
                title = decodeHTMLEntities(title);
            }
            if (!title) title = domain;
            renderPreview(idx, rawUrl, domain, faviconUrl, title);
        })
        .catch(() => {
            clearTimeout(timer);
            // Fallback: show domain name as title
            renderPreview(idx, rawUrl, domain, faviconUrl, domain);
        });
}

function renderPreview(idx, url, domain, faviconUrl, title) {
    const loadingEl = document.getElementById('ppc-loading-' + idx);
    const contentEl = document.getElementById('ppc-content-' + idx);

    if (!loadingEl || !contentEl) return;

    // Populate fields
    const faviconImg = document.getElementById('ppc-favicon-' + idx);
    const domainEl   = document.getElementById('ppc-domain-' + idx);
    const titleEl    = document.getElementById('ppc-title-' + idx);
    const urlEl      = document.getElementById('ppc-url-' + idx);
    const openBtn    = document.getElementById('ppc-open-' + idx);

    if (faviconImg) { faviconImg.src = faviconUrl; faviconImg.alt = domain; }
    if (domainEl)   domainEl.textContent = domain;
    if (titleEl)    titleEl.textContent  = title;
    if (urlEl)      urlEl.textContent    = url.length > 60 ? url.substring(0, 57) + '…' : url;
    if (openBtn)    openBtn.href         = url;

    loadingEl.style.display = 'none';
    contentEl.style.display = '';
}

function showPreviewError(idx, message) {
    const loadingEl  = document.getElementById('ppc-loading-' + idx);
    const contentEl  = document.getElementById('ppc-content-' + idx);
    const previewCard = document.getElementById('preview-card-' + idx);

    if (loadingEl) loadingEl.style.display = 'none';

    // Inject error state
    const errDiv = document.createElement('div');
    errDiv.className = 'ppc__error';
    errDiv.innerHTML = '⚠ ' + message + ' <button class="cert-btn cert-btn--view" onclick="editProductLink(' + idx + ')" style="margin-left:.5rem">Ganti Link</button>';

    if (previewCard) {
        // Remove previous error if any
        previewCard.querySelectorAll('.ppc__error').forEach(el => el.remove());
        previewCard.appendChild(errDiv);
    }
}

// ── Utility: decode HTML entities ────────────────────────
function decodeHTMLEntities(str) {
    const txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
}