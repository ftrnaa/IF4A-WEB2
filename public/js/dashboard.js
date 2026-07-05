const DASHBOARD_BASE_URL = '/dashboard';

/* ───────────────────────────────
   Helper kecil
   ─────────────────────────────── */
function formatRupiah(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
}

/* ───────────────────────────────
   Period Switch (AJAX, tanpa reload halaman)
   ─────────────────────────────── */
function setPeriod(period, extraParams = {}) {
    const buttons = document.querySelectorAll('.period-btn');
    buttons.forEach(b => {
        b.classList.toggle('active', b.dataset.period === period);
        b.disabled = true;
    });

    const query = new URLSearchParams({ period, ...extraParams }).toString();

    fetch(`${DASHBOARD_BASE_URL}/chart-data?${query}`)
        .then(res => {
            if (!res.ok) throw new Error('Gagal memuat data');
            return res.json();
        })
        .then(data => {
            // Update KPI (pakai optional chaining: aman walau elemen tidak ada di halaman)
            setText('kpi-revenue', formatRupiah(data.kpi.total_revenue));
            setText('kpi-sold', Number(data.kpi.products_sold).toLocaleString('id-ID'));
            setText('kpi-buyers', Number(data.kpi.active_buyers).toLocaleString('id-ID'));
            setText('kpi-pending', Number(data.kpi.pending ?? 0).toLocaleString('id-ID'));
            setText('kpi-cancelled', Number(data.kpi.cancelled ?? 0).toLocaleString('id-ID'));

            // Update judul chart
            const labels = {
                '7h': '7 Hari Terakhir',
                '30h': '30 Hari Terakhir',
                '3b': '3 Bulan Terakhir',
                '6b': '6 Bulan Terakhir',
                '1t': '1 Tahun Terakhir',
                'custom': 'Periode Custom',
            };
            setText('chart-title', 'Pendapatan — ' + (labels[period] || ''));

            // Render ulang bar chart
            const chart = document.getElementById('bar-chart');
            if (chart) {
                const maxVal = Math.max(...data.chart.chartValues, 1);
                chart.innerHTML = data.chart.chartLabels.map((label, i) => {
                    const val = data.chart.chartValues[i] || 0;
                    const pct = Math.max(Math.round((val / maxVal) * 100), 2);
                    return `
                        <div class="bar-chart__group">
                            <div class="bar-chart__bar" style="height:${pct}%">
                                <span class="bar-chart__tooltip">${formatRupiah(val)}</span>
                            </div>
                            <span class="bar-chart__label">${label}</span>
                        </div>`;
                }).join('');
            }

            // Simpan periode aktif untuk dipakai saat export
            window.__currentPeriod = period;
            window.__currentRange = extraParams;

            // Update URL tanpa reload, agar refresh tetap di periode yang sama
            const url = new URL(window.location);
            url.searchParams.set('period', period);
            if (extraParams.start_date) url.searchParams.set('start_date', extraParams.start_date);
            if (extraParams.end_date) url.searchParams.set('end_date', extraParams.end_date);
            window.history.replaceState({}, '', url);
        })
        .catch(() => {
            alert('Gagal memuat data periode. Silakan coba lagi.');
        })
        .finally(() => {
            buttons.forEach(b => b.disabled = false);
        });
}

/* ───────────────────────────────
   Custom Date Range
   ─────────────────────────────── */
function initCustomRange() {
    const rangeBox = document.getElementById('custom-range-box');
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');
    const applyBtn = document.getElementById('applyCustomRange');
    const errorText = document.getElementById('customRangeError');
    const customBtn = document.querySelector('.period-btn[data-period="custom"]');

    if (!customBtn || !rangeBox) return;

    customBtn.addEventListener('click', () => {
        rangeBox.classList.toggle('active');
    });

    applyBtn?.addEventListener('click', () => {
        if (errorText) errorText.textContent = '';

        const start = startInput?.value;
        const end = endInput?.value;

        if (!start || !end) {
            if (errorText) errorText.textContent = 'Pilih tanggal mulai dan tanggal akhir.';
            return;
        }

        const diffDays = (new Date(end) - new Date(start)) / (1000 * 60 * 60 * 24);
        if (diffDays < 90) {
            if (errorText) errorText.textContent = 'Minimal rentang 3 bulan.';
            return;
        }

        setPeriod('custom', { start_date: start, end_date: end });
    });
}

/* ───────────────────────────────
   Export
   ─────────────────────────────── */
function exportReport(type) {
    const overlay = document.getElementById('export-overlay');
    const title = document.getElementById('export-overlay-title');
    const msg = document.getElementById('export-overlay-msg');
    const period = window.__currentPeriod || '3b';
    const range = window.__currentRange || {};

    if (title) title.textContent = type === 'pdf' ? 'Menyiapkan PDF...' : 'Menyiapkan Excel...';
    if (msg) msg.textContent = 'Harap tunggu, laporan sedang diproses';
    overlay?.classList.add('active');

    const query = new URLSearchParams({ period, ...range }).toString();
    const exportUrl = `${DASHBOARD_BASE_URL}/export/${type}?${query}`;

    // Gunakan link tersembunyi agar browser men-download file dari endpoint Laravel asli
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    setTimeout(() => {
        overlay?.classList.remove('active');
    }, 1500);
}

/* ───────────────────────────────
   Detail Modal
   ─────────────────────────────── */
function openDetailModal(orderId) {
    const overlay = document.getElementById('detail-modal-overlay');
    const loading = document.getElementById('detail-loading');
    const content = document.getElementById('detail-content');
    const errorBox = document.getElementById('detail-error');

    overlay?.classList.add('active');
    if (loading) loading.style.display = 'flex';
    if (content) content.style.display = 'none';
    if (errorBox) errorBox.style.display = 'none';

    fetch(`${DASHBOARD_BASE_URL}/order/${orderId}`)
        .then(res => {
            if (!res.ok) throw new Error('not found');
            return res.json();
        })
        .then(data => {
            setText('d-invoice', data.invoice);

            const statusEl = document.getElementById('d-status');
            if (statusEl) {
                statusEl.textContent = data.status_label;
                statusEl.className = 'status-pill status-pill--' + data.status;
            }

            setText('d-user-name', data.user.name);
            setText('d-user-email', data.user.email);

            const img = document.getElementById('d-product-image');
            if (img) {
                if (data.product.image) {
                    img.src = data.product.image;
                    img.style.display = 'block';
                } else {
                    img.style.display = 'none';
                }
            }
            setText('d-product-name', data.product.name);
            setText('d-product-price', data.product.price);

            setText('d-payment',`${data.payment.type} / ${data.payment.channel}`
);

            setText('d-total', data.total_formatted);

            setText('d-created', data.created_at);
            setText('d-expired', data.expired_at);

            if (loading) loading.style.display = 'none';
            if (content) content.style.display = 'block';
        })
        .catch(() => {
            if (loading) loading.style.display = 'none';
            if (errorBox) errorBox.style.display = 'flex';
        });
}

function closeDetailModal() {
    document.getElementById('detail-modal-overlay')?.classList.remove('active');
}

/* ───────────────────────────────
   Init
   ─────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    window.__currentPeriod = params.get('period') || '3b';

    document.querySelectorAll('.period-btn').forEach(btn => {
        if (btn.dataset.period === 'custom') return; // ditangani initCustomRange
        btn.addEventListener('click', () => setPeriod(btn.dataset.period));
    });

    initCustomRange();

    document.getElementById('detail-modal-overlay')?.addEventListener('click', (e) => {
        if (e.target.id === 'detail-modal-overlay') closeDetailModal();
    });
});