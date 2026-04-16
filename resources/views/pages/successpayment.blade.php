{{-- resources/views/checkout/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Diterima — BatikAI')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --cream:#F5F0E8;
        --gold:#C9A84C;
        --brown-deep:#2C1A0E;
        --brown-mid:#5C3D1E;
        --text-soft:#7A6050;
        --white:#FFFDF8;
        --radius-md:12px;
    }

    /* ✅ IMPORTANT: WRAPPER PAGE (FIX NAVBAR ISSUE) */
    .page-success {
        background: var(--cream);
        min-height: 100vh;
        padding-top: 20px; /* biar tidak nabrak navbar */
    }

    .success-page {
        min-height: 80vh;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        padding:60px 20px;
        text-align:center;
        font-family:'DM Sans', sans-serif;
    }

    /* ICON */
    .success-icon-wrap {
        margin-bottom:28px;
    }

    .success-icon-circle {
        width:88px;
        height:88px;
        border-radius:50%;
        background:linear-gradient(135deg,var(--brown-deep),var(--brown-mid));
        display:flex;
        align-items:center;
        justify-content:center;
        margin:auto;
    }

    /* TITLE */
    .brand-name {
        font-size:11px;
        letter-spacing:2px;
        color:var(--text-soft);
        margin-bottom:12px;
    }

    .success-title {
        font-family:'Playfair Display', serif;
        font-size:40px;
        margin-bottom:10px;
        color:var(--brown-deep);
    }

    .success-subtitle {
        max-width:480px;
        color:var(--text-soft);
        margin-bottom:30px;
        line-height:1.6;
    }

    /* CARD */
    .order-card {
        width:100%;
        max-width:560px;
        background:var(--white);
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 4px 24px rgba(0,0,0,0.08);
        margin-bottom:30px;
    }

    .order-card-header {
        background:var(--brown-deep);
        color:#fff;
        padding:16px;
        font-family:'Playfair Display', serif;
    }

    .order-row {
        display:flex;
        justify-content:space-between;
        padding:12px 20px;
        border-bottom:1px solid #eee;
        font-size:14px;
    }

    .order-row:last-child {
        border-bottom:none;
    }

    .order-row.highlight {
        font-weight:bold;
        color:var(--gold);
    }

    /* BUTTON */
    .cta-buttons {
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        justify-content:center;
    }

    .btn-primary {
        background:var(--brown-deep);
        color:#fff;
        padding:12px 24px;
        border-radius:10px;
        text-decoration:none;
    }

    .btn-secondary {
        border:1px solid var(--gold);
        color:var(--brown-deep);
        padding:12px 24px;
        border-radius:10px;
        text-decoration:none;
    }

    /* CONFETTI */
    #confettiCanvas {
        position:fixed;
        inset:0;
        pointer-events:none;
        z-index:9999;
    }

</style>
@endpush

@section('content')

<div class="page-success">

    <canvas id="confettiCanvas"></canvas>

    <div class="success-page">

        {{-- ICON --}}
        <div class="success-icon-wrap">
            <div class="success-icon-circle">
                <svg width="40" height="40" viewBox="0 0 52 52" fill="none">
                    <path d="M14 27l7 7 16-17" stroke="#C9A84C" stroke-width="3"/>
                </svg>
            </div>
        </div>

        {{-- BRAND --}}
        <p class="brand-name">BatikAI — Transaksi Berhasil</p>

        {{-- TITLE --}}
        <h1 class="success-title">Pembayaran Diterima</h1>

        <p class="success-subtitle">
            Terima kasih, pembayaran Anda berhasil diproses.
            Detail pesanan sudah dikirim ke email Anda.
        </p>

        {{-- ORDER CARD --}}
        <div class="order-card">

            <div class="order-card-header">
                Detail Pesanan
            </div>

            <div class="order-row">
                <span>No Pesanan</span>
                <span>#{{ $order->kode ?? 'BTK-001' }}</span>
            </div>

            <div class="order-row">
                <span>Email</span>
                <span>{{ $order->email ?? 'email@gmail.com' }}</span>
            </div>

            <div class="order-row">
                <span>Metode</span>
                <span>{{ $order->metode_display ?? 'Virtual Account' }}</span>
            </div>

            <div class="order-row highlight">
                <span>Total</span>
                <span>Rp {{ number_format($order->total ?? 120000,0,',','.') }}</span>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="cta-buttons">
            <a href="{{ route('koleksi') }}" class="btn-primary">
                Kembali ke Koleksi
            </a>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    const canvas = document.getElementById('confettiCanvas');
    const ctx = canvas.getContext('2d');

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const pieces = [];

    for (let i = 0; i < 60; i++) {
        pieces.push({
            x: Math.random() * canvas.width,
            y: Math.random() * -canvas.height,
            w: 6,
            h: 6,
            vx: Math.random() * 2 - 1,
            vy: Math.random() * 3 + 2,
            color: '#C9A84C'
        });
    }

    function draw() {
        ctx.clearRect(0,0,canvas.width,canvas.height);

        pieces.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;

            ctx.fillStyle = p.color;
            ctx.fillRect(p.x,p.y,p.w,p.h);
        });

        requestAnimationFrame(draw);
    }

    draw();

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
</script>
@endpush