// =========================
// SYNC BUTTON LOADING STATE
// =========================
document.addEventListener("DOMContentLoaded", function () {
    const syncForm = document.getElementById("syncForm");
    const syncBtn = document.getElementById("syncBtn");

    if (!syncForm || !syncBtn) return;

    const btnText = syncBtn.querySelector(".sync-btn-text");
    const originalText = btnText ? btnText.textContent : null;

    syncForm.addEventListener("submit", function () {
        // Disable button biar tidak double click
        syncBtn.disabled = true;

        // Ubah tampilan tombol
        if (btnText) {
            btnText.textContent = "Sinkronisasi...";
        }

        // Tambah class loading (memunculkan spinner via CSS)
        syncBtn.classList.add("loading");
    });

    // Optional: kalau form gagal submit (misal validasi browser),
    // kembalikan tombol ke kondisi semula setelah beberapa detik
    window.addEventListener("pageshow", function () {
        syncBtn.disabled = false;
        syncBtn.classList.remove("loading");
        if (btnText && originalText) {
            btnText.textContent = originalText;
        }
    });
});