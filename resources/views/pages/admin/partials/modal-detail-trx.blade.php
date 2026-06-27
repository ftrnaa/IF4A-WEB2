<div class="admin-modal-overlay" id="detail-trx-modal">
  <div class="admin-modal" style="max-width:620px">
    <div class="admin-modal__header">
      <div>
        <p class="admin-modal__title">Detail Transaksi</p>
        <p style="font-size:.72rem;color:var(--clr-text-muted);margin-top:2px" id="modal-trx-id">—</p>
      </div>
      <button class="admin-modal__close" onclick="closeDetailModal()">✕</button>
    </div>

    <div class="admin-modal__body" style="display:flex;flex-direction:column;gap:1.2rem">

      {{-- Pembeli --}}
      <div>
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.5rem">Pembeli</p>
        <div class="admin-table__user">
          <img id="modal-buyer-avatar" src="" class="admin-table__avatar" style="width:40px;height:40px" alt="">
          <div>
            <p class="admin-table__user-name" id="modal-detail-buyer-name">—</p>
            <p class="admin-table__user-email" id="modal-detail-buyer-email">—</p>
          </div>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(200,169,110,.12)">

      {{-- Produk --}}
      <div>
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.5rem">Produk</p>
        <div style="display:flex;align-items:center;gap:1rem;background:rgba(200,169,110,.06);border:1px solid rgba(200,169,110,.15);border-radius:10px;padding:.9rem 1rem">
          <img id="modal-motif-img" src="" style="width:52px;height:52px;border-radius:8px;object-fit:cover;flex-shrink:0" alt="">
          <div>
            <p style="font-weight:500;font-size:.88rem;color:var(--clr-brown-dark)" id="modal-detail-product">—</p>
            <p style="font-size:.72rem;color:var(--clr-text-muted)" id="modal-detail-cat">—</p>
          </div>
          <p style="margin-left:auto;font-weight:700;font-size:1rem;color:var(--clr-green);flex-shrink:0" id="modal-detail-amount">—</p>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(200,169,110,.12)">

      {{-- Info Grid --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
        <div class="cert-info-box">
          <p class="cert-info-box__label">Metode Bayar</p>
          <p class="cert-info-box__value" id="modal-detail-method">—</p>
        </div>
        <div class="cert-info-box">
          <p class="cert-info-box__label">Tanggal Beli</p>
          <p class="cert-info-box__value" id="modal-detail-date">—</p>
        </div>
        <div class="cert-info-box">
          <p class="cert-info-box__label">Lisensi Berakhir</p>
          <p class="cert-info-box__value" id="modal-detail-expiry">—</p>
        </div>
        <div class="cert-info-box">
          <p class="cert-info-box__label">No. Referensi</p>
          <p class="cert-info-box__value" id="modal-detail-ref" style="color:var(--clr-brown)">—</p>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(200,169,110,.12)">

      {{-- Status --}}
      <div>
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.6rem">Status</p>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap" id="modal-detail-badges"></div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(200,169,110,.12)">

      {{-- Riwayat --}}
      <div>
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.75rem">Riwayat</p>
        <div id="modal-detail-timeline" style="display:flex;flex-direction:column;gap:.6rem"></div>
      </div>

    </div>

    <div class="admin-modal__footer">
      <button class="admin-action-btn admin-action-btn--danger" id="modal-btn-cancel" style="display:none">✕ Batalkan</button>
      <button class="admin-action-btn admin-action-btn--outline" id="modal-btn-cert" style="display:none" onclick="openSendModal(document.getElementById('modal-detail-buyer-name').textContent, document.getElementById('modal-detail-product').textContent)">🔄 Kirim Ulang Sertifikat</button>
      <button class="admin-action-btn admin-action-btn--primary" id="modal-btn-confirm" style="display:none">✓ Konfirmasi Bayar</button>
      <button class="admin-action-btn admin-action-btn--outline" onclick="closeDetailModal()">Tutup</button>
    </div>
  </div>
</div>
<script>
function openDetailModal(url)
{
    fetch(url)
        .then(response => response.json())
        .then(data => {

            document.getElementById('modal-trx-id').textContent =
                data.kode_order ?? '-';

            document.getElementById('modal-detail-buyer-name').textContent =
                data.nama ?? '-';

            document.getElementById('modal-detail-buyer-email').textContent =
                data.email ?? '-';

            document.getElementById('modal-detail-product').textContent =
                data.batik?.nama ?? '-';

            document.getElementById('modal-detail-cat').textContent =
                data.batik?.kategori ?? '-';

            document.getElementById('modal-detail-amount').textContent =
                'Rp ' + Number(data.total).toLocaleString('id-ID');

            document.getElementById('modal-buyer-avatar').src =
                'https://ui-avatars.com/api/?name=' +
                encodeURIComponent(data.nama);

            document.getElementById('modal-motif-img').src =
                data.batik?.preview_url ?? '';
              document.getElementById('modal-detail-method').textContent =
    (data.payment_type ?? '-') + ' / ' + (data.payment_channel ?? '-');

document.getElementById('modal-detail-date').textContent =
    data.created_at ?? '-';

document.getElementById('modal-detail-expiry').textContent =
    data.license_expired_at ?? '-';

document.getElementById('modal-detail-ref').textContent =
    data.reference_no ?? '-';

            document.getElementById('detail-trx-modal')
        .classList.add('open');
        })
        .catch(error => {
            console.error(error);
            alert('Gagal memuat detail transaksi');
        });
}

function closeDetailModal()
{
     document.getElementById('detail-trx-modal')
        .classList.remove('open');
}
const badges = document.getElementById('modal-detail-badges');

badges.innerHTML = `
    <span class="status-badge ${
        data.status === 'paid'
            ? 'status-badge--paid'
            : data.status === 'pending'
            ? 'status-badge--pending'
            : 'status-badge--failed'
    }">
        ${data.status}
    </span>
`;
</script>