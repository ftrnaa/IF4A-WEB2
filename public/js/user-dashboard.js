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