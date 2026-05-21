<div class="admin-modal-overlay" id="riwayat-cert-modal">
  <div class="admin-modal" style="max-width:580px">
    <div class="admin-modal__header">
      <div>
        <p class="admin-modal__title">Riwayat Pengiriman</p>
        <p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:2px" id="riwayat-modal-subtitle">—</p>
      </div>
      <button class="admin-modal__close" onclick="closeRiwayatModal()">✕</button>
    </div>

    <div class="admin-modal__body" style="display:flex;flex-direction:column;gap:1.2rem">

      {{-- Info Pembeli & Produk --}}
      <div style="display:flex;align-items:center;gap:1rem;background:rgba(200,169,110,.06);border:1px solid rgba(200,169,110,.15);border-radius:10px;padding:.9rem 1rem">
        <img id="riwayat-buyer-avatar" src="" style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0" alt="">
        <div style="flex:1;min-width:0">
          <p style="font-weight:500;font-size:.88rem;color:var(--clr-brown-dark)" id="riwayat-buyer-name">—</p>
          <p style="font-size:.72rem;color:var(--clr-text-muted)" id="riwayat-buyer-email">—</p>
        </div>
        <div style="text-align:right;flex-shrink:0">
          <p style="font-weight:500;font-size:.85rem;color:var(--clr-brown-dark)" id="riwayat-product-name">—</p>
          <p style="font-size:.7rem;color:var(--clr-text-muted)">Produk</p>
        </div>
        <img id="riwayat-motif-img" src="" style="width:40px;height:40px;border-radius:8px;object-fit:cover;flex-shrink:0" alt="">
      </div>

      {{-- Status Ringkas --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem" id="riwayat-status-cards">
        {{-- diisi JS --}}
      </div>

      <hr style="border:none;border-top:1px solid rgba(200,169,110,.12)">

      {{-- Log Riwayat --}}
      <div>
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.85rem">Log Pengiriman</p>
        <div id="riwayat-log-list" style="display:flex;flex-direction:column;gap:.5rem"></div>
      </div>

    </div>

    <div class="admin-modal__footer">
      <button class="admin-action-btn admin-action-btn--primary"
              id="riwayat-btn-kirim"
              onclick="document.getElementById('riwayat-cert-modal').classList.remove('open');document.body.style.overflow='';openSendModal(document.getElementById('riwayat-buyer-name').textContent,document.getElementById('riwayat-product-name').textContent)">
        📤 Kirim Ulang
      </button>
      <button class="admin-action-btn admin-action-btn--outline" onclick="closeRiwayatModal()">Tutup</button>
    </div>
  </div>
</div>