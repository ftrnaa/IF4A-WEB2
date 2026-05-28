@extends('layouts.admin')
@section('title', 'Produk — Admin BatikAI')
@section('breadcrumb', 'Motif & Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/produk.css') }}">
@endpush

@section('content')

<div class="admin-page-header">
    <div>
        <h1>Motif & Produk</h1>
        <p>Kelola motif batik AI dan kategori.</p>
    </div>
</div>

{{-- TAB --}}
<div class="admin-tabs">
    <button class="admin-tab-btn active" onclick="switchTab(this,'tab-motif')">🎨 Motif</button>
</div>

{{-- ===================== MOTIF ===================== --}}
<div class="admin-tab-panel active" id="tab-motif">

    {{-- TOOLBAR --}}
    <div class="produk-toolbar">

        {{-- SEARCH --}}
        <div class="produk-search-wrap">
            <span class="produk-search-icon">🔍</span>
            <input type="text"
                   id="filter-search"
                   class="produk-search-input"
                   placeholder="Cari motif batik..."
                   oninput="filterProducts()">
            <button class="produk-search-clear"
                    id="search-clear-btn"
                    onclick="clearSearch()">✕</button>
        </div>

        {{-- CATEGORY DROPDOWN (styled same as koleksi) --}}
        <div class="produk-filter-dropdown-wrap">
            <div class="produk-filter-dropdown" id="produkFilterDropdown">

                <button class="produk-filter-dropdown-btn" id="produkFilterBtn" type="button">
                    <span class="produk-filter-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                        </svg>
                    </span>
                    <span class="produk-filter-label" id="produkFilterLabel">Semua Kategori</span>
                    <span class="produk-filter-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </span>
                </button>

                <div class="produk-filter-menu" id="produkFilterMenu">
                    <button class="produk-filter-item active"
                            onclick="selectCategory('', 'Semua Kategori', this)"
                            type="button">
                        <span class="produk-item-dot"></span>
                        Semua Kategori
                    </button>
                    @foreach($categories as $cat)
                    <button class="produk-filter-item"
                            onclick="selectCategory('{{ strtolower($cat['name']) }}', '{{ ucfirst($cat['name']) }}', this)"
                            type="button">
                        <span class="produk-item-dot"></span>
                        {{ ucfirst($cat['name']) }}
                    </button>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    {{-- COUNTER --}}
    <div class="produk-counter-info">
        Menampilkan <strong id="produkCountVisible">{{ count($products) }}</strong> dari
        <strong>{{ count($products) }}</strong> motif batik
    </div>

    {{-- GRID --}}
    <div class="produk-grid" id="produk-grid">

        @foreach($products as $p)

        @php
            $slides = [];
            if (!empty($p['img'])) $slides[] = $p['img'];
            if (!empty($p['costume'])) {
                foreach ($p['costume'] as $c) $slides[] = $c;
            }
            if (empty($slides)) $slides[] = 'https://via.placeholder.com/400x300?text=BatikAI';

            $name     = $p['name']        ?? 'Batik';
            $kategori = $p['kategori']    ?? 'kontemporer';
            $desc     = $p['description'] ?? '';
            $price    = $p['price']       ?? 0;
            $code     = $p['code']        ?? '';
        @endphp

        <div class="motif-card product-item"
             data-cat="{{ strtolower($kategori) }}"
             data-name="{{ strtolower($name) }}"
             data-anim-index="{{ $loop->index }}">

            {{-- IMAGE SLIDESHOW --}}
            <div class="card-image-wrap"
                 onclick="openImageModal({{ $loop->index }})"
                 style="cursor:zoom-in">

                @foreach($slides as $i => $src)
                    <img src="{{ $src }}"
                         class="slide-img {{ $i === 0 ? 'active' : '' }}"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                         onerror="this.src='https://via.placeholder.com/400x300?text=BatikAI'">
                @endforeach

                <span class="card-badge">Batik</span>

                {{-- Zoom hint icon --}}
                <span class="card-zoom-hint">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        <line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
                    </svg>
                </span>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                <p class="card-style">{{ $kategori }}</p>

                @if(!empty($code))
                    <p class="card-code">#{{ $code }}</p>
                @endif

                <h3 class="card-title">{{ $name }}</h3>

                <p class="card-desc" id="desc-display-{{ $loop->index }}">{{ $desc }}</p>

                <div class="card-divider"></div>

                <div class="card-footer">
                    <p class="card-price">Rp {{ number_format($price, 0, ',', '.') }}</p>

                    <div class="card-actions">
                        <button class="card-action-btn card-action-edit"
                            title="Edit Deskripsi"
                            onclick='openProductModal(
                                @json($name),
                                @json($kategori),
                                @json($price),
                                @json($desc),
                                {{ $loop->index }}
                            )'>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button class="card-action-btn card-action-delete"
                            title="Hapus"
                            onclick='confirmDelete("motif", @json($name))'>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        @endforeach

        <div class="produk-no-results" id="no-results">
            Tidak ada motif yang ditemukan.
        </div>

    </div>

</div>

