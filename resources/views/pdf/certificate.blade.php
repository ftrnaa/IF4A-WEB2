<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    margin: 0;
    size: A4 portrait;
}

* {
    box-sizing: border-box;
}

body {
    font-family: DejaVu Sans, sans-serif;
    color: #2b2b2b;
    font-size: 12px;
    margin: 0;
    padding: 0;
}

.page {
    padding: 24px;
}

.wrapper {
    border: 2px solid #b8935a;
    padding: 0;
    position: relative;
}

.wrapper-inner {
    border: 1px solid #ddc9a3;
    padding: 28px 32px;
    min-height: 1000px;
    position: relative;
}

/* ===== Header ===== */
.header {
    text-align: center;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 2px solid #b8935a;
}

.header .ornament {
    font-size: 11px;
    color: #b8935a;
    letter-spacing: 6px;
    margin-bottom: 6px;
}

.header h1 {
    margin: 0;
    font-size: 26px;
    letter-spacing: 3px;
    color: #1a1a1a;
    font-weight: bold;
}

.header h2 {
    margin: 6px 0 0 0;
    font-size: 13px;
    font-weight: normal;
    color: #555;
}

.cert-number {
    text-align: center;
    font-size: 11px;
    margin-bottom: 20px;
    color: #555;
}

.cert-number strong {
    color: #1a1a1a;
    letter-spacing: 1px;
}

/* ===== Owner block ===== */
.owner-box {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px dashed #c8b48a;
}

.owner-box .label-small {
    font-size: 11px;
    color: #777;
    margin: 0 0 4px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.owner-name {
    font-size: 24px;
    font-weight: bold;
    color: #1a1a1a;
    margin: 2px 0;
}

.owner-box .company {
    font-size: 13px;
    color: #444;
    margin: 4px 0 0 0;
}

.owner-box .address {
    font-size: 11px;
    color: #777;
    margin: 4px 0 0 0;
}

/* ===== Section title ===== */
.section-title {
    background: #f7f1e6;
    color: #6b4f2a;
    padding: 7px 10px;
    font-weight: bold;
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-left: 4px solid #b8935a;
    margin: 18px 0 0 0;
}

/* ===== Info table ===== */
table.info {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0;
}

table.info td {
    border: 1px solid #e6e6e6;
    padding: 8px 10px;
    font-size: 11.5px;
    vertical-align: top;
}

table.info tr:nth-child(even) td {
    background: #fafafa;
}

table.info .label {
    width: 34%;
    background: #f5f0e6;
    font-weight: bold;
    color: #4a3a20;
}

.status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 3px;
    font-weight: bold;
    font-size: 10.5px;
    letter-spacing: 0.5px;
}

.status-active {
    background: #e3f3e6;
    color: #1f7a35;
    border: 1px solid #9fd6ab;
}

.status-expired {
    background: #fbe6e6;
    color: #b32424;
    border: 1px solid #f0b0b0;
}

/* ===== Footer ===== */
.footer-area {
    position: absolute;
    bottom: 0;
    left: 32px;
    right: 32px;
}

table.footer-table {
    width: 100%;
    border-collapse: collapse;
}

table.footer-table td {
    border: none;
    padding: 0;
    vertical-align: bottom;
}

.qr-box {
    width: 45%;
    text-align: center;
}

.qr-box img {
    width: 110px;
    height: 110px;
    border: 1px solid #ddd;
    padding: 4px;
    background: #fff;
}

.qr-box .qr-caption {
    margin-top: 6px;
    font-weight: bold;
    font-size: 10.5px;
    color: #333;
}

.qr-box .qr-url {
    margin-top: 4px;
    font-size: 8.5px;
    word-break: break-all;
    color: #888;
    line-height: 1.3;
}

.signature-box {
    width: 55%;
    text-align: center;
}

.signature-space {
    height: 60px;
}

.signature-line {
    border-top: 1px solid #999;
    padding-top: 6px;
    font-size: 11px;
    color: #333;
}

.signature-line strong {
    display: block;
    font-size: 12px;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.note {
    margin-top: 22px;
    padding-top: 12px;
    border-top: 1px solid #eee;
    font-size: 9.5px;
    color: #999;
    text-align: center;
}
</style>

</head>

<body>

<div class="page">
<div class="wrapper">
<div class="wrapper-inner">

    <div class="header">
        <div class="ornament">&#10070; &#10070; &#10070;</div>
        <h1>SERTIFIKAT LISENSI BATIK</h1>
        <h2>Sertifikat Kepemilikan Hak Penggunaan Motif Batik</h2>
    </div>

    <div class="cert-number">
        Certificate No&nbsp;: <strong>{{ $certificate->certificate_number }}</strong>
    </div>

    <div class="owner-box">
        <p class="label-small">Diberikan kepada</p>

        <div class="owner-name">
            {{ strtoupper($order->nama) }}
        </div>

        @if($order->perusahaan)
            <p class="company">{{ $order->perusahaan }}</p>
        @endif

        <p class="address">{{ $order->alamat }}</p>
    </div>

    <div class="section-title">
        Informasi Lisensi
    </div>

    <table class="info">
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
            <td>{{ \Carbon\Carbon::parse($order->license_expired_at)->format('d F Y') }}</td>
        </tr>

        <tr>
            <td class="label">Status Lisensi</td>
            <td>
                @if(now()->gt(\Carbon\Carbon::parse($order->license_expired_at)))
                    <span class="status-badge status-expired">EXPIRED</span>
                @else
                    <span class="status-badge status-active">ACTIVE</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer-area">

        <table class="footer-table">
            <tr>
                <td class="qr-box">
                    <img src="{{ $qrSrc }}" alt="QR Code">
                    <div class="qr-caption">Scan untuk verifikasi</div>
                    <div class="qr-url">{{ $verifyUrl }}</div>
                </td>
                <td class="signature-box">
                    <div class="signature-space"></div>
                    <div class="signature-line">
                        <strong>BatikAI Indonesia</strong>
                        Penerbit Lisensi
                    </div>
                </td>
            </tr>
            
        </table>

        <div class="note">
            Sertifikat ini diterbitkan secara digital dan dapat diverifikasi melalui QR Code resmi.
        </div>

    </div>

</div>
</div>
</div>

</body>
</html>