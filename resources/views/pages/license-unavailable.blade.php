@extends('layouts.app')

@section('title', 'Lisensi Tidak Tersedia')

@section('content')
<div class="container py-5">

    <div style="
        max-width:700px;
        margin:50px auto;
        background:#fff;
        padding:40px;
        border-radius:20px;
        text-align:center;
        box-shadow:0 10px 30px rgba(0,0,0,.08);
    ">

        <h1 style="
            color:#8B0000;
            margin-bottom:20px;
            font-family:'Playfair Display',serif;
        ">
            Lisensi Tidak Tersedia
        </h1>

        <p style="font-size:18px; margin-bottom:10px;">
            Maaf, motif batik
            <strong>{{ $motif->nama }}</strong>
            saat ini sudah memiliki pemegang lisensi aktif.
        </p>

        <p style="font-size:18px;">
            Lisensi akan berakhir pada:
        </p>

        <h3 style="
            color:#C9A84C;
            margin:20px 0;
        ">
            {{ \Carbon\Carbon::parse($lisensiAktif->license_expired_at)->format('d F Y') }}
        </h3>

        <p style="color:#666;">
            Setelah tanggal tersebut, motif dapat tersedia kembali
            apabila lisensi tidak diperpanjang oleh pemilik saat ini.
        </p>

        <a href="{{ route('koleksi') }}"
           class="btn btn-dark mt-4">
            Kembali ke Koleksi
        </a>

    </div>

</div>
@endsection