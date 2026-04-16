@extends('layouts.app')

@section('title', 'Pembayaran — BatikAI')

@php
$order = (object)[
    'id' => 1,
    'total' => 250000,
    'motif' => (object)[
        'nama' => 'Parang Kusumo'
    ]
];

$diskon = 0;
$pajak = 0;
@endphp

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

body {
    background:#F5F0E8;
    font-family:'DM Sans', sans-serif;
}

/* ✅ FIX: jarak header NORMAL lagi */
.payment-header {
    padding:30px 0 20px;
}
.payment-header h1 {
    font-family:'Playfair Display';
    font-size:42px;
}

/* LAYOUT */
.payment-layout {
    display:grid;
    grid-template-columns:1fr 360px;
    gap:30px;
    margin-bottom:60px;
}

/* CARD */
.card-box {
    background:#fffdf8;
    border-radius:16px;
    padding:24px;
    box-shadow:0 8px 25px rgba(0,0,0,0.06);
}

/* PAYMENT METHOD */
.method-card {
    padding:16px;
    border-radius:12px;
    border:1px solid #ddd;
    margin-bottom:10px;
    cursor:pointer;
    transition:.2s;
}
.method-card:hover {
    border-color:#C9A84C;
}
.method-card.active {
    border-color:#C9A84C;
    background:#F5F0E8;
}

/* VA */
.va-box {
    margin-top:20px;
    padding:20px;
    border-radius:14px;
    background:#2C1A0E;
    color:#fff;
}
.va-number {
    font-size:24px;
    font-family:'Playfair Display';
    color:#C9A84C;
    letter-spacing:2px;
}

/* SUMMARY */
.summary {
    position:sticky;
    top:90px;
    height:fit-content;
}

.summary-row {
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
}
.summary-total {
    display:flex;
    justify-content:space-between;
    margin-top:15px;
    font-size:20px;
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
    cursor:pointer;
}
.btn-submit:hover {
    background:#5C3D1E;
}

/* RESPONSIVE */
@media(max-width:900px){
    .payment-layout {
        grid-template-columns:1fr;
    }
    .summary {
        position:relative;
        top:0;
        margin-top:20px;
    }
}
</style>
@endpush


@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="payment-header">
        <h1>Payment</h1>
    </div>

    <div class="payment-layout">

        {{-- LEFT --}}
        <div>

            <div class="card-box">
                <h3 style="font-family:Playfair Display;margin-bottom:15px;">
                    Metode Pembayaran
                </h3>

                {{-- METHOD --}}
                <div class="method-card active" onclick="selectMethod('bca', this)">
                    BCA Virtual Account
                </div>

                <div class="method-card" onclick="selectMethod('mandiri', this)">
                    Mandiri Virtual Account
                </div>

                <div class="method-card" onclick="selectMethod('bni', this)">
                    BNI Virtual Account
                </div>

                {{-- VA --}}
                <div class="va-box">
                    <p>Nomor Virtual Account</p>
                    <div class="va-number" id="vaNumber">
                        1234 5678 9012 3456
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
                <span>{{ $order->motif->nama }}</span>
                <span>Rp {{ number_format($order->total,0,',','.') }}</span>
            </div>

            <div class="summary-row">
                <span>Diskon</span>
                <span>Rp {{ number_format($diskon,0,',','.') }}</span>
            </div>

            <div class="summary-row">
                <span>Pajak</span>
                <span>Rp {{ number_format($pajak,0,',','.') }}</span>
            </div>

            <hr>

            <div class="summary-total">
                <span>Total</span>
                <span>Rp {{ number_format($order->total,0,',','.') }}</span>
            </div>

            {{-- FORM --}}
            <form action="{{ route('successpayment') }}" method="GET">
                <button class="btn-submit">
                    KONFIRMASI PEMBAYARAN
                </button>
            </form>

        </div>

    </div>
</div>
@endsection


@push('scripts')
<script>
const vaList = {
    bca: '1234 5678 9012 3456',
    mandiri: '8800 1122 3344 5566',
    bni: '9988 7766 5544 3322'
};

function selectMethod(method, el) {
    // remove active
    document.querySelectorAll('.method-card').forEach(c => {
        c.classList.remove('active');
    });

    el.classList.add('active');

    // change VA
    document.getElementById('vaNumber').innerText = vaList[method];
}
</script>
@endpush