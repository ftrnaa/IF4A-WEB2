@extends('layouts.user-dashboard')
@section('title', 'Lisensi Saya — BatikAI')
@section('breadcrumb', 'Produk Saya')

@section('content')

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif
<div class="admin-page-header">
    <h1>Produk Saya</h1>
    <p>Kelola semua lisensi motif batik yang sudah kamu beli.</p>
</div>

{{-- Stats --}}
<div class="user-stats-grid" style="margin-bottom:1.5rem">
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Total Lisensi</p>
            <p class="user-stat-card__value">{{ $orders->count() }}</p>
        </div>
        <div class="user-stat-card__icon usc-gold">🛡</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Aktif</p>
            <p class="user-stat-card__value">{{ $aktif }}</p>
        </div>
        <div class="user-stat-card__icon usc-green">✅</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Hampir Habis</p>
            <p class="user-stat-card__value">{{ $hampirHabis }}</p>
        </div>
        <div class="user-stat-card__icon usc-blue">⚠️</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Kedaluwarsa</p>
            <p class="user-stat-card__value">{{ $kedaluwarsa }}</p>
        </div>
        <div class="user-stat-card__icon usc-brown">⏰</div>
    </div>
</div>

{{-- License Cards --}}
<div style="display:flex;flex-direction:column;gap:1rem">

    @foreach($orders as $idx => $order)

    @php
     $buyDate = \Carbon\Carbon::parse($order->created_at);
    $expiryDate = \Carbon\Carbon::parse($order->license_expired_at);
    $today = now();

    $daysLeft = (int) $today->diffInDays($expiryDate, false);

    // tombol perpanjang muncul jika sisa <= 2 hari
// apakah order lama sudah pernah diperpanjang
$isHistory = !is_null($order->renewed_at);

// apakah motif sudah dimiliki user lain
$isOwnedByOther = \App\Models\Order::where('batik_id', $order->batik_id)
    ->where('status', 'paid')
    ->where('user_id', '!=', $order->user_id)
    ->exists();

// tombol renew hanya muncul jika:
// - sisa <= 2 hari
// - belum pernah diperpanjang
// - belum dibeli user lain
$canRenew =
    $daysLeft <= 2 &&
    !$isHistory &&
    !$isOwnedByOther;

    $total = 365;
    $elapsed = $total - max(0, $daysLeft);
    $pct = max(0, min(100, ($elapsed / $total) * 100));
    $remainingPct = max(0, floor(($daysLeft / 365) * 100));

    if ($isHistory) {

    $status = 'history';
    $statusLabel = 'Riwayat';
    $pillClass = 'lic-pill--active';
    $badgeClass = 'lic-img-badge--active';
    $progressClass = '';

} elseif ($daysLeft < 0) {

    $status = 'expired';
    $statusLabel = 'Kedaluwarsa';
    $pillClass = 'lic-pill--expired';
    $badgeClass = 'lic-img-badge--expired';
    $progressClass = 'lic-progress__fill--expired';

} elseif ($daysLeft <= 30) {

    $status = 'expiring';
    $statusLabel = 'Hampir Habis';
    $pillClass = 'lic-pill--warn';
    $badgeClass = 'lic-img-badge--warn';
    $progressClass = 'lic-progress__fill--warn';

} else {

    $status = 'active';
    $statusLabel = 'Aktif';
    $pillClass = 'lic-pill--active';
    $badgeClass = 'lic-img-badge--active';
    $progressClass = '';

}
    $images = [];

    if ($order->batik->preview_image) {
        $images[] = $order->batik->preview_image;
    }

    $costumeImages = $order->batik->costume_images ?? [];

    if (is_array($costumeImages)) {
        $images = array_merge($images, $costumeImages);
    }
@endphp

    <div class="lic-card {{ $status === 'expired' ? 'lic-card--expired' : '' }}" id="license-card-{{ $idx }}">
        <div class="lic-card__inner">

            {{-- ── Kolom Gambar ─────────────────────────────── --}}
            <div class="lic-img-col">
                <div class="batik-slider lic-slider" data-images='@json($images)'>
                    <img
                        class="batik-slider-img lic-slider__img"
                        src="https://btx.agunghakase.my.id/api/image/{{ $images[0] ?? 'placeholder' }}"
                        alt="{{ $order->batik->nama }}">
                </div>
                <span class="lic-img-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
            </div>

            {{-- ── Kolom Info ────────────────────────────────── --}}
            <div class="lic-body">

                {{-- Judul --}}
                <div>
                    <p class="lic-category">{{ $order->batik->kategory }}</p>
                    <p class="lic-name">
    {{ $order->batik->nama }}

    @if($isHistory)
        <span class="badge bg-secondary ms-2">
            Riwayat
        </span>
    @endif
