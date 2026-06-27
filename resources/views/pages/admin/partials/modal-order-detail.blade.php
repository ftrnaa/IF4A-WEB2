{{-- ── Order Detail Modal ── --}}
<div class="modal-overlay" id="detail-modal-overlay">
    <div class="modal-box" id="detail-modal-box">
        <div class="modal-box__header">
            <p class="modal-box__title">Detail Transaksi</p>
            <button class="modal-box__close" onclick="closeDetailModal()">✕</button>
        </div>

        <div class="modal-box__body" id="detail-modal-body">
            {{-- Loading state --}}
            <div class="detail-loading" id="detail-loading">
                <div class="export-spinner"></div>
                <span>Memuat detail transaksi...</span>
            </div>

            {{-- Content (filled by JS) --}}
            <div class="detail-content" id="detail-content" style="display:none">

                <div class="detail-row detail-row--split">
                    <div class="detail-block">
                        <p class="detail-block__label">No. Invoice</p>
                        <p class="detail-block__value" id="d-invoice">—</p>
                    </div>
                    <div class="detail-block detail-block--right">
                        <p class="detail-block__label">Status</p>
                        <span class="status-pill" id="d-status">—</span>
                    </div>
                </div>

                <div class="detail-divider"></div>

                <p class="detail-section-title">Informasi Pembeli</p>
                <div class="detail-row detail-row--split">
                    <div class="detail-block">
                        <p class="detail-block__label">Nama</p>
                        <p class="detail-block__value" id="d-user-name">—</p>
                    </div>
                    <div class="detail-block detail-block--right">
                        <p class="detail-block__label">Email</p>
                        <p class="detail-block__value" id="d-user-email">—</p>
                    </div>
                </div>

                <div class="detail-divider"></div>

                <p class="detail-section-title">Informasi Produk</p>
                <div class="detail-product">
    <img id="d-product-image" class="detail-product__img">
    <div>
        <p id="d-product-name">—</p>
        <p id="d-product-price">—</p>
    </div>
</div>

                <div class="detail-divider"></div>

                <div class="detail-row detail-row--split">
   <div class="detail-block">
    <p class="detail-block__label">Metode Pembayaran</p>
    <p id="d-payment">—</p>
</div>

<div class="detail-block detail-block--right">
    <p class="detail-block__label">Total</p>
    <p id="d-total">—</p>
</div>
</div>

                <div class="detail-divider"></div>

                <div class="detail-row detail-row--split">
    <div class="detail-block">
    <p class="detail-block__label">Tanggal Beli</p>
    <p id="d-created">—</p>
</div>

<div class="detail-block detail-block--right">
    <p class="detail-block__label">Tanggal Berakhir</p>
    <p id="d-expired">—</p>
</div>
</div>

            </div>

            {{-- Error state --}}
            <div class="detail-error" id="detail-error" style="display:none">
                Gagal memuat detail transaksi. Silakan coba lagi.
            </div>
        </div>
    </div>
</div>
