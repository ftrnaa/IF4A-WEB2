{{-- ── Modal Lihat Sertifikat ── --}}
<div class="cert-modal-overlay" id="cert-modal">
    <div class="cert-modal">

        <div class="cert-modal__top">
            <button class="cert-modal__top-close" onclick="closeCertModal()">✕</button>
            <div class="cert-modal__seal">📜</div>
            <p class="cert-modal__top-title" id="cert-modal-title">Sertifikat Keaslian</p>
            <p class="cert-modal__top-sub">BatikAI · Verified Document</p>
        </div>

        <div class="cert-modal__body">
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Nama Dokumen</span>
                <span class="cert-detail-row__value" id="cert-modal-name">—</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Pemilik Lisensi</span>
                <span class="cert-detail-row__value">Rina Susanti</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Tanggal Diterbitkan</span>
                <span class="cert-detail-row__value" id="cert-modal-date">—</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Diterbitkan oleh</span>
                <span class="cert-detail-row__value">BatikAI — Platform Motif Nusantara</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Jenis Lisensi</span>
                <span class="cert-detail-row__value">Komersial Penuh (non-eksklusif)</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">No. Sertifikat</span>
                <span class="cert-detail-row__value" id="cert-modal-no">CERT-2026-001</span>
            </div>
            <div class="cert-detail-row">
                <span class="cert-detail-row__label">Status</span>
                <span class="cert-detail-row__value">
                    <span class="status-badge status-badge--paid">✓ Valid</span>
                </span>
            </div>
        </div>

        <div class="cert-modal__footer">
            <button class="cert-btn cert-btn--view" onclick="closeCertModal()">Tutup</button>
            <button class="cert-btn cert-btn--dl" style="padding:.5rem 1.2rem"
                    onclick="userToast('✓ File diunduh'); closeCertModal()">
                ⬇ Unduh PDF
            </button>
        </div>

    </div>
</div>