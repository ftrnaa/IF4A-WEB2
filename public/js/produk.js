/* ============================================================
   BatikAI — Admin Produk & Kategori JavaScript
   ============================================================ */

// ── State (seeded from blade via window.BATIK_CATEGORIES) ──
let categories  = window.BATIK_CATEGORIES || [];
let deleteType  = '';
let deleteTarget = '';

// ══════════════════════════════════════════════════════════
// FILTER PRODUK
// ══════════════════════════════════════════════════════════
function filterProducts() {
  const cat    = document.getElementById('filter-cat').value.toLowerCase();
  const status = document.getElementById('filter-status').value.toLowerCase();
  const search = document.getElementById('filter-search').value.toLowerCase();
  const items  = document.querySelectorAll('.product-item');
  let visible  = 0;

  items.forEach(item => {
    const matchCat    = !cat    || item.dataset.cat.toLowerCase() === cat;
    const matchStatus = !status || item.dataset.status === status;
    const matchSearch = !search || item.dataset.name.includes(search);
    const show = matchCat && matchStatus && matchSearch;
    item.classList.toggle('hidden', !show);
    if (show) visible++;
  });

  const noResults = document.getElementById('no-results');
  if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
}

// ══════════════════════════════════════════════════════════
// PRODUCT MODAL
// ══════════════════════════════════════════════════════════
function openProductModal(name, cat) {
  document.getElementById('product-modal-title').textContent =
    name ? 'Edit Motif — ' + name : 'Tambah Motif Baru';

  document.getElementById('pf-name').value   = name || '';
  document.getElementById('pf-desc').value   = '';
  document.getElementById('pf-price').value  = '';
  document.getElementById('pf-origin').value = '';

  const preview = document.getElementById('pf-img-preview');
  preview.style.display = 'none';
  preview.src = '';
  document.getElementById('pf-upload-icon').textContent = '🖼️';

  syncCatOptions('pf-cat', cat);

  openModal('product-modal');
}

function closeProductModal() {
  closeModal('product-modal');
}

function saveProduct() {
  const name = document.getElementById('pf-name').value.trim();
  if (!name) {
    document.getElementById('pf-name').focus();
    document.getElementById('pf-name').style.borderColor = '#E74C3C';
    return;
  }
  closeProductModal();
  showToast('✓ Motif "' + name + '" berhasil disimpan');
}

function previewMotifImage(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview = document.getElementById('pf-img-preview');
    preview.src = e.target.result;
    preview.style.display = 'block';
    document.getElementById('pf-upload-icon').textContent = '✅';
  };
  reader.readAsDataURL(file);
}

// ══════════════════════════════════════════════════════════
// CATEGORY MODAL
// ══════════════════════════════════════════════════════════
function openCatModal(id, name, desc, color) {
  document.getElementById('cat-modal-title').textContent =
    id ? 'Edit Kategori — ' + name : 'Tambah Kategori Baru';

  document.getElementById('cf-name').value  = name  || '';
  document.getElementById('cf-desc').value  = desc  || '';
  document.getElementById('cf-color').value = color || '#7B5E3A';
  document.getElementById('cat-form').dataset.editId = id || '';

  // Sync swatch highlight
  document.querySelectorAll('.color-swatch').forEach(s => {
    s.classList.toggle('selected', s.dataset.color === (color || ''));
  });

  updateCatPreview();
  openModal('cat-modal');
}

function closeCatModal() {
  closeModal('cat-modal');
}

function updateCatPreview() {
  const name  = document.getElementById('cf-name').value  || 'Nama Kategori';
  const color = document.getElementById('cf-color').value || '#7B5E3A';

  document.getElementById('cat-preview-dot').style.background = color;
  document.getElementById('cat-preview-name').textContent     = name;
  document.getElementById('cat-preview-badge').textContent    = name.toUpperCase();
  document.getElementById('cat-preview-badge').style.color    = color;
}

function pickSwatch(hex) {
  document.getElementById('cf-color').value = hex;

  document.querySelectorAll('.color-swatch').forEach(s => {
    s.classList.toggle('selected', s.dataset.color === hex);
  });

  updateCatPreview();
}

function saveCategory() {
  const name   = document.getElementById('cf-name').value.trim();
  const desc   = document.getElementById('cf-desc').value.trim();
  const color  = document.getElementById('cf-color').value;
  const editId = document.getElementById('cat-form').dataset.editId;

  if (!name) {
    document.getElementById('cf-name').focus();
    document.getElementById('cf-name').style.borderColor = '#E74C3C';
    return;
  }

  if (editId) {
    _updateCategory(editId, name, desc, color);
  } else {
    _addCategory(name, desc, color);
  }

  closeCatModal();
}

