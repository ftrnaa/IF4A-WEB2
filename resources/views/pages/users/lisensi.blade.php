@extends('layouts.user-dashboard')
@section('title', 'Lisensi Saya — BatikAI')
@section('breadcrumb', 'Lisensi Saya')

@section('content')

@php
$today = \Carbon\Carbon::today();
$licenses = [
    ['name'=>'Sido Mukti',  'cat'=>'Klasik',  'date'=>'2026-04-13','amount'=>120000,'img'=>'batik1','method'=>'Transfer Bank', 'product_link'=>''],
    ['name'=>'Mega Mendung','cat'=>'Pesisir', 'date'=>'2025-12-10','amount'=>135000,'img'=>'batik3','method'=>'GoPay',          'product_link'=>'https://tokopedia.com/toko-batikku/mega-mendung-premium'],
    ['name'=>'Parang Rusak','cat'=>'Pesisir', 'date'=>'2025-04-05','amount'=>95000, 'img'=>'batik5','method'=>'Transfer Bank', 'product_link'=>''],
];
@endphp

<div class="admin-page-header">
    <h1>Lisensi Saya</h1>
    <p>Kelola semua lisensi motif batik yang sudah kamu beli.</p>
</div>

{{-- Stats --}}
<div class="user-stats-grid" style="margin-bottom:1.4rem">
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Total Lisensi</p>
            <p class="user-stat-card__value">3</p>
        </div>
        <div class="user-stat-card__icon usc-gold">🛡</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Aktif</p>
            <p class="user-stat-card__value">2</p>
        </div>
        <div class="user-stat-card__icon usc-green">✅</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Hampir Habis</p>
            <p class="user-stat-card__value">1</p>
        </div>
        <div class="user-stat-card__icon usc-blue">⚠️</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Kedaluwarsa</p>
            <p class="user-stat-card__value">1</p>
        </div>
        <div class="user-stat-card__icon usc-brown">⏰</div>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:1.2rem">
    @foreach($licenses as $idx => $lic)
    @php
        $buyDate    = \Carbon\Carbon::parse($lic['date']);
        $expiryDate = $buyDate->copy()->addYear();
        $daysLeft   = $today->diffInDays($expiryDate, false);
        $total      = 365;
        $elapsed    = $total - max(0, $daysLeft);
        $pct        = max(0, min(100, round(($elapsed / $total) * 100)));

        if ($daysLeft < 0)       { $status = 'expired';  $statusLabel = 'Kedaluwarsa'; $statusClass = 'status-badge--failed'; }
        elseif ($daysLeft <= 30) { $status = 'expiring'; $statusLabel = 'Hampir Habis'; $statusClass = 'status-badge--pending'; }
        else                     { $status = 'active';   $statusLabel = 'Aktif'; $statusClass = 'status-badge--paid'; }

        $progressClass = $status === 'expiring' ? 'license-progress__fill--warn'
                       : ($status === 'expired' ? 'license-progress__fill--expired' : '');

        $hasLink = !empty($lic['product_link']);
    @endphp

    <div class="user-card license-main-card" id="license-card-{{ $idx }}">
        <div class="user-card__body" style="padding:1.4rem 1.6rem">
            <div style="display:flex;gap:1.2rem;align-items:flex-start;flex-wrap:wrap">

                {{-- Motif Image --}}
                <img src="{{ asset('images/' . $lic['img'] . '.jpg') }}"
                     alt="{{ $lic['name'] }}"
                     style="width:80px;height:80px;border-radius:10px;object-fit:cover;flex-shrink:0">

                {{-- Info --}}
                <div style="flex:1;min-width:200px">
                    <p style="font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-gold);font-weight:600;margin-bottom:.2rem">{{ $lic['cat'] }}</p>
                    <p style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--clr-brown-dark);margin-bottom:.6rem">{{ $lic['name'] }}</p>

                    <div style="display:flex;gap:2rem;flex-wrap:wrap;margin-bottom:.9rem">
                        <div>
                            <p style="font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--clr-text-muted)">Tgl Beli</p>
                            <p style="font-size:.84rem;font-weight:600;color:var(--clr-brown-dark)">{{ $buyDate->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p style="font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--clr-text-muted)">Tgl Berakhir</p>
                            <p style="font-size:.84rem;font-weight:600;color:{{ $status === 'expired' ? '#C0392B' : ($status === 'expiring' ? '#B8610A' : 'var(--clr-brown-dark)') }}">
                                {{ $expiryDate->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--clr-text-muted)">Harga Beli</p>
                            <p style="font-size:.84rem;font-weight:600;color:var(--clr-green)">Rp {{ number_format($lic['amount'],0,',','.') }}</p>
                        </div>
                        <div>
                            <p style="font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--clr-text-muted)">Metode Bayar</p>
                            <p style="font-size:.84rem;font-weight:500;color:var(--clr-text-muted)">{{ $lic['method'] }}</p>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div style="display:flex;align-items:center;gap:.75rem">
                        <div style="flex:1;height:6px;background:var(--clr-cream-dark);border-radius:3px;overflow:hidden;max-width:240px">
                            <div class="license-progress__fill {{ $progressClass }}"
                                 style="width:{{ $pct }}%;height:100%;border-radius:3px"></div>
                        </div>
                        <span style="font-size:.72rem;color:var(--clr-text-muted);white-space:nowrap">
                            @if($status === 'expired')
                                Berakhir {{ abs($daysLeft) }} hari lalu
                            @else
                                {{ $daysLeft }} hari lagi ({{ 100 - $pct }}% tersisa)
                            @endif
                        </span>
                    </div>

                    {{-- Product Link Section --}}
                    <div class="product-link-section" style="margin-top:1rem;padding-top:1rem;border-top:1px dashed rgba(200,169,110,.25)">
                        <p style="font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--clr-text-muted);margin-bottom:.5rem;font-weight:600">
                            🔗 Link Produk Saya
                        </p>

                        {{-- Input form --}}
                        <div class="product-link-input-wrap" id="link-input-wrap-{{ $idx }}" style="{{ $hasLink ? 'display:none' : '' }}">
                            <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
                                <input type="url"
                                       class="product-link-input"
                                       id="link-input-{{ $idx }}"
                                       placeholder="https://tokopedia.com/toko-saya/produk-batik..."
                                       value="{{ $lic['product_link'] }}"
                                       style="flex:1;min-width:200px">
                                <button class="cert-btn cert-btn--dl"
                                        onclick="saveProductLink({{ $idx }}, '{{ $lic['name'] }}')"
                                        style="white-space:nowrap;padding:.38rem .85rem">
                                    💾 Simpan Link
                                </button>
                            </div>
                            <p style="font-size:.68rem;color:var(--clr-text-muted);margin-top:.35rem;opacity:.7">
                                Tempel link toko atau halaman produk yang menggunakan motif ini.
                            </p>
                        </div>

                        {{-- Preview card (shown when link exists) --}}
                        <div class="product-link-preview" id="link-preview-{{ $idx }}" style="{{ !$hasLink ? 'display:none' : '' }}">
                            <div class="product-preview-card" id="preview-card-{{ $idx }}"
                                 data-url="{{ $lic['product_link'] }}">
                                <div class="ppc__loading" id="ppc-loading-{{ $idx }}">
                                    <span class="ppc__spinner"></span>
                                    <span style="font-size:.75rem;color:var(--clr-text-muted)">Memuat detail produk…</span>
                                </div>
                                <div class="ppc__content" id="ppc-content-{{ $idx }}" style="display:none">
                                    <div class="ppc__favicon-wrap">
                                        <img class="ppc__favicon" id="ppc-favicon-{{ $idx }}" src="" alt="">
                                        <span class="ppc__domain" id="ppc-domain-{{ $idx }}"></span>
                                    </div>
                                    <p class="ppc__title" id="ppc-title-{{ $idx }}"></p>
                                    <p class="ppc__url" id="ppc-url-{{ $idx }}"></p>
                                    <div class="ppc__actions">
                                        <a class="cert-btn cert-btn--dl ppc__open-btn" id="ppc-open-{{ $idx }}" href="#" target="_blank" rel="noopener">
                                            ↗ Buka Produk
                                        </a>
                                        <button class="cert-btn cert-btn--view"
                                                onclick="editProductLink({{ $idx }})">
                                            ✏ Ganti Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end product link --}}

                </div>

                {{-- Status + Actions --}}
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.7rem;flex-shrink:0">
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>

                    <div style="display:flex;flex-direction:column;gap:.4rem;align-items:flex-end">
                        <a href="#" class="cert-btn cert-btn--dl" onclick="userToast('✓ File lisensi diunduh')">
                            ⬇ Unduh Lisensi (PDF)
                        </a>
                        <button class="cert-btn cert-btn--view"
                                onclick="viewCert('Lisensi Komersial — {{ $lic['name'] }}','{{ $buyDate->format('d M Y') }}')">
                            👁 Lihat Detail
                        </button>
                        @if($status === 'expired')
                        <a href="/koleksi" class="cert-btn" style="color:var(--clr-green);border-color:rgba(44,74,62,.2)">
                            🔄 Perpanjang
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
</div>

@include('pages.users.cert-modal')

@endsection

@push('scripts')
<script>
// Initialize product link previews on page load
document.addEventListener('DOMContentLoaded', () => {
    // Auto-load previews for cards that already have a link
    document.querySelectorAll('.product-preview-card[data-url]').forEach(card => {
        const url = card.dataset.url;
        if (url && url.trim() !== '') {
            const idx = card.id.replace('preview-card-', '');
            loadProductPreview(idx, url);
        }
    });
});
</script>
@endpush