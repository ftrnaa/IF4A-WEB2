@extends('layouts.app')

@php
$motif = (object) [
    'nama' => 'Parang Kusumo',
    'harga' => 200000,
    'thumbnail' => asset('images/batik1.jpg'),
    'galeri' => [
        asset('images/batik1.jpg'),
        asset('images/batik1.jpg'),
        asset('images/batik1.jpg'),
    ],
    'deskripsi' => 'Motif Parang Kusumo melambangkan kekuatan, kesinambungan, dan perjuangan hidup.'
];

$relatedMotifs = [
    ['nama'=>'Mega Mendung','kategori'=>'pesisir','harga'=>200000,'img'=>'images/batik2.jpg'],
    ['nama'=>'Kawung','kategori'=>'keraton','harga'=>200000,'img'=>'images/batik3.jpg'],
    ['nama'=>'Truntum','kategori'=>'klasik','harga'=>200000,'img'=>'images/batik4.jpg'],
    ['nama'=>'Sidomukti','kategori'=>'klasik','harga'=>200000,'img'=>'images/batik5.jpg'],
];

/* ✅ LINK PRODUK (tanpa card) */
$linkProduk = [
    ['nama' => 'Kemeja Batik Parang Premium', 'url' => '#'],
    ['nama' => 'Dress Batik Elegan', 'url' => '#'],
    ['nama' => 'Tas Batik Handmade', 'url' => '#'],
];
@endphp

@section('title', $motif->nama)

@push('styles')
<style>
body { background:#F5F0E8; font-family:'DM Sans'; }

/* LAYOUT */
.detail-layout {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:60px;
    padding:50px 0;
}

/* IMAGE */
.main-image-wrap {
    border-radius:20px;
    overflow:hidden;
}
.main-image-wrap img {
    width:100%;
    transition:.4s;
}
.main-image-wrap:hover img {
    transform:scale(1.05);
}

/* THUMB */
.thumb-row {
    display:flex;
    gap:10px;
    margin-top:10px;
}
.thumb {
    flex:1;
    cursor:pointer;
    border:2px solid transparent;
}
.thumb.active { border-color:#C9A84C; }

/* TITLE */
.motif-nama {
    font-family:'Playfair Display';
    font-size:36px;
}
.motif-harga {
    color:#C9A84C;
    font-size:24px;
    margin:10px 0 20px;
}

/* BUTTON */
.btn-beli {
    display:block;
    padding:14px;
    background:#2C1A0E;
    color:#fff;
    text-align:center;
    border-radius:12px;
    text-decoration:none;
    margin-bottom:10px;
}

/* SECTION */
.section-box {
    background:#fff;
    padding:20px;
    border-radius:12px;
    margin-top:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

/* LINK PRODUK */
.link-produk-list {
    margin-top:10px;
}
.link-item {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 12px;
    border-bottom:1px solid #eee;
    text-decoration:none;
    color:#2C1A0E;
    transition:.2s;
}
.link-item:hover {
    background:#f9f6f1;
}
.link-item span {
    font-size:13px;
    color:#888;
}

/* GRID CARD */
.motif-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:24px;
}

.motif-card {
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:.3s;
}
.motif-card:hover { transform:translateY(-6px); }

.card-image-wrap {
    position:relative;
    height:200px;
}
.card-image-wrap img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.card-badge {
    position:absolute;
    top:10px;
    left:10px;
    background:#C9A84C;
    color:#fff;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
}

.card-body { padding:14px; }

@media(max-width:900px){
    .detail-layout{grid-template-columns:1fr;}
    .motif-grid{grid-template-columns:repeat(2,1fr);}
}
</style>
@endpush

@section('content')

<div class="container">
    <div class="detail-layout">

        {{-- IMAGE --}}
        <div>
            <div class="main-image-wrap">
                <img id="mainImage" src="{{ $motif->thumbnail }}">
            </div>

            <div class="thumb-row">
                @foreach($motif->galeri as $img)
                <div class="thumb" onclick="switchImage('{{ $img }}', this)">
                    <img src="{{ $img }}">
                </div>
                @endforeach
            </div>
        </div>

        {{-- INFO --}}
        <div>
            <h1 class="motif-nama">{{ $motif->nama }}</h1>
            <p class="motif-harga">Rp {{ number_format($motif->harga,0,',','.') }}</p>

            <a href="{{ route('checkout') }}" class="btn-beli">BELI SEKARANG</a>

            {{-- DESKRIPSI --}}
            <div class="section-box">
                <h3>Deskripsi Motif</h3>
                <p>{{ $motif->deskripsi }}</p>
            </div>

            {{-- ✅ LINK PRODUK --}}
            <div class="section-box">
                <h3>Digunakan pada Produk</h3>

                <div class="link-produk-list">
                    @foreach($linkProduk as $item)
                    <a href="{{ $item['url'] }}" target="_blank" class="link-item">
                        {{ $item['nama'] }}
                        <span>→ lihat</span>
                    </a>
                    @endforeach
                </div>

                <small style="color:#888;">
                    Produk dibuat oleh pembeli lisensi motif ini
                </small>
            </div>

            {{-- INFO TAMBAHAN --}}
            <div class="section-box">
                <h3>Apa yang Anda Dapatkan</h3>
                <ul>
                    <li>File SVG & PNG HD</li>
                    <li>Resolusi tinggi siap cetak</li>
                    <li>Akses download instan</li>
                </ul>
            </div>

            <div class="section-box">
                <h3>Lisensi</h3>
                <p>Lisensi tidak dapat dialihkan, dibagikan, atau dijual kembali dalam bentuk apa pun,
        termasuk sebagai file digital.</p>
            </div>
        </div>

    </div>
</div>

{{-- RELATED --}}
<div class="container" style="margin-top:70px; padding-bottom:100px;">
    <h2 style="font-family:Playfair Display; margin-bottom:20px;">
        Motif <span style="color:#C9A84C;">Serupa</span>
    </h2>

    <div class="motif-grid">
        @foreach($relatedMotifs as $rel)
        <div class="motif-card">
            <div class="card-image-wrap">
                <img src="{{ asset($rel['img']) }}">
                <span class="card-badge">{{ ucfirst($rel['kategori']) }}</span>
            </div>
            <div class="card-body">
                <h3>{{ $rel['nama'] }}</h3>
                <p>Rp {{ number_format($rel['harga'],0,',','.') }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
function switchImage(src, el){
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active'));
    el.classList.add('active');
}
</script>
@endpush