</p>
                </div>

                {{-- Meta --}}
                <div class="lic-meta">
                    <div class="lic-meta__group">
                        <p class="lic-meta__label">Tgl Beli</p>
                        <p class="lic-meta__value">{{ $buyDate->format('d M Y') }}</p>
                    </div>
                    <div class="lic-meta__group">
                        <p class="lic-meta__label">Tgl Berakhir</p>
                        <p class="lic-meta__value {{ $status === 'expired' ? 'lic-meta__value--expired' : ($status === 'expiring' ? 'lic-meta__value--warn' : '') }}">
                            {{ $expiryDate->format('d M Y') }}
                        </p>
                    </div>
                    <div class="lic-meta__group">
                        <p class="lic-meta__label">Harga Beli</p>
                        <p class="lic-meta__value lic-meta__value--price">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="lic-meta__group">
                        <p class="lic-meta__label">Metode Bayar</p>
                        <p class="lic-meta__value lic-meta__value--muted">{{ $order->payment_type ?? '-' }}</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="lic-progress-row">
                    <div class="lic-progress-track">
                        <div class="lic-progress__fill {{ $progressClass }}" style="width:{{ $pct }}%"></div>
                    </div>
                    <span class="lic-progress-text">
                        @if($status === 'history')

    Riwayat Lisensi

@elseif($status === 'expired')

    Berakhir {{ abs($daysLeft) }} hari lalu

@else

    {{ $daysLeft }} hari lagi ({{ $remainingPct }}% tersisa)

@endif
                    </span>
                </div>

                {{-- Product Link Section --}}
                <div class="lic-link-section">
                    <p class="lic-link-section__label">🔗 Link Produk Saya</p>

                    {{-- Saved links --}}
                    @if($order->productLinks->count() > 0)
                    <div class="lic-saved-links">
                        @foreach($order->productLinks as $link)
                        <div class="lic-saved-link">
                            <div class="lic-saved-link__info">
                                <strong>{{ $link->title }}</strong>
                                <span>{{ Str::limit($link->url, 60) }}</span>
                            </div>
                            <div class="lic-saved-link__actions">
                                <a href="{{ $link->url }}" target="_blank" class="cert-btn cert-btn--view">↗ Buka</a>
                                <button
                                    class="cert-btn cert-btn--danger"
                                    onclick="deleteProductLink({{ $link->id }})">🗑</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @php
    $linkCount = $order->productLinks->count();
@endphp
                    {{-- Tombol tambah --}}
                @if($linkCount < 5)
    <button
        type="button"
        class="cert-btn cert-btn--add"
        onclick="showAddLink({{ $idx }})">
        ➕ Tambah Link
    </button>
@else
    <button
        type="button"
        class="cert-btn cert-btn--add"
        disabled
        style="opacity:.5;cursor:not-allowed">
        🚫 Maksimal 5 Link
    </button>
@endif

                    {{-- Form input link (tersembunyi) --}}
                    <div class="lic-link-input-wrap" id="link-input-wrap-{{ $idx }}" style="display:none">
                        <input
                            type="url"
                            class="lic-link-input"
                            id="link-input-{{ $idx }}"
                            placeholder="https://tokopedia.com/toko-saya/produk-batik...">
                        <button
                            class="cert-btn cert-btn--dl"
                            onclick="saveProductLink({{ $idx }}, {{ $order->id }}, '{{ $order->batik->nama }}')">
                            💾 Simpan
                        </button>
                    </div>
                    <p class="lic-link-hint">Tempel link toko atau halaman produk yang menggunakan motif ini.</p>
                </div>

            </div>{{-- /lic-body --}}

            {{-- ── Kolom Aksi ────────────────────────────────── --}}
            <div class="lic-actions">

                <span class="lic-pill {{ $pillClass }}">{{ $statusLabel }}</span>

                <div class="lic-actions__buttons">
                    <a href="{{ route('license.motif.pdf', $order->id) }}" class="cert-btn cert-btn--dl cert-btn--block">
                        ⬇ Unduh Motif HD
                    </a>
                    <a href="{{ route('license.certificate.pdf', $order->id) }}" class="cert-btn cert-btn--cert cert-btn--block">
                        📜 Sertifikat PDF
                    </a>
                   @if(
    $canRenew &&
    !$order->renewed_at
)
<a href="{{ route('license.renew', $order->id) }}"
   class="cert-btn cert-btn--renew cert-btn--block">
    🔄 Perpanjang Lisensi
