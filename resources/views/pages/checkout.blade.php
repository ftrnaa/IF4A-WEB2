@extends('layouts.app')

@section('title', 'Checkout — BatikAI')

@php
$motif = (object)[
    'id' => 1,
    'nama' => 'Parang Kusumo',
    'kategori' => 'Klasik',
    'harga' => 250000,
    'thumbnail' => 'images/batik1.jpg'
];

$diskon = 0;
$pajak = 0;
$total = $motif->harga - $diskon + $pajak;
@endphp

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

body {
    background:#F5F0E8;
    font-family:'DM Sans', sans-serif;
}

/* HEADER */
.checkout-header {
    padding:50px 0 30px;
}
.checkout-header h1 {
    font-family:'Playfair Display';
    font-size:44px;
}
.checkout-header span { color:#C9A84C; }

/* LAYOUT */
.checkout-layout {
    display:grid;
    grid-template-columns:1fr 360px;
    gap:30px;
}

/* CARD */
.card-box {
    background:#fffdf8;
    border-radius:16px;
    padding:24px;
    margin-bottom:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.06);
}

/* ITEM */
.item {
    display:flex;
    gap:15px;
    align-items:center;
}
.item img {
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:10px;
}

/* FORM GRID */
.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}
.full {
    grid-column:1 / -1;
}

/* FORM */
.form-group label {
    font-size:12px;
    text-transform:uppercase;
    color:#7A6050;
    margin-bottom:5px;
    display:block;
}
.form-group input,
.form-group textarea {
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    background:#F5F0E8;
}

/* SUMMARY */
.summary {
    position:sticky;
    top:100px;
    height:fit-content;
    background:#fff;
}

.summary-row {
    display:flex;
    justify-content:space-between;
    padding:6px 0;
}

.summary-total {
    display:flex;
    justify-content:space-between;
    margin-top:10px;
    padding-top:10px;
    border-top:1px solid #eee;
    font-size:18px;
    font-weight:700;
    color:#C9A84C;
}

/* BUTTON */
.btn-submit {
    width:100%;
    padding:16px;
    background:#2C1A0E;
    color:#fff;
    border:none;
    border-radius:12px;
    margin-top:20px;
    font-family:'Playfair Display';
}

/* RESPONSIVE */
@media(max-width:900px){
    .checkout-layout { grid-template-columns:1fr; }
    .form-grid { grid-template-columns:1fr; }
}
</style>
@endpush


@section('content')
<div class="container">

    <div class="checkout-header">
        <h1>Check<span>out</span></h1>
    </div>

    <form action="{{ route('payment') }}" method="GET">
        @csrf

        <div class="checkout-layout">

            {{-- LEFT --}}
            <div>

                {{-- ITEM --}}
                <div class="card-box">
                    <h3 style="font-family:Playfair Display;margin-bottom:15px;">
                        Item Lisensi
                    </h3>

                    <div class="item">
                        <img src="{{ asset($motif->thumbnail) }}">
                        <div>
                            <h4 style="font-family:Playfair Display;">
                                {{ $motif->nama }}
                            </h4>
                            <p>{{ $motif->kategori }}</p>
                            <strong>Rp {{ number_format($motif->harga,0,',','.') }}</strong>
                        </div>
                    </div>
                </div>

                {{-- IDENTITAS PEMESAN --}}
                <div class="card-box">
                    <h3 style="font-family:Playfair Display;margin-bottom:15px;">
                        Identitas Pemesan
                    </h3>

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Nama *</label>
                            <input type="text" name="nama">
                        </div>

                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email">
                        </div>

                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="telepon">
                        </div>

                        <div class="form-group">
                            <label>No KTP (NIK)</label>
                            <input type="text" name="nik">
                        </div>

                    </div>
                </div>

                {{-- PERUSAHAAN --}}
                <div class="card-box">
                    <h3 style="font-family:Playfair Display;margin-bottom:15px;">
                        Identitas Perusahaan (Opsional)
                    </h3>

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Nama Perusahaan</label>
                            <input type="text" name="perusahaan">
                        </div>

                        <div class="form-group">
                            <label>NPWP</label>
                            <input type="text" name="npwp">
                        </div>

                        <div class="form-group">
                            <label>Bidang Usaha</label>
                            <input type="text" name="bidang">
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat">
                        </div>

                        <div class="form-group full">
                            <label>Catatan Tambahan</label>
                            <textarea name="catatan"></textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
<div class="summary card-box">

    <h3 style="font-family:Playfair Display;margin-bottom:15px;">
        Ringkasan Pesanan
    </h3>

    <div class="summary-row">
        <span>{{ $motif->nama }}</span>
        <span>Rp {{ number_format($motif->harga,0,',','.') }}</span>
    </div>

    <div class="summary-row">
        <span>Diskon</span>
        <span>Rp {{ number_format($diskon,0,',','.') }}</span>
    </div>

    <div class="summary-row">
        <span>Pajak</span>
        <span>Rp {{ number_format($pajak,0,',','.') }}</span>
    </div>

    <div class="summary-total">
        <span>Total</span>
        <span>Rp {{ number_format($total,0,',','.') }}</span>
    </div>

    {{-- FIX BUTTON --}}
    <button type="submit" class="btn-submit">
        LANJUT PEMBAYARAN →
    </button>

</div>

        </div>
    </form>
</div>
@endsection