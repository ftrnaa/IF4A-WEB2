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

// ── Period Selector ───────────────────────────────────────
function setPeriod(btn, period) {
  const selector = btn.closest('.period-selector');
  selector.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  // In production: reload chart data via fetch/AJAX
  const labels = { '7h':'7 Hari','30h':'30 Hari','3b':'3 Bulan','1t':'1 Tahun' };
  showToast('Periode diubah ke: ' + labels[period]);
}

// ── Export Report ─────────────────────────────────────────
function exportReport(type) {
  const overlay  = document.getElementById('export-overlay');
  const title    = document.getElementById('export-overlay-title');
  const msg      = document.getElementById('export-overlay-msg');

  const config = {
    pdf:   { title: 'Membuat PDF...', msg: 'Menyusun data laporan ke format PDF',       ext: 'pdf',  mime: 'application/pdf' },
    excel: { title: 'Membuat Excel...', msg: 'Menyusun data laporan ke format Excel',   ext: 'xlsx', mime: 'application/vnd.ms-excel' },
    csv:   { title: 'Membuat CSV...',   msg: 'Mengekspor data ke format CSV',           ext: 'csv',  mime: 'text/csv' },
  };

  const c = config[type];
  title.textContent = c.title;
  msg.textContent   = c.msg;
  overlay.classList.add('show');

  // Simulate async export (replace with real endpoint in production)
  setTimeout(() => {
    overlay.classList.remove('show');

    if (type === 'csv') {
      // Generate dummy CSV and trigger download
      const csv = [
        'ID,Pembeli,Produk,Harga,Tanggal,Metode,Status',
        'TRX-001,Rina Susanti,Sido Mukti,120000,13 Apr 2026,Transfer Bank,Lunas',
        'TRX-002,Budi Hartono,Kawung,110000,13 Apr 2026,QRIS,Menunggu',
        'TRX-003,Dewi Lestari,Mega Mendung,135000,12 Apr 2026,GoPay,Lunas',
      ].join('\n');
      downloadBlob(csv, 'laporan-batikai.csv', 'text/csv;charset=utf-8;');
    } else {
      // For PDF/Excel: in production, call server endpoint like /admin/laporan/export?type=pdf
      // window.location.href = `/admin/laporan/export?type=${type}`;
      showToast(`✓ Laporan ${type.toUpperCase()} siap diunduh`);
    }
  }, 2200);
}

function downloadBlob(content, filename, mime) {
  const blob = new Blob([content], { type: mime });
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a');
  a.href     = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  showToast('✓ File CSV berhasil diunduh');
}

