@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Pembayaran — BatikAI')

@push('styles')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap');

body{
    background:#F5F0E8;
    font-family:'DM Sans',sans-serif;
}

.payment-header{
    padding:30px 0 20px;
}

.payment-header h1{
    font-family:'Playfair Display';
    font-size:42px;
    color:#2C1A0E;
    margin:0;
}

.payment-layout{
    display:grid;
    grid-template-columns:1fr 360px;
    gap:30px;
    margin-bottom:60px;
}

.card-box{
    background:#fffdf8;
    border-radius:16px;
    padding:24px;
    box-shadow:0 8px 25px rgba(0,0,0,.06);
}

/* DETAIL PRODUK */

.section-title{
    font-family:'Playfair Display';
    color:#2C1A0E;
    margin-bottom:20px;
}

.product-card{
    display:flex;
    gap:20px;
    align-items:flex-start;
}

.product-image{
    width:180px;
    min-width:180px;
    height:180px;
    border-radius:14px;
    overflow:hidden;
    background:#f2f2f2;
}

.product-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.product-info{
    flex:1;
}

.product-category{
    display:inline-block;
    padding:6px 12px;
    border-radius:30px;
    background:#F5F0E8;
    color:#8A6A2F;
    font-size:13px;
    margin-bottom:12px;
}

.product-info h4{
    font-family:'Playfair Display';
    color:#2C1A0E;
    font-size:26px;
    margin-bottom:12px;
}

.product-info p{
    color:#666;
    line-height:1.8;
    margin:0;
}

.info-box{
    margin-top:25px;
    background:#F8F5EF;
    border-left:4px solid #C9A84C;
    border-radius:12px;
    padding:18px;
}

.info-box h5{
    margin-bottom:10px;
    color:#2C1A0E;
}

.info-box p{
    margin:0;
    color:#666;
    line-height:1.7;
}

/* SUMMARY */

.summary{
    position:sticky;
    top:90px;
    height:fit-content;
}

.summary h3{
    font-family:'Playfair Display';
    margin-bottom:20px;
    color:#2C1A0E;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
    color:#555;
}

.summary-total{
    display:flex;
    justify-content:space-between;
    margin-top:18px;
    font-size:22px;
    font-weight:700;
    color:#C9A84C;
}

/* BUTTON */

.btn-submit{
    width:100%;
    padding:16px;
    border:none;
    border-radius:12px;
    margin-top:22px;
    background:#2C1A0E;
    color:#fff;
    cursor:pointer;
    font-family:'Playfair Display';
    font-size:18px;
    transition:.3s;
}

.btn-submit:hover{
    background:#5C3D1E;
    transform:translateY(-2px);
}

/* SECURITY */

.security-note{
    margin-top:20px;
    font-size:13px;
    color:#777;
    text-align:center;
    line-height:1.6;
}

.security-note strong{
    color:#2C1A0E;
}

/* RESPONSIVE */

@media(max-width:900px){

    .payment-layout{
        grid-template-columns:1fr;
    }

    .summary{
        position:relative;
        top:0;
    }

    .product-card{
        flex-direction:column;
    }

    .product-image{
        width:100%;
        height:260px;
    }

}
</style>

@endpush

@section('content')

<div class="container">

```
<div class="payment-header">
    <h1>Payment</h1>
</div>

<div class="payment-layout">

    {{-- LEFT --}}
    <div>

        <div class="card-box">

            <h3 class="section-title">
                Detail Pesanan
            </h3>

            <div class="product-card">

                <div class="product-image">
                    <img
                        src="{{ $order->batik->preview_url }}"
                        alt="{{ $order->batik->nama }}">
                </div>

                <div class="product-info">

                    <span class="product-category">
                        {{ $order->batik->kategori }}
                    </span>

                    <h4>
                        {{ $order->batik->nama }}
                    </h4>

                    <p>
                        {{ Str::limit($order->batik->deskripsi, 220) }}
                    </p>

                </div>

            </div>

            <div class="info-box">

                <h5>Informasi Pembayaran</h5>

                <p>
                    Setelah menekan tombol
                    <strong>Bayar Sekarang</strong>,
                    Anda akan diarahkan ke halaman pembayaran Midtrans
                    untuk memilih metode pembayaran yang tersedia.
                    Setelah pembayaran berhasil diverifikasi,
                    pesanan akan diproses secara otomatis.
                </p>

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="summary card-box">

        <h3>
            Ringkasan Pesanan
        </h3>

        <div class="summary-row">
            <span>Produk</span>
            <span>
                Rp {{ number_format($order->batik->harga,0,',','.') }}
            </span>
        </div>

        <div class="summary-row">
            <span>Biaya Layanan</span>
            <span>Rp 0</span>
        </div>

        <hr>

        <div class="summary-total">
            <span>Total</span>
            <span>
                Rp {{ number_format($order->total,0,',','.') }}
            </span>
        </div>

        <button
            id="pay-button"
            class="btn-submit"
            type="button">
            BAYAR SEKARANG
        </button>

        <div class="security-note">
            Transaksi diproses dengan aman melalui
            <strong>Midtrans Payment Gateway</strong>
        </div>

    </div>

</div>
```

</div>
@endsection

@push('scripts')

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>

document
.getElementById('pay-button')
.addEventListener('click', function () {

    snap.pay('{{ $snapToken }}', {

        onSuccess: function(result) {

            window.location.href =
                "{{ route('successpayment', $order->id) }}";

        },

        onPending: function(result) {

            alert('Menunggu pembayaran');

        },

        onError: function(result) {

            alert('Pembayaran gagal');

        }

    });

});

</script>

@endpush
