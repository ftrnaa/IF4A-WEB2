@extends('layouts.app')

@php
$motif = (object) [
    'nama' => 'Parang Kusumo',
    'harga' => 250000,
    'thumbnail' => asset('images/batik1.jpg'),
    'galeri' => [
        asset('images/batik1.jpg'),
        asset('images/batik1.jpg'),
        asset('images/batik1.jpg'),
    ],
    'deskripsi' => 'Motif Parang Kusumo melambangkan kekuatan, kesinambungan, dan perjuangan hidup.'
];

$relatedMotifs = [
    ['nama'=>'Mega Mendung','kategori'=>'pesisir','harga'=>180000,'img'=>'images/batik2.jpg'],
    ['nama'=>'Kawung','kategori'=>'keraton','harga'=>300000,'img'=>'images/batik3.jpg'],
    ['nama'=>'Truntum','kategori'=>'klasik','harga'=>200000,'img'=>'images/batik4.jpg'],
    ['nama'=>'Sidomukti','kategori'=>'klasik','harga'=>220000,'img'=>'images/batik5.jpg'],
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
.section-box h3 {
    font-size:15px;
    margin-bottom:10px;
}

/* GRID CARD KOLEKSI STYLE */
.motif-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:24px;
}

/* CARD */
.motif-card {
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:.3s;
}
.motif-card:hover {
    transform:translateY(-6px);
}

.card-image-wrap {
    position:relative;
    height:200px;
}
.card-image-wrap img {
    width:100%;
    height:100%;
    object-fit:cover;
}

/* OVERLAY */
.card-overlay {
    position:absolute;
    inset:0;
    background:linear-gradient(to top, rgba(0,0,0,.6), transparent);
    opacity:0;
    display:flex;
    align-items:flex-end;
    padding:12px;
    transition:.3s;
}
.motif-card:hover .card-overlay { opacity:1; }

.overlay-btn {
    background:#C9A84C;
    border:none;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
}

/* BADGE */
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

/* BODY */
.card-body {
    padding:14px;
}
.card-title {
    font-family:'Playfair Display';
    font-size:15px;
}
.card-price {
    color:#5C3D1E;
}

/* SPACING */
.related-section {
    margin-top:70px;
    padding-bottom:100px;
}

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

            <div class="section-box">
                <h3>Deskripsi Motif</h3>
                <p>{{ $motif->deskripsi }}</p>
            </div>

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
                <p>Boleh digunakan untuk kebutuhan komersial tanpa batas.</p>
            </div>
        </div>

    </div>
</div>

{{-- RELATED --}}
<div class="container related-section">

    <h2 style="font-family:Playfair Display; margin-bottom:20px;">
        Motif <span style="color:#C9A84C;">Serupa</span>
    </h2>

    <div class="motif-grid">
        @foreach($relatedMotifs as $rel)
        <div class="motif-card">
            <div class="card-image-wrap">
                <img src="{{ asset($rel['img']) }}">
                <div class="card-overlay">
                    <button class="overlay-btn">Lihat Detail</button>
                </div>
                <span class="card-badge">{{ ucfirst($rel['kategori']) }}</span>
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $rel['nama'] }}</h3>
                <p class="card-price">
                    Rp {{ number_format($rel['harga'],0,',','.') }}
                </p>
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