@extends('layouts.app')
@section('title', 'Koleksi Batik Nusantara')

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
<form action="{{ route('koleksi') }}" method="GET" class="search-bar">
    <input type="text"
           name="search"
           placeholder="Cari motif batik..."
           value="{{ request('search') }}"
           autocomplete="off">
</form>

{{-- FILTER DROPDOWN --}}
<div class="filter-dropdown-wrap">
    <div class="filter-dropdown" id="filterDropdown">

        <button class="filter-dropdown-btn" id="filterDropdownBtn" type="button">
            <span class="filter-dropdown-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
            </span>
            <span class="filter-dropdown-label" id="filterDropdownLabel">
                {{ request('kategori') ? ucfirst(request('kategori')) : 'Semua Kategori' }}
            </span>
            <span class="filter-dropdown-arrow" id="filterDropdownArrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </span>
        </button>

        <div class="filter-dropdown-menu" id="filterDropdownMenu">
            <a href="{{ route('koleksi', ['search' => request('search')]) }}"
               class="filter-dropdown-item {{ !request('kategori') ? 'active' : '' }}">
                <span class="item-dot"></span>
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('koleksi', ['kategori' => $cat, 'search' => request('search')]) }}"
                   class="filter-dropdown-item {{ request('kategori') == $cat ? 'active' : '' }}">
                    <span class="item-dot"></span>
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
        </div>

    </div>
</div>

<div class="container">
    <div class="motif-grid">

        @forelse($motifs as $m)

        @php
            $slides = [];

            if (!empty($m['img'])) {
                $slides[] = $m['img'];
            }

            if (!empty($m['costume'])) {
                foreach ($m['costume'] as $c) {
                    $slides[] = $c;
                }
            }

            if (empty($slides)) {
                $slides[] = 'https://via.placeholder.com/300x230?text=BatikAI';
            }

            $name     = $m['name']        ?? 'Batik';
            $kategori = $m['kategori']    ?? 'kontemporer';
            $desc     = $m['description'] ?? '';
            $price    = $m['price']       ?? 0;
        @endphp

        <a class="motif-card"
           href="{{ route('detail', ['id' => $m['id']]) }}"
           data-kategori="{{ $kategori }}">

            {{-- IMAGE --}}
            <div class="card-image-wrap">

                @foreach($slides as $i => $src)
                    <img src="{{ $src }}"
                         class="slide-img {{ $i === 0 ? 'active' : '' }}"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                @endforeach

                <span class="card-badge">Batik</span>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                <p class="card-style">{{ $kategori }}</p>
                @if(!empty($m['code']))
                    <p class="card-code">
                        #{{ $m['code'] }}
                    </p>
                @endif
                <h3 class="card-title">{{ $name }}</h3>

                <p class="card-desc">
                    {{ $desc }}
                </p>

                <div class="card-divider"></div>

                <div class="card-footer">
                    <p class="card-price">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </p>
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

        @if($motifs->onFirstPage())
            <span class="page-btn disabled">&laquo;</span>
        @else
            <a class="page-btn" href="{{ $motifs->previousPageUrl() }}">&laquo;</a>
        @endif

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