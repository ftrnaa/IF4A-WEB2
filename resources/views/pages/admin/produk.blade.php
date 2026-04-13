@extends('layouts.admin')
@section('title', 'Produk — Admin BatikAI')
@section('breadcrumb', 'Motif & Produk')

@section('content')

<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Motif & Produk</h1>
        <p>Tambah, edit, dan kelola semua motif batik AI.</p>
    </div>
    <button class="admin-action-btn admin-action-btn--primary" style="padding:.7rem 1.4rem;font-size:.88rem" onclick="openProductModal()">
        + Tambah Motif
    </button>
</div>

{{-- Product Grid --}}
@php
$products = [
    ['name'=>'Sido Mukti','cat'=>'Klasik','price'=>120000,'sold'=>214,'status'=>'active','img'=>'batik1'],
    ['name'=>'Parang Rusak','cat'=>'Pesisir','price'=>95000,'sold'=>187,'status'=>'active','img'=>'batik2'],
    ['name'=>'Mega Mendung','cat'=>'Pesisir','price'=>135000,'sold'=>164,'status'=>'active','img'=>'batik3'],
    ['name'=>'Kawung','cat'=>'Klasik','price'=>110000,'sold'=>143,'status'=>'active','img'=>'batik4'],
    ['name'=>'Truntum','cat'=>'Modern','price'=>150000,'sold'=>128,'status'=>'active','img'=>'batik5'],
    ['name'=>'Sekar Jagad','cat'=>'Kontemporer','price'=>175000,'sold'=>98,'status'=>'draft','img'=>'batik6'],
];
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem">
    @foreach($products as $p)
    <div class="admin-card" style="overflow:visible">
        <div style="position:relative">
            <img src="https://picsum.photos/seed/{{ $p['img'] }}/600/400" alt="{{ $p['name'] }}"
                 style="width:100%;height:180px;object-fit:cover;border-radius:var(--radius-md) var(--radius-md) 0 0;display:block">
            <span class="status-badge status-badge--{{ $p['status'] === 'active' ? 'paid' : 'pending' }}"
                  style="position:absolute;top:.75rem;right:.75rem">
                {{ $p['status'] === 'active' ? 'Aktif' : 'Draft' }}
            </span>
        </div>
        <div class="admin-card__body">
            <p style="font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--clr-gold);margin-bottom:.3rem">{{ $p['cat'] }}</p>
            <p style="font-family:var(--font-display);font-size:1.05rem;font-weight:700;color:var(--clr-brown-dark);margin-bottom:.5rem">{{ $p['name'] }}</p>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                <span style="font-weight:600;color:var(--clr-green)">Rp {{ number_format($p['price'],0,',','.') }}</span>
                <span style="font-size:.78rem;color:var(--clr-text-muted)">{{ $p['sold'] }} terjual</span>
            </div>
            <div class="admin-actions-group">
                <button class="admin-action-btn admin-action-btn--outline" onclick="openProductModal('{{ $p['name'] }}')">✏️ Edit</button>
                <button class="admin-action-btn admin-action-btn--danger">🗑</button>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Modal: Tambah / Edit Produk ── --}}
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
                        <label class="admin-form-label">Nama Motif <span>*</span></label>
                        <input type="text" class="admin-form-input" id="pf-name" placeholder="cth. Sido Mukti">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Kategori <span>*</span></label>
                        <select class="admin-form-select" id="pf-cat">
                            <option>Klasik</option>
                            <option>Pesisir</option>
                            <option>Modern</option>
                            <option>Kontemporer</option>
                        </select>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Deskripsi <span>*</span></label>
                    <textarea class="admin-form-textarea" id="pf-desc" placeholder="Jelaskan asal-usul, filosofi, dan keunikan motif ini..."></textarea>
                </div>

                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Harga (Rp) <span>*</span></label>
                        <input type="number" class="admin-form-input" id="pf-price" placeholder="120000">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Asal Daerah</label>
                        <input type="text" class="admin-form-input" id="pf-origin" placeholder="cth. Yogyakarta">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Unggah Gambar Motif</label>
                    <div class="admin-upload-area" onclick="document.getElementById('pf-img').click()">
                        <div class="admin-upload-area__icon">🖼️</div>
                        <p class="admin-upload-area__text"><strong>Klik untuk unggah</strong> atau seret file ke sini</p>
                        <p class="admin-upload-area__text" style="font-size:.72rem;margin-top:.3rem">PNG, JPG, SVG hingga 10 MB</p>
                        <input type="file" id="pf-img" accept="image/*" style="display:none">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Status</label>
                    <select class="admin-form-select" id="pf-status">
                        <option value="active">Aktif (langsung tampil)</option>
                        <option value="draft">Draft (tersimpan, tidak tampil)</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="admin-modal__footer">
            <button class="admin-action-btn admin-action-btn--outline" onclick="closeProductModal()">Batal</button>
            <button class="admin-action-btn admin-action-btn--primary" style="padding:.6rem 1.4rem">Simpan Motif</button>
        </div>
    </div>
</div>

@endsection