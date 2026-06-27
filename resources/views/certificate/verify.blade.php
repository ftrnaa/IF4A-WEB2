<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Sertifikat</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            background:#f5f7fb;
            padding:40px 15px;
        }

        .card{
            max-width:700px;
            margin:auto;
            background:#fff;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,.08);
        }

        .header{
            background:linear-gradient(135deg,#8B5E3C,#C8A96E);
            color:#fff;
            padding:30px;
            text-align:center;
        }

        .header h1{
            font-size:28px;
            margin-bottom:10px;
        }

        .header p{
            opacity:.9;
            font-size:14px;
        }

        .content{
            padding:30px;
        }

        .status-box{
            text-align:center;
            margin-bottom:30px;
        }

        .status-icon{
            font-size:50px;
            margin-bottom:10px;
        }

        .badge{
            display:inline-block;
            background:#16a34a;
            color:#fff;
            padding:8px 18px;
            border-radius:30px;
            font-size:13px;
            font-weight:bold;
            letter-spacing:1px;
        }

        .info-table{
            width:100%;
            border-collapse:collapse;
            margin-top:25px;
        }

        .info-table tr{
            border-bottom:1px solid #eee;
        }

        .info-table td{
            padding:14px 5px;
        }

        .label{
            width:220px;
            color:#666;
            font-weight:bold;
        }

        .value{
            color:#222;
        }

        .footer{
            margin-top:30px;
            padding:20px;
            background:#fafafa;
            border-radius:12px;
            text-align:center;
            font-size:13px;
            color:#666;
        }

        .certificate-number{
            background:#f7f3ea;
            padding:12px;
            border-radius:10px;
            text-align:center;
            margin-top:20px;
            font-weight:bold;
            color:#8B5E3C;
        }

    </style>
</head>
<body>

@php
    $issuedAt = $certificate->order->created_at;

    $expiredAt = $certificate->order->license_expired_at;

    $isExpired = $expiredAt
        ? now()->greaterThan($expiredAt)
        : false;
@endphp

<div class="card">

    <div class="header">
        <h1>Sertifikat Lisensi Batik</h1>
        <p>Verifikasi Keaslian Sertifikat</p>
    </div>

    <div class="content">

        <div class="status-box">

            <div class="status-icon">
                {{ $isExpired ? '⚠️' : '✅' }}
            </div>

            <div class="badge"
                 style="background:{{ $isExpired ? '#dc2626' : '#16a34a' }}">
                {{ $isExpired ? 'EXPIRED' : 'ACTIVE' }}
            </div>

        </div>

        <div class="certificate-number">
            Nomor Sertifikat:
            {{ $certificate->certificate_number }}
        </div>

        <table class="info-table">

            <tr>
                <td class="label">Nama Pemegang Lisensi</td>
                <td class="value">{{ $certificate->order->nama }}</td>
            </tr>

            <tr>
                <td class="label">Motif Batik</td>
                <td class="value">{{ $certificate->order->batik->nama }}</td>
            </tr>

            <tr>
                <td class="label">Kode Order</td>
                <td class="value">{{ $certificate->order->kode_order }}</td>
            </tr>

            <tr>
                <td class="label">Tanggal Terbit</td>
                <td class="value">
                    {{ $issuedAt->format('d F Y') }}
                </td>
            </tr>

            <tr>
                <td class="label">Berlaku Sampai</td>
                <td class="value">
                    {{ $expiredAt ? $expiredAt->format('d F Y') : '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Status Lisensi</td>
                <td class="value">
                    <strong>
                        {{ $isExpired ? 'Kedaluwarsa' : 'Aktif' }}
                    </strong>
                </td>
            </tr>

        </table>

        <div class="footer">
            Sertifikat ini diterbitkan oleh sistem lisensi batik dan
            dapat diverifikasi secara digital melalui QR Code resmi.
        </div>

    </div>

</div>

</body>
</html>