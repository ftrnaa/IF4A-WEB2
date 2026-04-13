{{-- ── Modal: Kirim Sertifikat & Lisensi ── --}}
<div class="admin-modal-overlay" id="send-cert-modal">
    <div class="admin-modal">
        <div class="admin-modal__header">
            <p class="admin-modal__title">📤 Kirim Sertifikat & Lisensi</p>
            <button class="admin-modal__close" onclick="closeSendModal()">✕</button>
        </div>
        <div class="admin-modal__body">

            {{-- Info Pembeli --}}
            <div class="cert-info-box">
                <p class="cert-info-box__label">Pembeli</p>
                <p class="cert-info-box__value" id="modal-buyer-name">—</p>
            </div>
            <div class="cert-info-box" style="margin-top:-.4rem">
                <p class="cert-info-box__label">Produk</p>
                <p class="cert-info-box__value" id="modal-product-name">—</p>
            </div>

            <form class="admin-form" style="margin-top:1.2rem" id="send-cert-form">

                {{-- Email tujuan --}}
                <div class="admin-form-group">
                    <label class="admin-form-label">Email Tujuan <span style="color:#E74C3C">*</span></label>
                    <input type="email" class="admin-form-input" id="cert-email" placeholder="pembeli@email.com">
                    <p class="admin-form-hint">Terisi otomatis dari data pembeli, bisa diubah.</p>
                </div>

                {{-- Pilih yang dikirim --}}
                <div class="admin-form-group">
                    <label class="admin-form-label">Kirim</label>
                    <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:.3rem">
                        <label style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;cursor:pointer">
                            <input type="checkbox" id="send-cert-check" checked style="accent-color:var(--clr-green)">
                            📜 Sertifikat Keaslian (PDF)
                        </label>
                        <label style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;cursor:pointer">
                            <input type="checkbox" id="send-license-check" checked style="accent-color:var(--clr-green)">
                            📄 File Lisensi Komersial (PDF)
                        </label>
                        <label style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;cursor:pointer">
                            <input type="checkbox" id="send-file-check" style="accent-color:var(--clr-green)">
                            🖼️ File Motif Resolusi Tinggi (ZIP)
                        </label>
                    </div>
                </div>

                {{-- Upload manual --}}
                <div class="admin-form-group">
                    <label class="admin-form-label">Unggah File Kustom (opsional)</label>
                    <div class="admin-upload-area" onclick="document.getElementById('cert-file-input').click()" style="padding:1.2rem">
                        <div class="admin-upload-area__icon" style="font-size:1.4rem">📎</div>
                        <p class="admin-upload-area__text"><strong>Klik untuk unggah</strong> file tambahan</p>
                        <p class="admin-upload-area__text" style="font-size:.7rem;margin-top:.2rem">PDF, ZIP, PNG hingga 20 MB</p>
                        <input type="file" id="cert-file-input" accept=".pdf,.zip,.png,.jpg" style="display:none" multiple>
                    </div>
                    <div id="cert-file-list" style="margin-top:.6rem;display:flex;flex-direction:column;gap:.3rem"></div>
                </div>

                {{-- Pesan --}}
                <div class="admin-form-group">
                    <label class="admin-form-label">Pesan (opsional)</label>
                    <textarea class="admin-form-textarea" id="cert-msg" style="min-height:75px"
                        placeholder="Terima kasih telah membeli motif BatikAI..."></textarea>
                </div>

            </form>
        </div>
        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline" onclick="closeSendModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary" style="padding:.65rem 1.6rem" onclick="submitSend()">
                📤 Kirim Sekarang
            </button>
        </div>
    </div>
</div>