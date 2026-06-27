@extends('layouts.app')

@section('title', Str::limit($motif->nama ?? 'Detail Motif', 60))
@push('styles')
<link rel="stylesheet" href="{{ asset('css/detail-motif.css') }}">
@endpush

@section('content')

@php

use Illuminate\Support\Str;

$namaBatik = $motif->nama ?? 'Motif Batik';

$fileCode = null;

if (!empty($motif->preview_image)) {

    preg_match('/^(\d+)_/', $motif->preview_image, $match);

    $fileCode = $match[1] ?? null;
}

$tgl = $motif->api_created_at ?? null;

// =========================
// SLIDES
// =========================
$slides = [];

// preview utama
if (!empty($motif->preview_url)) {

    $slides[] = [
        'url' => $motif->preview_url,
        'label' => 'Motif Pattern'
    ];
}

// costume images
if (!empty($motif->costume_images) && is_array($motif->costume_images)) {

    foreach ($motif->costume_images as $cf) {

        $slides[] = [
            'url' => 'https://btx.agunghakase.my.id/api/image/' . $cf,
            'label' => 'Tampilan Kostum'
        ];
    }
}

// fallback
if (empty($slides)) {

    $slides[] = [
        'url' => 'https://via.placeholder.com/600x450?text=No+Image',
        'label' => ''
    ];
}

@endphp

<div class="container">

    {{-- BREADCRUMB --}}
    <nav class="breadcrumb">

        <a href="{{ route('koleksi') }}">
            Koleksi
        </a>

        <span class="sep">›</span>

        <span class="current">
            {{ Str::limit($namaBatik, 40) }}
        </span>

    </nav>

    <div class="detail-layout">

        {{-- ========================= --}}
        {{-- GALLERY --}}
        {{-- ========================= --}}
        <div class="gallery-section">

            <div class="gallery-wrap" id="galleryWrap">

                @if(count($slides) > 1)
                <div class="slide-counter">
                    1 / {{ count($slides) }}
                </div>
                @endif

                <div class="slides" id="slides">

                    @foreach($slides as $s)

                    <div class="slide">

                        <img
                            src="{{ $s['url'] }}"
                            alt="{{ $namaBatik }}"
                            loading="lazy"
                        >

                        @if($s['label'])
                        <span class="slide-label">
                            {{ $s['label'] }}
                        </span>
                        @endif

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        {{-- ========================= --}}
        {{-- INFO PANEL --}}
        {{-- ========================= --}}
        <div class="info-panel">

            {{-- META --}}
            <div class="motif-meta">

                @if($fileCode)
                    <span class="meta-badge">
                        #{{ $fileCode }}
                    </span>
                @endif

                @if(!empty($motif->kategori))
                    <span class="meta-badge">
                        {{ Str::limit($motif->kategori, 30) }}
                    </span>
                @endif

               @if(!empty($motif->warna))
                    <span class="meta-badge">
                        {{ $motif->warna }}
                    </span>
                @endif

                @if($tgl)
                    <span class="meta-badge">
                        {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                    </span>
                @endif

            </div>

            {{-- TITLE --}}
            <h1 class="motif-keyword">
                {{ $namaBatik }}
            </h1>

            {{-- PRICE --}}
            <p class="motif-harga">
                Rp 150.000
            </p>

            <p class="motif-harga-note">
                Lisensi personal · Akses download instan
            </p>

            {{-- STATS --}}
            <div class="stats-bar">

                <div class="stat-item">
                    <span class="stat-num">HD</span>
                    <span class="stat-label">Resolusi</span>
                </div>

                <div class="stat-item">
                    <span class="stat-num">SVG</span>
                    <span class="stat-label">Format</span>
                </div>

                <div class="stat-item">
                    <span class="stat-num">
                        {{ count($slides) }}
                    </span>

                    <span class="stat-label">
                        Preview
                    </span>
                </div>

            </div>

            {{-- CTA --}}
            <a href="{{ route('checkout', $motif->id) }}" class="btn-beli">
                Beli Lisensi
            </a>

            <div class="divider"></div>

            {{-- DESCRIPTION --}}
            <div class="section-box">

                <h3>Deskripsi Motif</h3>

                <p>
                    {{ $motif->deskripsi ?? 'Deskripsi belum tersedia.' }}
                </p>

            </div>

            {{-- WHAT YOU GET --}}
            <div class="section-box">

                <h3>Apa yang Anda Dapatkan</h3>

                <ul>
                    <li>File PNG resolusi tinggi</li>
                    <li>Preview motif & costume</li>
                    <li>Akses download instan</li>
                    <li>{{ count($slides) }} preview gambar</li>
                </ul>

            </div>

            {{-- LICENSE --}}
            
            {{-- PRODUCT LINKS --}}
<div class="section-box">

    <h3>Produk yang Menggunakan Motif Ini</h3>

    @if($productLinks->count())

        <div class="product-links">

            @foreach($productLinks->unique('url') as $link)

                <a
                    href="{{ $link->url }}"
                    target="_blank"
                    class="product-link-item"
                >
                    🔗 {{ $link->title ?: parse_url($link->url, PHP_URL_HOST) }}
                </a>

            @endforeach

        </div>

    @else

        <p>
            Belum ada produk yang menggunakan motif ini.
        </p>

    @endif

</div>

        </div>

    </div>
</div>

{{-- ========================= --}}
{{-- RELATED MOTIFS --}}
{{-- ========================= --}}
@if(!empty($relatedMotifs))

<div class="container related-section">

    <h2 class="related-title">
        Motif <span>Serupa</span>
    </h2>

    <div class="motif-grid">

        @foreach($relatedMotifs as $rel)

        @php
            $relName = $rel->nama ?? 'Batik';
        @endphp

        <a
            href="{{ route('detail', $rel->id) }}"
            class="motif-card"
        >

            <div class="card-image-wrap">

                <img
                    src="{{ $rel->preview_url }}"
                    alt="{{ $relName }}"
                    loading="lazy"
                >

                <span class="card-badge">
                    Batik
                </span>

            </div>

            <div class="card-body">

            <h3 class="card-title">
                {{ $rel->nama }}
            </h3>
             <p class="card-desc">
                {{ \Illuminate\Support\Str::limit($rel->deskripsi, 80) }}
            </p>
                <p class="card-price">
                    150000
                </p>

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