@extends('layouts.app')

@section('title', Str::limit($motif['keyword'] ?? 'Detail Motif', 60))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/detail-motif.css') }}">
@endpush

@section('content')

@php
use Illuminate\Support\Str;
$baseUrl = 'https://btx.agunghakase.my.id/api/image/';

$slides = [];
if (!empty($motif['file_preview'])) {
    $slides[] = [
        'url'   => $baseUrl . $motif['file_preview'],
        'label' => 'Motif Pattern',
    ];
}
foreach ($costumeFiles as $cf) {
    $slides[] = [
        'url'   => $baseUrl . $cf,
        'label' => 'Tampilan Kostum',
    ];
}
if (empty($slides)) {
    $slides[] = [
        'url'   => 'https://via.placeholder.com/600x450?text=No+Image',
        'label' => '',
    ];
}

// Ambil nama dari keyword (setelah koma kedua)
$kwParts  = explode(',', $motif['keyword'] ?? '');
$namaBatik = isset($kwParts[2]) ? ucfirst(trim($kwParts[2])) : ucfirst(trim($kwParts[0] ?? 'Motif Batik'));

// Tanggal
$tgl = '';
if (!empty($motif['created_at'])) {
    try { $tgl = \Carbon\Carbon::parse($motif['created_at'])->format('d M Y'); }
    catch (\Exception $e) {}
}

// Kode
$fileCode = '';
if (!empty($motif['file_preview'])) {
    preg_match('/^(\d{4})/', $motif['file_preview'], $mc);
    $fileCode = $mc[1] ?? '';
}
@endphp

<div class="container">

    {{-- BREADCRUMB --}}
    <nav class="breadcrumb">
        <a href="{{ route('koleksi') }}">Koleksi</a>
        <span class="sep">›</span>
        <span class="current">{{ Str::limit($namaBatik, 40) }}</span>
    </nav>

    <div class="detail-layout">

        {{-- ===== GALLERY ===== --}}
        <div class="gallery-section">
            <div class="gallery-wrap" id="galleryWrap">

                {{-- Slide counter --}}
                @if(count($slides) > 1)
                <div class="slide-counter" id="slideCounter">1 / {{ count($slides) }}</div>
                @endif

                <div class="slides" id="slides">
                    @foreach($slides as $s)
                    <div class="slide">
                        <img src="{{ $s['url'] }}" alt="{{ $s['label'] }}" loading="lazy">
                        @if($s['label'])
                        <span class="slide-label">{{ $s['label'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if(count($slides) > 1)
                <button class="slider-btn prev" onclick="changeSlide(-1)" aria-label="Sebelumnya">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>
                <button class="slider-btn next" onclick="changeSlide(1)" aria-label="Berikutnya">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="9 6 15 12 9 18"/>
                    </svg>
                </button>
                @endif
            </div>

            {{-- Dots --}}
            @if(count($slides) > 1)
            <div class="dots-wrap" id="dots">
                @foreach($slides as $i => $s)
                <div class="dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})" aria-label="Slide {{ $i + 1 }}"></div>
                @endforeach
            </div>
            @endif

            {{-- Thumbnail strip --}}
            @if(count($slides) > 1)
            <div class="thumb-strip" id="thumbStrip">
                @foreach($slides as $i => $s)
                <div class="thumb-item {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})">
                    <img src="{{ $s['url'] }}" alt="{{ $s['label'] }}" loading="lazy">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ===== INFO PANEL ===== --}}
        <div class="info-panel">

            {{-- Meta badges --}}
            <div class="motif-meta">
                @if($fileCode)
                    <span class="meta-badge">#{{ $fileCode }}</span>
                @endif
                @if(!empty($motif['style']))
                    <span class="meta-badge">{{ Str::limit($motif['style'], 30) }}</span>
                @endif
                @if(!empty($motif['warna']))
                    <span class="meta-badge">{{ $motif['warna'] }}</span>
                @endif
                @if($tgl)
                    <span class="meta-badge">{{ $tgl }}</span>
                @endif
            </div>

            {{-- Judul --}}
            <h1 class="motif-keyword">{{ $namaBatik }}</h1>

            {{-- Harga --}}
            <p class="motif-harga">Rp 100.000</p>
            <p class="motif-harga-note">Lisensi personal · Akses download instan</p>

            {{-- Stats bar --}}
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-num">HD</span>
                    <span class="stat-label">Resolusi</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">SVG</span>
                    <span class="stat-label">Format file</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">{{ count($slides) }}</span>
                    <span class="stat-label">Preview foto</span>
                </div>
            </div>

            {{-- CTA --}}
            <a href="{{ route('checkout') }}" class="btn-beli">
                <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Beli Lisensi
            </a>
            

            <div class="divider"></div>

            {{-- Deskripsi --}}
            <div class="section-box">
                <h3>Deskripsi Motif</h3>
                <p>Motif batik ini mengusung unsur tradisional Nusantara yang dipadukan dengan sentuhan desain modern. Detail pola yang elegan serta komposisi warna yang harmonis menjadikan desain ini cocok untuk kebutuhan fashion, dekorasi, branding, hingga media digital.</p>
            </div>

            {{-- Yang didapat --}}
            <div class="section-box">
                <h3>Apa yang Anda Dapatkan</h3>
                <ul>
                    <li>File SVG &amp; PNG resolusi tinggi</li>
                    <li>Siap cetak &amp; penggunaan digital</li>
                    <li>Akses download instan setelah pembayaran</li>
                    <li>{{ count($slides) }} foto preview eksklusif</li>
                </ul>
            </div>

            {{-- Lisensi --}}
            <div class="section-box">
                <h3>Lisensi</h3>
                <p>Lisensi bersifat personal dan tidak dapat dialihkan, dibagikan, atau dijual kembali dalam bentuk apa pun termasuk sebagai file digital.</p>
            </div>

            {{-- Link produk --}}
            <div class="section-box">
                <h3>Link Produk</h3>
                <p>Setelah membeli, Anda dapat menambahkan tautan produk yang menggunakan motif ini — pakaian, aksesori, atau produk lain yang telah diproduksi dan dijual.</p>
            </div>

        </div>
    </div>
</div>

{{-- RELATED MOTIFS --}}
@if(!empty($relatedMotifs))
<div class="container related-section">
    <h2 class="related-title">Motif <span>Serupa</span></h2>

    <div class="motif-grid">
        @foreach($relatedMotifs as $rel)
        @php
            $relParts = explode(',', $rel['keyword'] ?? '');
            $relName  = isset($relParts[2])
                ? Str::limit(ucfirst(trim($relParts[2])), 40)
                : Str::limit(ucfirst(trim($relParts[0] ?? 'Batik')), 40);
        @endphp
        <a href="{{ route('detail', $rel['id']) }}?q={{ urlencode($rel['keyword'] ?? '') }}" class="motif-card">
            <div class="card-image-wrap">
                @if(!empty($rel['file_preview']))
                    <img src="http://btx.agunghakase.my.id/api/image/{{ $rel['file_preview'] }}"
                         alt="{{ $relName }}" loading="lazy">
                @else
                    <img src="https://via.placeholder.com/300x190?text=No+Image" alt="No Image">
                @endif
                <span class="card-badge">Batik</span>
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $relName }}</h3>
                <p class="card-price">Rp 100.000</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('js/detail-motif.js') }}"></script>
<script>
    initSlider({{ count($slides) }});
</script>
@endpush