@extends('layouts.app')
@section('title', 'Koleksi Batik Nusantara')
@php
use Illuminate\Support\Str;


function getNameFromKeyword(string $keyword): string {
    $parts = explode(',', $keyword);
    // index 2 = setelah koma kedua; fallback ke index 0 jika tidak ada
    $name = isset($parts[2]) ? trim($parts[2]) : trim($parts[0]);
    return Str::limit(ucfirst($name), 45);
}


function getCodeFromFile(string $file): string {
    preg_match('/^(\d{4})/', $file, $m);
    return $m[1] ?? '----';
}

function formatTanggal(string $date): string {
    try {
        return \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
    } catch (\Exception $e) {
        return $date;
    }
}
@endphp
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/koleksi.css') }}">
@endpush
@section('content')

<section class="koleksi-header">
    <h1>Motif <em>Terkurasi</em><br>Nusantara</h1>
</section>

<div class="koleksi-info">
    Menampilkan <strong id="countVisible">{{ $motifs->count() }}</strong> dari
    <strong>{{ $motifs->total() }}</strong> motif batik
</div>

{{-- SEARCH --}}
<form action="{{ route('koleksi') }}" method="GET" class="search-bar" id="searchForm">
    <input
        type="text"
        name="search"
        id="searchInput"
        placeholder="Cari motif batik..."
        value="{{ request('search') }}"
        autocomplete="off"
    >
</form>

{{-- FILTER --}}
<div class="filter-tabs">

    {{-- SEMUA --}}
    <a
        href="{{ route('koleksi', ['search' => request('search')]) }}"
        class="filter-tab {{ !request('kategori') ? 'active' : '' }}"
    >
        Semua
    </a>

    {{-- AUTO KATEGORI --}}
    @foreach($categories as $cat)

        <a
            href="{{ route('koleksi', [
                'kategori' => $cat,
                'search' => request('search')
            ]) }}"
            class="filter-tab {{ request('kategori') == $cat ? 'active' : '' }}"
        >
            {{ ucfirst($cat) }}
        </a>

    @endforeach

</div>

<div class="container">
    <div class="motif-grid" id="motifGrid">
        @forelse($motifs as $m)
        @php
            $keyword  = $m['keyword'] ?? '';
            $parts    = explode(',', $keyword);
            // Style: bagian pertama keyword (sebelum koma pertama)
            $styleName = Str::limit(trim($parts[0] ?? ''), 35);
            // Nama: bagian setelah koma kedua
            $cardName  = isset($parts[2]) ? Str::limit(ucfirst(trim($parts[2])), 45)
                                          : Str::limit(ucfirst(trim($parts[0] ?? 'Batik')), 45);
            // Kode 4 digit
            $fileCode  = '';
            if (!empty($m['file_preview'])) {
                preg_match('/^(\d{4})/', $m['file_preview'], $mc);
                $fileCode = $mc[1] ?? '----';
            }
            // Tanggal
            $tgl = '';
            if (!empty($m['created_at'])) {
                try {
                    $tgl = \Carbon\Carbon::parse($m['created_at'])->format('d M Y');
                } catch (\Exception $e) {
                    $tgl = $m['created_at'];
                }
            }
        @endphp
        <a class="motif-card" href="{{ route('detail', ['id' => $m['id']]) }}?q={{ urlencode($keyword) }}" data-kategori="{{ $m['kategori'] ?? 'semua' }}">

            {{-- IMAGE SLIDESHOW --}}
            @php
                $baseUrl   = 'http://btx.agunghakase.my.id/api/image/';
                $slides    = [];
                if (!empty($m['file_preview']))  $slides[] = $baseUrl . $m['file_preview'];
                // file_costume adalah JSON string array
                if (!empty($m['file_costume'])) {
                    $costumes = is_array($m['file_costume'])
                        ? $m['file_costume']
                        : json_decode($m['file_costume'], true) ?? [];
                    foreach ($costumes as $c) $slides[] = $baseUrl . $c;
                }
                if (empty($slides)) $slides[] = 'https://via.placeholder.com/300x230?text=No+Image';
            @endphp
            <div class="card-image-wrap" data-slides="{{ json_encode($slides) }}">
                {{-- Semua slide, hanya slide pertama visible --}}
                @foreach($slides as $i => $src)
                    <img src="{{ $src }}"
                         alt="{{ $cardName }}"
                         class="slide-img{{ $i === 0 ? ' active' : '' }}"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                @endforeach

                {{-- Dot indicators (tampil kalau > 1 slide) --}}
                @if(count($slides) > 1)
                <div class="slide-dots">
                    @foreach($slides as $i => $src)
                        <span class="dot{{ $i === 0 ? ' active' : '' }}"></span>
                    @endforeach
                </div>
                @endif

                {{-- Kode 4 digit pojok kanan atas --}}
                @if($fileCode)
                    <span class="card-code">#{{ $fileCode }}</span>
                @endif

                {{-- Badge kiri bawah --}}
                <span class="card-badge">Batik</span>
            </div>

            {{-- BODY --}}
            <div class="card-body">
                {{-- Style (baris kecil atas) --}}
                @if($styleName)
                    <p class="card-style">{{ $styleName }}</p>
                @endif

                {{-- Nama (setelah koma kedua) --}}
                <h3 class="card-title">{{ $cardName }}</h3>

                <div class="card-divider"></div>

                {{-- Harga & Tanggal --}}
                <div class="card-footer">
                    <p class="card-price">Rp {{ number_format(100000, 0, ',', '.') }}</p>
                    @if($tgl)
                        <span class="card-date">{{ $tgl }}</span>
                    @endif
                </div>
            </div>

        </a>
        @empty
        <div class="empty-state">
            <span>😢</span>
            <p>Tidak ditemukan motif batik yang sesuai.</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($motifs->hasPages())
    <div class="pagination-wrap">
        {{-- Prev --}}
        @if($motifs->onFirstPage())
            <span class="page-btn disabled">&laquo;</span>
        @else
            <a class="page-btn" href="{{ $motifs->previousPageUrl() }}">&laquo;</a>
        @endif

        {{-- Page Numbers --}}
        @foreach($motifs->getUrlRange(1, $motifs->lastPage()) as $page => $url)
            @if($page == $motifs->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @elseif(
                $page == 1 ||
                $page == $motifs->lastPage() ||
                abs($page - $motifs->currentPage()) <= 2
            )
                <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
            @elseif(abs($page - $motifs->currentPage()) == 3)
                <span class="page-btn dots">…</span>
            @endif
        @endforeach

        {{-- Next --}}
        @if($motifs->hasMorePages())
            <a class="page-btn" href="{{ $motifs->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="page-btn disabled">&raquo;</span>
        @endif
    </div>
    @endif
</div>

@endsection
@push('scripts')
    <script src="{{ asset('js/koleksi.js') }}"></script>
@endpush