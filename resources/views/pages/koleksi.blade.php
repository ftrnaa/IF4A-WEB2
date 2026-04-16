@extends('layouts.app')

@section('title', 'Koleksi Batik Nusantara')

@php
use Illuminate\Support\Str;

$motifs = [
    ['nama'=>'Parang Kusumo','kategori'=>'klasik','harga'=>250000,'img'=>'images/batik1.jpg'],
    ['nama'=>'Mega Mendung','kategori'=>'pesisir','harga'=>180000,'img'=>'images/batik2.jpg'],
    ['nama'=>'Kawung','kategori'=>'keraton','harga'=>300000,'img'=>'images/batik3.jpg'],
    ['nama'=>'Batik Kontemporer','kategori'=>'modern','harga'=>150000,'img'=>'images/batik4.jpg'],
    ['nama'=>'Sidomukti','kategori'=>'klasik','harga'=>220000,'img'=>'images/batik5.jpg'],
    ['nama'=>'Truntum','kategori'=>'keraton','harga'=>200000,'img'=>'images/batik6.jpg'],
    ['nama'=>'Lasem','kategori'=>'pesisir','harga'=>190000,'img'=>'images/batik7.jpg'],
    ['nama'=>'Batik Abstrak','kategori'=>'modern','harga'=>170000,'img'=>'images/batik8.jpg'],

    // ✅ TAMBAHAN BIAR JADI 12
    ['nama'=>'Sekar Jagad','kategori'=>'klasik','harga'=>210000,'img'=>'images/batik1.jpg'],
    ['nama'=>'Batik Bali','kategori'=>'modern','harga'=>260000,'img'=>'images/batik2.jpg'],
    ['nama'=>'Sido Asih','kategori'=>'keraton','harga'=>230000,'img'=>'images/batik3.jpg'],
    ['nama'=>'Batik Garutan','kategori'=>'pesisir','harga'=>195000,'img'=>'images/batik4.jpg'],
];
@endphp

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --cream:#F5F0E8;
    --gold:#C9A84C;
}

body {
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
}

/* HEADER */
.koleksi-header {
    padding: 60px 0 10px;
    text-align: center;
}
.koleksi-header h1 {
    font-family: 'Playfair Display';
    font-size: 44px;
}
.koleksi-header em { color: var(--gold); }

/* COUNTER */
.koleksi-info {
    text-align:center;
    margin-bottom: 20px;
    font-size: 14px;
    color:#666;
}

/* SEARCH */
.search-bar {
    display:flex;
    justify-content:center;
    margin-bottom: 20px;
}
.search-bar input {
    width: 60%;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

/* FILTER */
.filter-bar {
    background:#fff;
    padding:12px 0;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}
.filter-tabs {
    display:flex;
    gap:10px;
    justify-content:center;
}
.filter-tab {
    padding:10px 16px;
    border:none;
    cursor:pointer;
    background:transparent;
    border-radius:8px;
}
.filter-tab.active {
    background:var(--gold);
    color:#fff;
}

/* GRID */
.motif-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:22px;
    margin-top:30px;
    padding-bottom:80px;
}

/* CARD */
.motif-card {
    display:block;
    text-decoration:none;
    color:inherit;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:.3s;
    opacity:0;
    transform:translateY(20px);
}

.motif-card.show {
    opacity:1;
    transform:translateY(0);
}

.motif-card:hover {
    transform:translateY(-6px);
}

/* IMAGE */
.card-image-wrap {
    height:220px;
    position:relative;
}
.card-image-wrap img {
    width:100%;
    height:100%;
    object-fit:cover;
}

/* BADGE */
.card-badge {
    position:absolute;
    top:10px;
    left:10px;
    background:var(--gold);
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
    font-size:16px;
}
.card-price {
    color:var(--gold);
    font-weight:600;
}
</style>
@endpush

@section('content')

<section class="koleksi-header">
    <h1>Motif <em>Terkurasi</em><br>Nusantara</h1>
</section>

{{-- ✅ COUNTER (RESTORED) --}}
<div class="koleksi-info">
    Menampilkan <strong id="countVisible">{{ count($motifs) }}</strong> dari
    <strong>{{ count($motifs) }}</strong> motif batik
</div>

{{-- SEARCH --}}
<div class="search-bar">
    <input type="text" id="searchInput" placeholder="Cari motif batik...">
</div>

{{-- FILTER --}}
<div class="filter-bar">
    <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterKategori('semua', this)">Semua</button>
        <button class="filter-tab" onclick="filterKategori('klasik', this)">Klasik</button>
        <button class="filter-tab" onclick="filterKategori('modern', this)">Modern</button>
        <button class="filter-tab" onclick="filterKategori('keraton', this)">Keraton</button>
        <button class="filter-tab" onclick="filterKategori('pesisir', this)">Pesisir</button>
    </div>
</div>

<div class="container">
    <div class="motif-grid">

        @foreach($motifs as $m)
        <a href="{{ route('motif.detail', Str::slug($m['nama'])) }}"
           class="motif-card"
           data-kategori="{{ $m['kategori'] }}"
           data-nama="{{ strtolower($m['nama']) }}">

            <div class="card-image-wrap">
                <img src="{{ asset($m['img']) }}">
                <span class="card-badge">{{ ucfirst($m['kategori']) }}</span>
            </div>

            <div class="card-body">
                <h3 class="card-title">{{ $m['nama'] }}</h3>
                <p class="card-price">Rp {{ number_format($m['harga'],0,',','.') }}</p>
            </div>

        </a>
        @endforeach

    </div>
</div>

@endsection

@push('scripts')
<script>

const cards = document.querySelectorAll('.motif-card');

const counter = document.getElementById('countVisible');

// animation
const observer = new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
        if(entry.isIntersecting){
            entry.target.classList.add('show');
        }
    });
});

cards.forEach(c=>observer.observe(c));


// UPDATE COUNTER
function updateCounter(){
    let visible = 0;
    document.querySelectorAll('.motif-card').forEach(card=>{
        if(card.style.display !== 'none'){
            visible++;
        }
    });
    counter.innerText = visible;
}


// FILTER
function filterKategori(kategori, el){
    document.querySelectorAll('.filter-tab').forEach(b=>b.classList.remove('active'));
    el.classList.add('active');

    document.querySelectorAll('.motif-card').forEach(card=>{
        const kat = card.dataset.kategori;

        if(kategori === 'semua' || kat === kategori){
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    updateCounter();
}


// SEARCH
document.getElementById('searchInput').addEventListener('keyup', function(){
    let keyword = this.value.toLowerCase();

    document.querySelectorAll('.motif-card').forEach(card=>{
        let nama = card.dataset.nama;

        if(nama.includes(keyword)){
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    updateCounter();
});

</script>
@endpush