{{-- ===================== KATEGORI ===================== --}}
<div class="admin-tab-panel" id="tab-kategori">

    <div class="cat-tab-header">
        <button class="admin-action-btn admin-action-btn--primary"
                onclick="openCatModal()">
            + Tambah Kategori
        </button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="cat-table-body">
            @foreach($categories as $cat)
            <tr>
                <td><span class="cat-name">{{ $cat['name'] }}</span></td>
                <td>{{ $cat['desc'] }}</td>
                <td class="cat-count">{{ $cat['count'] }}</td>
                <td>
                    <button class="admin-action-btn admin-action-btn--outline"
                        onclick='openCatModal({{ $cat["id"] }}, @json($cat["name"]), @json($cat["desc"]))'>
                        ✏️ Edit
                    </button>
                    <button class="admin-action-btn admin-action-btn--danger"
                        onclick='confirmDelete("kategori", @json($cat["name"]))'>
                        🗑
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- ===================== MODAL IMAGE LIGHTBOX ===================== --}}
<div class="admin-modal-overlay" id="image-modal" onclick="closeImageModal()">
    <div class="image-modal-inner" onclick="event.stopPropagation()">

        <button class="image-modal-close" onclick="closeImageModal()">✕</button>

        <div class="image-modal-slideshow" id="imageModalSlideshow">
            {{-- slides injected by JS --}}
        </div>

        <div class="image-modal-info">
            <p class="image-modal-name" id="imageModalName"></p>
            <p class="image-modal-cat" id="imageModalCat"></p>
        </div>

        <div class="image-modal-nav">
            <button class="image-modal-prev" id="imageModalPrev" onclick="imageModalNav(-1)">&#8592;</button>
            <div class="image-modal-dots" id="imageModalDots"></div>
            <button class="image-modal-next" id="imageModalNext" onclick="imageModalNav(1)">&#8594;</button>
        </div>

    </div>
</div>

{{-- ===================== MODAL EDIT DESKRIPSI ===================== --}}
<div class="admin-modal-overlay" id="product-modal">
    <div class="admin-modal" style="max-width:520px">

        <div class="admin-modal__header">
            <p class="admin-modal__title" id="product-modal-title">Edit Motif</p>
            <button class="admin-modal__close" onclick="closeProductModal()">✕</button>
        </div>

        <div class="admin-modal__body">

            <div class="admin-form-group">
                <label>Nama Motif</label>
                <input type="text" id="pf-name" class="admin-form-input" disabled>
            </div>

            <div class="admin-form-group">
                <label>Kategori</label>
                <input type="text" id="pf-cat" class="admin-form-input" disabled>
            </div>

            <div class="admin-form-group">
                <label>Harga</label>
                <input type="text" id="pf-price" class="admin-form-input" disabled>
            </div>

            <div class="admin-form-group">
                <label>Deskripsi <span class="label-editable-badge">✏️ Dapat diedit</span></label>
                <textarea id="pf-desc" class="admin-form-textarea" rows="5"></textarea>
            </div>

            <input type="hidden" id="pf-card-index">

        </div>

        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeProductModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary"
                    onclick="saveProduct()">Simpan</button>
        </div>

    </div>
</div>

{{-- ===================== MODAL KATEGORI ===================== --}}
<div class="admin-modal-overlay" id="cat-modal">
    <div class="admin-modal">

        <div class="admin-modal__header">
            <p class="admin-modal__title">Kategori</p>
            <button class="admin-modal__close" onclick="closeCatModal()">✕</button>
        </div>

        <div class="admin-modal__body">

            <div class="admin-form-group">
                <label>Nama</label>
                <input type="text" id="cf-name" class="admin-form-input">
            </div>

            <div class="admin-form-group">
                <label>Deskripsi</label>
                <textarea id="cf-desc" class="admin-form-textarea"></textarea>
            </div>

        </div>

        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeCatModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary"
                    onclick="saveCategory()">Simpan</button>
        </div>

    </div>
</div>

{{-- ===================== DELETE MODAL ===================== --}}
<div class="admin-modal-overlay" id="delete-modal">
    <div class="admin-modal" style="max-width:360px">

        <div class="admin-modal__header">
            <p class="admin-modal__title">Hapus?</p>
            <button class="admin-modal__close" onclick="closeDeleteModal()">✕</button>
        </div>

        <div class="admin-modal__body">
            Yakin ingin menghapus <strong id="delete-target-name"></strong>?
        </div>

        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeDeleteModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--danger"
                    onclick="doDelete()">Hapus</button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    window.BATIK_CATEGORIES = @json($categories);

    // Build product data for image modal
    window.PRODUK_DATA = [
        @foreach($products as $p)
        @php
            $slides = [];
            if (!empty($p['img'])) $slides[] = $p['img'];
            if (!empty($p['costume'])) foreach ($p['costume'] as $c) $slides[] = $c;
            if (empty($slides)) $slides[] = 'https://via.placeholder.com/400x300?text=BatikAI';
        @endphp
        {
            name: @json($p['name'] ?? ''),
            kategori: @json($p['kategori'] ?? ''),
            slides: @json($slides),
        },
        @endforeach
    ];
</script>
<script src="{{ asset('js/produk.js') }}"></script>
@endpush