function _updateCategory(editId, name, desc, color) {
  const idx = categories.findIndex(c => c.id == editId);
  if (idx > -1) {
    categories[idx] = { ...categories[idx], name, desc, color };
  }

  // Update table row
  const tbody = document.getElementById('cat-table-body');
  const row   = tbody.querySelector(`tr[data-cat-id="${editId}"]`);
  if (row) {
    row.querySelector('.cat-color-swatch').style.background = color;
    row.querySelector('.cat-name').textContent              = name;
    row.cells[2].textContent                                = desc || '—';
    // Rebuild action buttons with updated data
    row.cells[5].querySelector('.admin-actions-group').innerHTML = _catActionBtns(editId, name, desc, color);
  }

  syncCatOptions('pf-cat');
  syncCatOptions('filter-cat');
  showToast('✓ Kategori "' + name + '" berhasil diperbarui');
}

function _addCategory(name, desc, color) {
  const newId = categories.length
    ? Math.max(...categories.map(c => c.id)) + 1
    : 1;
  categories.push({ id: newId, name, desc, color, count: 0 });

  // Append row to table
  const tbody = document.getElementById('cat-table-body');
  const row   = document.createElement('tr');
  row.dataset.catId = newId;
  row.innerHTML = `
    <td><div class="cat-color-swatch" style="background:${color}"></div></td>
    <td><span class="cat-name">${name}</span></td>
    <td class="cat-desc" style="font-size:.85rem;color:var(--clr-text-muted)">${desc || '—'}</td>
    <td><span class="cat-count">0 motif</span></td>
    <td><span class="status-badge status-badge--paid">Aktif</span></td>
    <td><div class="admin-actions-group">${_catActionBtns(newId, name, desc, color)}</div></td>`;
  tbody.appendChild(row);

  syncCatOptions('pf-cat');
  syncCatOptions('filter-cat');
  showToast('✓ Kategori "' + name + '" berhasil ditambahkan');
}

function _catActionBtns(id, name, desc, color) {
  return `
    <button class="admin-action-btn admin-action-btn--outline"
            onclick="openCatModal(${id},'${name}','${desc}','${color}')">✏️ Edit</button>
    <button class="admin-action-btn admin-action-btn--danger"
            onclick="confirmDelete('kategori','${name}')">🗑</button>`;
}

// Sync all <option> inside a <select> from current categories array
function syncCatOptions(selectId, selected) {
  const sel = document.getElementById(selectId);
  if (!sel) return;

  const hasPlaceholder = sel.options[0]?.value === '';
  sel.innerHTML = '';

  if (hasPlaceholder) {
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'Semua Kategori';
    sel.appendChild(opt);
  }

  categories.forEach(c => {
    const opt = document.createElement('option');
    opt.value       = c.name;
    opt.textContent = c.name;
    if (selected && c.name === selected) opt.selected = true;
    sel.appendChild(opt);
  });
}

// ══════════════════════════════════════════════════════════
// DELETE CONFIRM
// ══════════════════════════════════════════════════════════
function confirmDelete(type, name) {
  deleteType   = type;
  deleteTarget = name;
  document.getElementById('delete-target-name').textContent = name;
  openModal('delete-modal');
}

function closeDeleteModal() {
  closeModal('delete-modal');
}

function doDelete() {
  if (deleteType === 'kategori') {
    const idx = categories.findIndex(c => c.name === deleteTarget);
    if (idx > -1) categories.splice(idx, 1);

    // Remove table row
    document.querySelectorAll('#cat-table-body tr').forEach(row => {
      if (row.querySelector('.cat-name')?.textContent === deleteTarget) row.remove();
    });

    syncCatOptions('pf-cat');
    syncCatOptions('filter-cat');
  } else {
    // Remove product card
    document.querySelectorAll('.product-item').forEach(item => {
      if (item.dataset.name === deleteTarget.toLowerCase()) item.remove();
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

// ── Close on overlay click ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  ['product-modal', 'cat-modal', 'delete-modal'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', e => {
      if (e.target.id === id) closeModal(id);
    });
  });
});

// ── ESC key ────────────────────────────────────────────────
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    ['product-modal', 'cat-modal', 'delete-modal'].forEach(id => closeModal(id));
  }
});