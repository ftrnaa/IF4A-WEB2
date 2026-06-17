<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    margin: 20px;
    size: A4 portrait;
}

body{
    font-family: DejaVu Sans, sans-serif;
    color:#222;
    font-size:12px;
}

.wrapper{
    border:3px solid #c8a96e;
    padding:20px;
    min-height:1000px;
    position:relative;
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.header h1{
    margin:0;
    font-size:28px;
    letter-spacing:2px;
}

.header h2{
    margin:5px 0;
    font-size:16px;
    font-weight:normal;
}

.cert-number{
    text-align:right;
    font-size:11px;
    margin-bottom:20px;
}

.owner-box{
    margin-top:20px;
    margin-bottom:25px;
}

.owner-box p{
    margin:3px 0;
}

.owner-name{
    font-size:22px;
    font-weight:bold;
}

.section-title{
    background:#f5f5f5;
    padding:6px;
    font-weight:bold;
    border:1px solid #ddd;
    margin-top:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table td{
    border:1px solid #ccc;
    padding:8px;
}

.label{
    width:35%;
    background:#fafafa;
    font-weight:bold;
}

.footer-area{
    position:absolute;
    bottom:30px;
    left:20px;
    right:20px;
}

.bottom-row{
    width:100%;
}

.qr-box{
    width:150px;
    text-align:center;
    float:left;
}

.qr-box img{
    width:100px;
}

.signature-box{
    width:250px;
    float:right;
    text-align:center;
}

.signature-space{
    height:80px;
}

.signature-line{
    border-top:1px solid #000;
    padding-top:5px;
}

.clearfix{
    clear:both;
}

.note{
    margin-top:15px;
    font-size:10px;
    color:#666;
    text-align:center;
}
</style>

</head>

<body>

<div class="wrapper">

    <div class="cert-number">
        Certificate No :
        <strong>{{ $certificate->certificate_number }}</strong>
    </div>

    <div class="header">
        <h1>SERTIFIKAT LISENSI BATIK</h1>
        <h2>Sertifikat Kepemilikan Hak Penggunaan Motif Batik</h2>
    </div>

    <div class="owner-box">
        <p>Diberikan kepada:</p>

        <div class="owner-name">
            {{ strtoupper($order->nama) }}
        </div>

        @if($order->perusahaan)
            <p>{{ $order->perusahaan }}</p>
        @endif

        <p>{{ $order->alamat }}</p>
    </div>

    <div class="section-title">
        INFORMASI LISENSI
    </div>

    <table>
        <tr>
            <td class="label">Nama Pemegang Lisensi</td>
            <td>{{ $order->nama }}</td>
        </tr>

        <tr>
            <td class="label">Email</td>
            <td>{{ $order->email }}</td>
        </tr>

        <tr>
            <td class="label">Perusahaan</td>
            <td>{{ $order->perusahaan ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Bidang Usaha</td>
            <td>{{ $order->bidang_usaha ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">NPWP</td>
            <td>{{ $order->npwp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Motif Batik</td>
            <td>{{ $batik->nama }}</td>
        </tr>

        <tr>
            <td class="label">Kategori Motif</td>
            <td>{{ $batik->kategori ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Kode Order</td>
            <td>{{ $order->kode_order }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal Terbit</td>
            <td>{{ $issuedAt->format('d F Y') }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal Berakhir</td>
            <td>{{ $expiredAt->format('d F Y') }}</td>
        </tr>

        <tr>
            <td class="label">Status Lisensi</td>
            <td>
                @if(now()->gt($expiredAt))
                    EXPIRED
                @else
                    ACTIVE
                @endif
            </td>
        </tr>
    </table>

    <div class="footer-area">

        <div class="bottom-row">

            <div class="qr-box">
                <img src="{{ $qrSrc }}">
                <div>
                    Scan untuk verifikasi
                </div>
            </div>

            <div class="signature-box">

                <div class="signature-space"></div>

                <div class="signature-line">
                    BatikAI Indonesia<br>
                    Penerbit Lisensi
                </div>

            </div>

            <div class="clearfix"></div>

        </div>

        <div class="note">
            Sertifikat ini diterbitkan secara digital dan dapat diverifikasi melalui QR Code resmi.
        </div>

    </div>

</div>

</body>
</html>