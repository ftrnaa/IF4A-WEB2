
@extends('layouts.user-dashboard')
@section('title', 'Dashboard — BatikAI')
@section('breadcrumb', 'Beranda')

@section('content')



{{-- Welcome Banner --}}
<div class="user-welcome">

    <div>
        <h1 class="user-welcome__title">
            Halo, <em>{{ auth()->user()->name ?? auth()->user()->nama }}</em> 👋
        </h1>

        <p class="user-welcome__sub">
            Kamu punya {{ $orders->count() }} lisensi aktif.
        </p>
    </div>

    <a href="/koleksi" class="user-welcome__action">
        🛍 Jelajahi Motif Baru
    </a>

</div>

{{-- Stats --}}
<div class="user-stats-grid">
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Motif Dimiliki</p>
            <p class="user-stat-card__value">
    {{ $orders->count() }}
</p>
            <p class="user-stat-card__sub">lisensi aktif</p>
        </div>
        <div class="user-stat-card__icon usc-gold">🎨</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Sertifikat</p>
            <p class="user-stat-card__value">
    {{ $orders->count() }}
</p>
            <p class="user-stat-card__sub">siap diunduh</p>
        </div>
        <div class="user-stat-card__icon usc-green">📜</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Total Pembelian</p>
            <p class="user-stat-card__value">
    Rp {{ number_format($totalPembelian,0,',','.') }}
</p>

<p class="user-stat-card__sub">
    {{ $orders->count() }} transaksi
</p>
        </div>
        <div class="user-stat-card__icon usc-brown">💳</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Lisensi Hampir Habis</p>
            <p class="user-stat-card__value">0</p>
<p class="user-stat-card__sub">belum ada</p>
        </div>
        <div class="user-stat-card__icon usc-blue">⚠️</div>
    </div>
</div>

{{-- Lisensi + Aktivitas --}}
<div class="user-grid-3-1">

    {{-- Lisensi Aktif --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">🛡 Lisensi Aktif</p>
            <a href="/dashboard/lisensi" class="user-card__action">Semua lisensi →</a>
        </div>
        <div class="user-card__body" style="padding-top:.6rem">
            @foreach($orders as $order)

<div class="license-item">

    <img
    src="https://btx.agunghakase.my.id/api/image/{{ $order->batik->preview_image }}"
    class="license-item__img"
    alt="{{ $order->batik->nama }}"
>

    <div style="flex:1;min-width:0">

        <p class="license-item__cat">
            {{ $order->batik->kategori }}
        </p>

        <p class="license-item__name">
            {{ $order->batik->nama }}
        </p>

        <div class="license-item__dates">

            <div class="license-item__date-group">
                <p class="license-item__date-lbl">
                    Kode Order
                </p>

                <p class="license-item__date-val">
                    {{ $order->kode_order }}
                </p>
            </div>

            <div class="license-item__date-group">
                <p class="license-item__date-lbl">
                    Tanggal Beli
                </p>

                <p class="license-item__date-val">
                    {{ $order->created_at->format('d M Y') }}
                </p>
            </div>

        </div>

    </div>

    <div class="license-item__status">

        <span class="status-badge status-badge--paid">
            Aktif
        </span>

    </div>

</div>

@endforeach
            
        </div>
    </div>

    {{-- Aktivitas --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">🕐 Aktivitas</p>
        </div>
    <div class="user-card__body" style="padding-top:.4rem">

    @foreach($orders->take(5) as $order)

    <div class="user-activity-item">

        <div class="user-activity-icon uai-purchase">
            🛍
        </div>

        <div class="user-activity-text">
            Membeli
            <strong>{{ $order->batik->nama }}</strong>
        </div>

        <div class="user-activity-time">
            {{ $order->created_at->format('d M') }}
        </div>

    </div>

    @endforeach

</div>

</div>

{{-- Sertifikat Terbaru --}}
<div class="user-card">
    <div class="user-card__header">
        <p class="user-card__title">📜 Sertifikat Terbaru</p>
        <a href="/dashboard/sertifikat" class="user-card__action">Lihat semua →</a>
    </div>
    <div class="user-card__body" style="padding-top:.5rem">
       
        @foreach($orders as $order)

<div class="cert-item">

    <div class="cert-item__icon">
        📜
    </div>

    <div>

        <p class="cert-item__name">
            Sertifikat Keaslian —
            {{ $order->batik->nama }}
        </p>

        <p class="cert-item__meta">
            Diterima
            {{ $order->created_at->format('d M Y') }}
        </p>

    </div>

    <div class="cert-item__actions">

        <button class="cert-btn cert-btn--view">
            👁 Lihat
        </button>

      <a
    href="{{ route('license.certificate.pdf', $order->id) }}"
    class="cert-btn cert-btn--dl"
>
    ⬇ Unduh
</a>

    </div>

</div>

@endforeach
    </div>
</div>

@include('pages.users.cert-modal')
@endsection