</a>
@endif
                </div>

            </div>

        </div>{{-- /lic-card__inner --}}
    </div>{{-- /lic-card --}}

    @endforeach

</div>

@include('pages.users.cert-modal')

@endsection

@push('styles')
<style>
/* ── License Card Grid ───────────────────────────────────── */
.lic-card {
    background: var(--clr-white);
    border-radius: var(--radius-md);
    border: 1px solid rgba(200,169,110,.12);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: box-shadow .2s;
}
.lic-card:hover { box-shadow: var(--shadow-md); }
.lic-card--expired { opacity: .85; }

.lic-card__inner {
    display: grid;
    grid-template-columns: 160px 1fr 160px;
}

/* ── Image Column ────────────────────────────────────────── */
.lic-img-col {
    position: relative;
    flex-shrink: 0;
}
.lic-slider {
    width: 160px;
    height: 100%;
    min-height: 200px;
    border-radius: 0;
    overflow: hidden;
}
.lic-slider__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: opacity .4s ease;
}
.lic-img-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    padding: .2rem .6rem;
    border-radius: 100px;
}
.lic-img-badge--active  { background: rgba(39,174,96,.18);  color: #1A6B3C; }
.lic-img-badge--warn    { background: rgba(186,117,23,.2);  color: #7A4C0A; }
.lic-img-badge--expired { background: rgba(192,57,43,.15);  color: #922B21; }

/* ── Body Column ─────────────────────────────────────────── */
.lic-body {
    padding: 1.3rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: .85rem;
    border-left: 1px solid rgba(200,169,110,.1);
    border-right: 1px solid rgba(200,169,110,.1);
    min-width: 0;
}

.lic-category {
    font-size: .68rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--clr-gold);
    font-weight: 700;
    margin-bottom: .2rem;
}
.lic-name {
    font-family: var(--font-display);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--clr-brown-dark);
    line-height: 1.35;
}

.lic-meta {
    display: flex;
    gap: 1.6rem;
    flex-wrap: wrap;
}
.lic-meta__label {
    font-size: .62rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--clr-text-muted);
    margin-bottom: .15rem;
    font-weight: 600;
}
.lic-meta__value {
    font-size: .85rem;
    font-weight: 600;
    color: var(--clr-brown-dark);
}
.lic-meta__value--expired { color: #C0392B; }
.lic-meta__value--warn    { color: #B8610A; }
.lic-meta__value--price   { color: var(--clr-green); }
.lic-meta__value--muted   { color: var(--clr-text-muted); font-weight: 500; }

/* Progress */
.lic-progress-row {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.lic-progress-track {
    flex: 1;
    max-width: 260px;
    height: 5px;
    background: var(--clr-cream-dark);
    border-radius: 3px;
    overflow: hidden;
}
.lic-progress__fill {
    height: 100%;
    border-radius: 3px;
    background: var(--clr-green);
    transition: width .5s;
}
.lic-progress__fill--warn    { background: #E67E22; }
.lic-progress__fill--expired { background: #E74C3C; }
.lic-progress-text {
    font-size: .72rem;
    color: var(--clr-text-muted);
    white-space: nowrap;
}

/* Link Section */
.lic-link-section {
    padding-top: .9rem;
    border-top: 1px dashed rgba(200,169,110,.25);
    display: flex;
    flex-direction: column;
    gap: .45rem;
}
.lic-link-section__label {
    font-size: .62rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--clr-text-muted);
    font-weight: 700;
    margin-bottom: .1rem;
}
.lic-saved-links {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    margin-bottom: .2rem;
}
.lic-saved-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    background: var(--clr-cream-light);
    border: 1px solid rgba(200,169,110,.15);
    border-radius: var(--radius-sm);
    padding: .55rem .85rem;
}
.lic-saved-link__info strong {
    font-size: .82rem;
    font-weight: 600;
    color: var(--clr-brown-dark);
    display: block;
}
.lic-saved-link__info span {
    font-size: .72rem;
    color: var(--clr-text-muted);
}
.lic-saved-link__actions {
    display: flex;
    gap: .4rem;
    flex-shrink: 0;
}
.lic-link-input-wrap {
    display: flex;
    gap: .5rem;
    align-items: center;
    flex-wrap: wrap;
}
.lic-link-input {
    flex: 1;
    min-width: 180px;
    padding: .45rem .8rem;
    font-size: .82rem;
    color: var(--clr-text);
    background: var(--clr-cream-light);
    border: 1.5px solid var(--clr-cream-dark);
    border-radius: var(--radius-sm);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    font-family: var(--font-body);
}
.lic-link-input:focus {
    border-color: var(--clr-gold);
    box-shadow: 0 0 0 3px rgba(200,169,110,.15);
}
.lic-link-hint {
    font-size: .65rem;
    color: var(--clr-text-muted);
    opacity: .7;
}

/* ── Actions Column ──────────────────────────────────────── */
.lic-actions {
    padding: 1.3rem 1.2rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    gap: .6rem;
}
.lic-actions__buttons {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    width: 100%;
}

/* Status Pill */
.lic-pill {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .28rem .75rem;
    border-radius: 100px;
    align-self: flex-end;
}
.lic-pill--active  { background: rgba(39,174,96,.12);  color: #1A6B3C; }
.lic-pill--warn    { background: rgba(186,117,23,.15);  color: #7A4C0A; }
.lic-pill--expired { background: rgba(192,57,43,.1);   color: #922B21; }

/* Extra cert-btn variants */
.cert-btn--block  { display: flex; justify-content: center; width: 100%; }
.cert-btn--add    { color: var(--clr-gold); border-color: rgba(200,169,110,.35); background: transparent; }
.cert-btn--add:hover { background: rgba(200,169,110,.1); }
.cert-btn--cert   { color: #185FA5; border-color: rgba(24,95,165,.2); background: transparent; }
.cert-btn--cert:hover { background: rgba(24,95,165,.06); }
.cert-btn--renew  { color: var(--clr-green); border-color: rgba(44,74,62,.2); background: transparent; }
.cert-btn--renew:hover { background: rgba(44,74,62,.07); }
.cert-btn--danger { color: #C0392B; border-color: rgba(192,57,43,.2); background: transparent; }
.cert-btn--danger:hover { background: rgba(192,57,43,.06); }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 860px) {
    .lic-card__inner {
        grid-template-columns: 130px 1fr;
        grid-template-rows: auto auto;
    }
    .lic-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        align-items: center;
        padding: .9rem 1.2rem;
        border-top: 1px solid rgba(200,169,110,.1);
        justify-content: space-between;
    }
    .lic-actions__buttons {
        flex-direction: row;
        width: auto;
        gap: .4rem;
    }
    .cert-btn--block { width: auto; }
}

@media (max-width: 600px) {
    .lic-card__inner { grid-template-columns: 110px 1fr; }
    .lic-slider { width: 110px; min-height: 160px; }
    .lic-meta { gap: 1rem; }
    .lic-actions__buttons { flex-wrap: wrap; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Image slider ─────────────────────────────────── */
    document.querySelectorAll('.batik-slider').forEach(slider => {
        const images = JSON.parse(slider.dataset.images);
        if (images.length < 2) return;
        let current = 0;
        const img = slider.querySelector('.batik-slider-img');
        setInterval(() => {
            img.style.opacity = 0;
            setTimeout(() => {
                current = (current + 1) % images.length;
                img.src = `https://btx.agunghakase.my.id/api/image/${images[current]}`;
                img.style.opacity = 1;
            }, 300);
        }, 3000);
    });

});

/* ── Show / hide add-link form ────────────────────────── */
function showAddLink(idx) {
    const wrap = document.getElementById(`link-input-wrap-${idx}`);
    if (!wrap) return;
    wrap.style.display = wrap.style.display === 'none' ? 'flex' : 'none';
    if (wrap.style.display === 'flex') {
        document.getElementById(`link-input-${idx}`)?.focus();
    }
}

/* ── Save product link ────────────────────────────────── */
async function saveProductLink(idx, orderId, batikName) {
    const input = document.getElementById(`link-input-${idx}`);
    const url = input?.value?.trim();

    if (!url) {
        alert('Masukkan URL terlebih dahulu.');
        return;
    }

    try {
        const response = await fetch('/product-links', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                order_id: orderId,
                url,
                title: batikName
            }),
        });

        const data = await response.json();

        // 🔥 FIX UTAMA DI SINI
        if (!response.ok) {
            alert(data.message || 'Gagal menyimpan link.');
            return;
        }

        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal menyimpan link.');
        }

    } catch (err) {
        console.error(err);
        alert('Server error / response tidak valid JSON.');
    }
}

/* ── Delete product link ──────────────────────────────── */
async function deleteProductLink(id) {
    if (!confirm('Hapus link produk ini?')) return;

    try {
        const response = await fetch(`/product-links/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            alert(data.message ?? 'Gagal menghapus link.');
        }
    } catch (err) {
        alert('Terjadi kesalahan. Coba lagi.');
    }
}
</script>
@endpush