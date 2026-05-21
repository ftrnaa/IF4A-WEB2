@extends('layouts.admin')
@section('title', 'Produk — Admin BatikAI')
@section('breadcrumb', 'Motif & Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
@endpush

@section('content')

@php
$categories = [
    ['id'=>1,'name'=>'Klasik',      'desc'=>'Motif tradisional keraton Jawa',      'count'=>3,'color'=>'#7B5E3A'],
    ['id'=>2,'name'=>'Pesisir',     'desc'=>'Motif batik daerah pesisir utara',    'count'=>2,'color'=>'#2C4A3E'],
    ['id'=>3,'name'=>'Modern',      'desc'=>'Adaptasi motif dengan gaya kekinian', 'count'=>1,'color'=>'#C8A96E'],
    ['id'=>4,'name'=>'Kontemporer', 'desc'=>'Eksplorasi bebas berbasis AI',        'count'=>1,'color'=>'#3D6B5C'],
];

$products = [
    ['name'=>'Sido Mukti',  'cat'=>'Klasik',      'price'=>120000,'sold'=>214,'status'=>'active','img'=>'batik1'],
    ['name'=>'Parang Rusak','cat'=>'Pesisir',     'price'=>95000, 'sold'=>187,'status'=>'active','img'=>'batik2'],
    ['name'=>'Mega Mendung','cat'=>'Pesisir',     'price'=>135000,'sold'=>164,'status'=>'active','img'=>'batik3'],
    ['name'=>'Kawung',      'cat'=>'Klasik',      'price'=>110000,'sold'=>143,'status'=>'active','img'=>'batik4'],
    ['name'=>'Truntum',     'cat'=>'Modern',      'price'=>150000,'sold'=>128,'status'=>'active','img'=>'batik5'],
    ['name'=>'Sekar Jagad', 'cat'=>'Kontemporer', 'price'=>175000,'sold'=>98, 'status'=>'draft', 'img'=>'batik6'],
];

$swatches = ['#4A3728','#7B5E3A','#C8A96E','#2C4A3E','#3D6B5C','#8B7355','#5C4033','#1A6FA8','#8E44AD','#C0392B'];
@endphp

{{-- ── Page Header ── --}}
<div class="admin-page-header">
    <div>
        <h1>Motif & Produk</h1>
        <p>Tambah, edit, kelola motif dan kategori batik AI.</p>
    </div>
    <div class="produk-header-actions">
        <button class="admin-action-btn admin-action-btn--outline" onclick="openCatModal()">
            🏷 Kelola Kategori
        </button>
        <button class="admin-action-btn admin-action-btn--primary" onclick="openProductModal()">
            + Tambah Motif
        </button>
    </div>
</div>

{{-- ── Tabs ── --}}
<div class="admin-tabs">
    <button class="admin-tab-btn active" onclick="switchTab(this,'tab-motif')">🎨 Semua Motif</button>
    <button class="admin-tab-btn" onclick="switchTab(this,'tab-kategori')">🏷 Kategori</button>
</div>

{{-- ══════════════════════════════════════
     TAB: MOTIF
══════════════════════════════════════ --}}
<div class="admin-tab-panel active" id="tab-motif">

    {{-- Filter Bar --}}
    <div class="produk-filter-bar">
        <select class="admin-form-select" id="filter-cat" onchange="filterProducts()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat['name'] }}">{{ $cat['name'] }}</option>
            @endforeach
        </select>

        <select class="admin-form-select" id="filter-status" onchange="filterProducts()">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="draft">Draft</option>
        </select>

        <input type="text" class="admin-form-input" id="filter-search"
               placeholder="Cari nama motif..." oninput="filterProducts()">
    </div>

    {{-- Grid --}}
    <div class="produk-grid" id="products-grid">

        @foreach($products as $p)
        <div class="admin-card product-item"
             data-cat="{{ $p['cat'] }}"
             data-status="{{ $p['status'] }}"
             data-name="{{ strtolower($p['name']) }}">

            <div class="product-item__img-wrap">
                <img class="product-item__img"
                     src="{{ asset('images/' . $p['img'] . '.jpg') }}"
                     alt="{{ $p['name'] }}">
                <span class="status-badge status-badge--{{ $p['status'] === 'active' ? 'paid' : 'pending' }} product-item__status">
                    {{ $p['status'] === 'active' ? 'Aktif' : 'Draft' }}
                </span>
            </div>

            <div class="admin-card__body">
                <p class="product-item__cat">{{ $p['cat'] }}</p>
                <p class="product-item__name">{{ $p['name'] }}</p>
                <div class="product-item__meta">
                    <span class="product-item__price">Rp {{ number_format($p['price'],0,',','.') }}</span>
                    <span class="product-item__sold">{{ $p['sold'] }} terjual</span>
                </div>
                <div class="admin-actions-group">
                    <button class="admin-action-btn admin-action-btn--outline"
                            onclick="openProductModal('{{ $p['name'] }}','{{ $p['cat'] }}')">
                        ✏️ Edit
                    </button>
                    <button class="admin-action-btn admin-action-btn--danger"
                            onclick="confirmDelete('motif','{{ $p['name'] }}')">
                        🗑
                    </button>
                </div>
            </div>

        </div>
        @endforeach

        <p class="produk-no-results" id="no-results">
            Tidak ada motif yang cocok dengan filter.
        </p>

    </div>

</div>

{{-- ══════════════════════════════════════
     TAB: KATEGORI
══════════════════════════════════════ --}}
<div class="admin-tab-panel" id="tab-kategori">

    <div class="cat-tab-header">
        <button class="admin-action-btn admin-action-btn--primary" onclick="openCatModal()">
            + Tambah Kategori
        </button>
    </div>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Warna</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Motif</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="cat-table-body">
                    @foreach($categories as $cat)
                    <tr data-cat-id="{{ $cat['id'] }}">
                        <td>
                            <div class="cat-color-swatch"
                                 style="background:{{ $cat['color'] }}"></div>
                        </td>
                        <td>
                            <span class="cat-name">{{ $cat['name'] }}</span>
                        </td>
                        <td class="cat-desc" style="font-size:.85rem;color:var(--clr-text-muted)">
                            {{ $cat['desc'] }}
                        </td>
                        <td>
                            <span class="cat-count">{{ $cat['count'] }} motif</span>
                        </td>
                        <td>
                            <span class="status-badge status-badge--paid">Aktif</span>
                        </td>
                        <td>
                            <div class="admin-actions-group">
                                <button class="admin-action-btn admin-action-btn--outline"
                                        onclick="openCatModal({{ $cat['id'] }},'{{ $cat['name'] }}','{{ $cat['desc'] }}','{{ $cat['color'] }}')">
                                    ✏️ Edit
                                </button>
                                <button class="admin-action-btn admin-action-btn--danger"
                                        onclick="confirmDelete('kategori','{{ $cat['name'] }}')">
                                    🗑
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════
     MODAL: Tambah / Edit Motif
══════════════════════════════════════ --}}
<div class="admin-modal-overlay" id="product-modal">
    <div class="admin-modal" style="max-width:640px">
        <div class="admin-modal__header">
            <p class="admin-modal__title" id="product-modal-title">Tambah Motif Baru</p>
            <button class="admin-modal__close" onclick="closeProductModal()">✕</button>
        </div>
        <div class="admin-modal__body">
            <form class="admin-form" id="product-form">

                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="pf-name">
                            Nama Motif <span style="color:#E74C3C">*</span>
                        </label>
                        <input type="text" class="admin-form-input" id="pf-name"
                               placeholder="cth. Sido Mukti">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="pf-cat"
                               style="display:flex;align-items:center;justify-content:space-between">
                            <span>Kategori <span style="color:#E74C3C">*</span></span>
                            <button type="button" class="label-link-btn"
                                    onclick="closeProductModal(); openCatModal()">
                                + Kategori Baru
                            </button>
                        </label>
                        <select class="admin-form-select" id="pf-cat">
                            @foreach($categories as $cat)
                                <option value="{{ $cat['name'] }}">{{ $cat['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="pf-desc">
                        Deskripsi <span style="color:#E74C3C">*</span>
                    </label>
                    <textarea class="admin-form-textarea" id="pf-desc"
                              placeholder="Jelaskan asal-usul, filosofi, dan keunikan motif ini..."></textarea>
                </div>

                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="pf-price">
                            Harga (Rp) <span style="color:#E74C3C">*</span>
                        </label>
                        <input type="number" class="admin-form-input" id="pf-price"
                               placeholder="120000" min="0">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label" for="pf-origin">Asal Daerah</label>
                        <input type="text" class="admin-form-input" id="pf-origin"
                               placeholder="cth. Yogyakarta">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Unggah Gambar Motif</label>
                    <div class="admin-upload-area"
                         onclick="document.getElementById('pf-img').click()">
                        <div class="admin-upload-area__icon" id="pf-upload-icon">🖼️</div>
                        <p class="admin-upload-area__text">
                            <strong>Klik untuk unggah</strong> atau seret file ke sini
                        </p>
                        <p class="admin-upload-area__text" style="font-size:.72rem;margin-top:.3rem">
                            PNG, JPG, SVG hingga 10 MB
                        </p>
                        <input type="file" id="pf-img" accept="image/*" style="display:none"
                               onchange="previewMotifImage(this)">
                    </div>
                    <img id="pf-img-preview" class="pf-img-preview" alt="Preview motif">
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="pf-status">Status</label>
                    <select class="admin-form-select" id="pf-status">
                        <option value="active">Aktif — langsung tampil di katalog</option>
                        <option value="draft">Draft — tersimpan, tidak tampil</option>
                    </select>
                </div>

            </form>
        </div>
        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeProductModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary"
                    style="padding:.6rem 1.6rem"
                    onclick="saveProduct()">
                💾 Simpan Motif
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: Tambah / Edit Kategori
══════════════════════════════════════ --}}
<div class="admin-modal-overlay" id="cat-modal">
    <div class="admin-modal" style="max-width:480px">
        <div class="admin-modal__header">
            <p class="admin-modal__title" id="cat-modal-title">Tambah Kategori Baru</p>
            <button class="admin-modal__close" onclick="closeCatModal()">✕</button>
        </div>
        <div class="admin-modal__body">
            <form class="admin-form" id="cat-form">

                <div class="admin-form-group">
                    <label class="admin-form-label" for="cf-name">
                        Nama Kategori <span style="color:#E74C3C">*</span>
                    </label>
                    <input type="text" class="admin-form-input" id="cf-name"
                           placeholder="cth. Batik Papua"
                           oninput="updateCatPreview()">
                    <p class="admin-form-hint">Gunakan nama yang singkat dan deskriptif.</p>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label" for="cf-desc">Deskripsi</label>
                    <textarea class="admin-form-textarea" id="cf-desc"
                              placeholder="Jelaskan ciri khas dan asal motif kategori ini..."></textarea>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Warna Label</label>
                    <div class="cat-color-picker-wrap">
                        <input type="color" id="cf-color" value="#7B5E3A"
                               class="cat-color-input"
                               oninput="updateCatPreview()">
                        <div class="cat-swatches">
                            @foreach($swatches as $sw)
                            <div class="color-swatch"
                                 data-color="{{ $sw }}"
                                 style="background:{{ $sw }}"
                                 title="{{ $sw }}"
                                 onclick="pickSwatch('{{ $sw }}')"></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Preview Tampilan</label>
                    <div class="cat-preview-box">
                        <div class="cat-preview-dot" id="cat-preview-dot"></div>
                        <div>
                            <p class="cat-preview-name" id="cat-preview-name">Nama Kategori</p>
                            <p class="cat-preview-badge" id="cat-preview-badge">KATEGORI</p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeCatModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary"
                    style="padding:.6rem 1.6rem"
                    onclick="saveCategory()">
                💾 Simpan Kategori
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: Konfirmasi Hapus
══════════════════════════════════════ --}}
<div class="admin-modal-overlay" id="delete-modal">
    <div class="admin-modal" style="max-width:380px">
        <div class="admin-modal__header">
            <p class="admin-modal__title" style="color:#C0392B">🗑 Konfirmasi Hapus</p>
            <button class="admin-modal__close" onclick="closeDeleteModal()">✕</button>
        </div>
        <div class="admin-modal__body">
            <p style="font-size:.9rem;color:var(--clr-text-muted);line-height:1.65">
                Yakin ingin menghapus
                <strong id="delete-target-name" style="color:var(--clr-brown-dark)"></strong>?
                Tindakan ini tidak bisa dibatalkan.
            </p>
        </div>
        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline"
                    onclick="closeDeleteModal()">Batal</button>
            <button class="admin-action-btn"
                    style="background:#C0392B;color:#fff;border-color:#C0392B;padding:.6rem 1.4rem"
                    onclick="doDelete()">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Seed initial category data for produk.js --}}
<script>
    window.BATIK_CATEGORIES = @json($categories);
</script>
<script src="{{ asset('js/produk.js') }}"></script>
@endpush