function openDetailModal(tx) {
  document.getElementById('modal-trx-id').textContent        = tx.id + ' · ' + tx.date;
  document.getElementById('modal-buyer-avatar').src          = `https://picsum.photos/seed/${tx.img}/80/80`;
  document.getElementById('modal-detail-buyer-name').textContent  = tx.name;
  document.getElementById('modal-detail-buyer-email').textContent = tx.email;
  document.getElementById('modal-motif-img').src             = `https://picsum.photos/seed/${tx.motif}/120/120`;
  document.getElementById('modal-detail-product').textContent = tx.product;
  document.getElementById('modal-detail-cat').textContent     = tx.cat + ' · Lisensi Komersial';
  document.getElementById('modal-detail-amount').textContent  = 'Rp ' + tx.amount;
  document.getElementById('modal-detail-date').textContent    = tx.date;
  document.getElementById('modal-detail-expiry').textContent  = tx.status === 'paid' ? tx.expiry : '—';
  document.getElementById('modal-detail-method').textContent  = 'Transfer Bank';
  document.getElementById('modal-detail-ref').textContent     = tx.status === 'paid' ? 'BNI-' + Math.floor(Math.random()*900000000+100000000) : '—';

  // Badges
  const badgeMap = {
    paid:    '<span class="status-badge status-badge--paid">Lunas</span>',
    pending: '<span class="status-badge status-badge--pending">Menunggu</span>',
    failed:  '<span class="status-badge status-badge--failed">Gagal</span>',
  };
  let badges = badgeMap[tx.status] || '';
  if (tx.status === 'paid') {
    badges += ' <span class="status-badge status-badge--paid">Lisensi Aktif</span>';
    badges += tx.cert
      ? ' <span class="status-badge status-badge--sent">Sertifikat Terkirim</span>'
      : ' <span style="font-size:.75rem;color:var(--clr-text-muted)">Sertifikat Belum Dikirim</span>';
  }
  document.getElementById('modal-detail-badges').innerHTML = badges;

  // Timeline
  const timelines = {
    paid: [
      ['Transaksi dibuat', tx.date + ', 09:14'],
      ['Pembayaran dikonfirmasi', tx.date + ', 10:02'],
      ['Lisensi diaktifkan', tx.date + ', 10:02'],
      tx.cert ? ['Sertifikat dikirim ke ' + tx.email, tx.date + ', 10:15'] : null,
    ].filter(Boolean),
    pending: [
      ['Transaksi dibuat', tx.date + ', 09:14'],
      ['Menunggu konfirmasi pembayaran', '—'],
    ],
    failed: [
      ['Transaksi dibuat', tx.date + ', 09:14'],
      ['Pembayaran gagal / kadaluarsa', tx.date + ', 10:00'],
    ],
  };
  const items = timelines[tx.status] || [];
  document.getElementById('modal-detail-timeline').innerHTML = items.map((item, i) => `
    <div style="display:flex;gap:.75rem;align-items:flex-start">
      <div style="display:flex;flex-direction:column;align-items:center">
        <div style="width:10px;height:10px;border-radius:50%;background:${tx.status==='paid'?'#27AE60':'#C8A96E'};flex-shrink:0;margin-top:3px"></div>
        ${i < items.length-1 ? '<div style="width:1.5px;background:rgba(200,169,110,.25);flex:1;min-height:14px;margin-top:2px"></div>' : ''}
      </div>
      <div>
        <p style="font-size:.83rem;color:var(--clr-brown-dark)">${item[0]}</p>
        <p style="font-size:.7rem;color:var(--clr-text-muted);margin-top:1px">${item[1]}</p>
      </div>
    </div>
  `).join('');

  // Footer buttons
  document.getElementById('modal-btn-cancel').style.display  = tx.status === 'pending' ? '' : 'none';
  document.getElementById('modal-btn-cert').style.display    = tx.status === 'paid'    ? '' : 'none';
  document.getElementById('modal-btn-confirm').style.display = tx.status === 'pending' ? '' : 'none';

  document.getElementById('detail-trx-modal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
  document.getElementById('detail-trx-modal').classList.remove('open');
  document.body.style.overflow = '';
}

// ── Riwayat Sertifikat Modal ──────────────────────────────
function openRiwayatModal(c) {
  document.getElementById('riwayat-modal-subtitle').textContent = c.name + ' · ' + c.product;
  document.getElementById('riwayat-buyer-avatar').src  = `https://picsum.photos/seed/${c.img}/80/80`;
  document.getElementById('riwayat-buyer-name').textContent    = c.name;
  document.getElementById('riwayat-buyer-email').textContent   = c.email;
  document.getElementById('riwayat-product-name').textContent  = c.product;
  document.getElementById('riwayat-motif-img').src  = `https://picsum.photos/seed/${c.motif}/120/120`;

  // Status cards
  const certColor   = c.cert_sent    ? '#1A7A43' : '#B8610A';
  const licColor    = c.license_sent ? '#1A7A43' : '#B8610A';
  const certBg      = c.cert_sent    ? 'rgba(39,174,96,.08)'    : 'rgba(230,126,34,.08)';
  const licBg       = c.license_sent ? 'rgba(39,174,96,.08)'    : 'rgba(230,126,34,.08)';
  const certIcon    = c.cert_sent    ? '✓' : '✗';
  const licIcon     = c.license_sent ? '✓' : '✗';

  document.getElementById('riwayat-status-cards').innerHTML = `
    <div style="background:${certBg};border:1px solid ${certColor}22;border-radius:10px;padding:.85rem 1rem">
      <p style="font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:${certColor};margin-bottom:.35rem">Sertifikat</p>
      <p style="font-size:.9rem;font-weight:500;color:${certColor}">${certIcon} ${c.cert_sent ? 'Sudah Terkirim' : 'Belum Terkirim'}</p>
    </div>
    <div style="background:${licBg};border:1px solid ${licColor}22;border-radius:10px;padding:.85rem 1rem">
      <p style="font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:${licColor};margin-bottom:.35rem">File Lisensi</p>
      <p style="font-size:.9rem;font-weight:500;color:${licColor}">${licIcon} ${c.license_sent ? 'Sudah Terkirim' : 'Belum Terkirim'}</p>
    </div>
  `;

  // Bangun log entries
  const logs = [];

  if (c.cert_sent) {
    logs.push({ type: 'cert', icon: '📜', label: 'Sertifikat dikirim', to: c.email, time: c.date + ', 10:15', success: true });
  }
  if (c.license_sent) {
    logs.push({ type: 'license', icon: '📄', label: 'File lisensi dikirim', to: c.email, time: c.date + ', 10:16', success: true });
  }
  if (!c.cert_sent && !c.license_sent) {
    logs.push({ type: 'none', icon: '📭', label: 'Belum ada pengiriman', to: '', time: '—', success: false });
  }
  // Tambah satu contoh kirim ulang jika salah satu sudah terkirim
  if (c.cert_sent || c.license_sent) {
    logs.unshift({ type: 'resend', icon: '🔄', label: 'Kirim ulang sertifikat', to: c.email, time: c.date + ', 14:03', success: true });
  }

  document.getElementById('riwayat-log-list').innerHTML = logs.length
    ? logs.map(log => `
        <div style="display:flex;align-items:flex-start;gap:.85rem;padding:.75rem .9rem;border-radius:8px;background:${log.success ? 'rgba(200,169,110,.05)' : 'rgba(231,76,60,.04)'};border:0.5px solid ${log.success ? 'rgba(200,169,110,.18)' : 'rgba(231,76,60,.15)'}">
          <span style="font-size:1rem;flex-shrink:0;margin-top:1px">${log.icon}</span>
          <div style="flex:1;min-width:0">
            <p style="font-size:.83rem;font-weight:500;color:var(--clr-brown-dark)">${log.label}</p>
            ${log.to ? `<p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:1px">ke: ${log.to}</p>` : ''}
          </div>
          <div style="text-align:right;flex-shrink:0">
            <p style="font-size:.72rem;color:var(--clr-text-muted);white-space:nowrap">${log.time}</p>
            <p style="font-size:.68rem;font-weight:600;margin-top:2px;color:${log.success ? '#1A7A43' : '#C0392B'}">${log.success ? '✓ Berhasil' : '✗ Gagal'}</p>
          </div>
        </div>
      `).join('')
    : '<p style="font-size:.82rem;color:var(--clr-text-muted)">Tidak ada riwayat pengiriman.</p>';

  document.getElementById('riwayat-cert-modal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeRiwayatModal() {
  document.getElementById('riwayat-cert-modal').classList.remove('open');
  document.body.style.overflow = '';
}