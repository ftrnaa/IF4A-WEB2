/* ============================================================
   BatikAI — Admin JavaScript
   ============================================================ */

// ── Sidebar Toggle ────────────────────────────────────────
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const main    = document.getElementById('admin-main');
  sidebar.classList.toggle('open');
}

// ── Tab Switcher ──────────────────────────────────────────
function switchTab(btn, panelId) {
  const tabs   = btn.closest('.admin-tabs').querySelectorAll('.admin-tab-btn');
  const panels = btn.closest('.admin-card__body').querySelectorAll('.admin-tab-panel');

  tabs.forEach(t => t.classList.remove('active'));
  panels.forEach(p => p.classList.remove('active'));

  btn.classList.add('active');
  const panel = document.getElementById(panelId);
  if (panel) panel.classList.add('active');
}

// ── Send Certificate Modal ────────────────────────────────
function openSendModal(buyerName, productName) {
  document.getElementById('modal-buyer-name').textContent  = buyerName  || '—';
  document.getElementById('modal-product-name').textContent = productName || '—';
  document.getElementById('cert-email').value = '';
  document.getElementById('cert-msg').value   = `Halo ${buyerName},\n\nTerima kasih telah membeli motif "${productName}" dari BatikAI. Terlampir sertifikat keaslian dan file lisensi komersial.\n\nSalam,\nTim BatikAI`;

  const overlay = document.getElementById('send-cert-modal');
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeSendModal() {
  document.getElementById('send-cert-modal').classList.remove('open');
  document.body.style.overflow = '';
}

function submitSend() {
  const email = document.getElementById('cert-email').value;
  if (!email) {
    document.getElementById('cert-email').focus();
    document.getElementById('cert-email').style.borderColor = '#E74C3C';
    return;
  }
  // Simulate success
  closeSendModal();
  showToast('✓ Sertifikat & lisensi berhasil dikirim ke ' + email);
}

// ── Product Modal ─────────────────────────────────────────
function openProductModal(name) {
  const modal = document.getElementById('product-modal');
  const title = document.getElementById('product-modal-title');
  if (name) {
    title.textContent = 'Edit Motif — ' + name;
    document.getElementById('pf-name').value = name;
  } else {
    title.textContent = 'Tambah Motif Baru';
    document.getElementById('product-form').reset();
  }
  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeProductModal() {
  document.getElementById('product-modal').classList.remove('open');
  document.body.style.overflow = '';
}

// ── File Upload Preview ───────────────────────────────────
const certFileInput = document.getElementById('cert-file-input');
if (certFileInput) {
  certFileInput.addEventListener('change', function () {
    const list = document.getElementById('cert-file-list');
    list.innerHTML = '';
    Array.from(this.files).forEach(file => {
      const item = document.createElement('div');
      item.style.cssText = 'display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:var(--clr-brown-dark);background:var(--clr-cream-dark);border-radius:6px;padding:.3rem .7rem';
      item.innerHTML = `<span>📎</span><span>${file.name}</span><span style="color:var(--clr-text-muted)">(${(file.size/1024).toFixed(0)} KB)</span>`;
      list.appendChild(item);
    });
  });
}

// ── Close modals on overlay click ─────────────────────────
document.querySelectorAll('.admin-modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function (e) {
    if (e.target === this) {
      this.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
});

// ── Toast Notification ────────────────────────────────────
function showToast(msg) {
  let toast = document.getElementById('admin-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'admin-toast';
    toast.style.cssText = `
      position:fixed; bottom:2rem; right:2rem; z-index:9999;
      background:var(--clr-green); color:var(--clr-cream-light);
      padding:.85rem 1.4rem; border-radius:10px;
      font-size:.88rem; font-weight:500;
      box-shadow:0 8px 32px rgba(0,0,0,.18);
      transform:translateY(20px); opacity:0;
      transition:all .3s cubic-bezier(.25,.8,.25,1);
    `;
    document.body.appendChild(toast);
  }
  toast.textContent = msg;
  requestAnimationFrame(() => {
    toast.style.transform = 'translateY(0)';
    toast.style.opacity   = '1';
  });
  setTimeout(() => {
    toast.style.transform = 'translateY(20px)';
    toast.style.opacity   = '0';
  }, 3500);
}

// ── Close on Escape ───────────────────────────────────────
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.admin-modal-overlay.open').forEach(m => {
      m.classList.remove('open');
      document.body.style.overflow = '';
    });
  }
});