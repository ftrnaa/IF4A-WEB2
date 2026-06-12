<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  @page {
    size: A4 landscape;
    margin: 0;
  }

  body {
    font-family: DejaVu Sans, sans-serif;
    background: #fff;
    color: #333;
}

  /* ── OUTER WRAPPER ── */
  .page {
    width: 100%;
    height: auto;
    padding: 8mm;
}

  /* ── BORDER FRAME ── */
  .frame {
    flex: 1;
    border: 3px solid #C8A96E;
    outline: 1px solid #C8A96E;
    outline-offset: -7px;
    padding: 18px 30px;
    display: flex;
    flex-direction: column;
    position: relative;
    background:
      radial-gradient(ellipse at top left,  #fdf8f0 0%, transparent 60%),
      radial-gradient(ellipse at bottom right, #fdf8f0 0%, transparent 60%),
      #ffffff;
  }

  /* corner ornaments */
  .frame::before, .frame::after {
    content: '❧';
    position: absolute;
    font-size: 20px;
    color: #C8A96E;
    opacity: 0.5;
  }
  .frame::before { top: 10px; left: 14px; }
  .frame::after  { bottom: 10px; right: 14px; transform: rotate(180deg); }

  /* ── HEADER ── */
  .header {
    text-align: center;
    border-bottom: 1.5px solid #e8d5b0;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }

  .header-top {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 4px;
  }

  .ornament-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, #C8A96E, transparent);
  }

  .header-icon {
    font-size: 20px;
    color: #C8A96E;
  }

  .title {
    font-size: 19px;
    font-weight: bold;
    color: #6B4423;
    letter-spacing: 3px;
    text-transform: uppercase;
  }

  .subtitle {
    font-size: 10px;
    color: #999;
    letter-spacing: 1.5px;
    margin-top: 2px;
    text-transform: uppercase;
  }

  .cert-num {
    font-size: 10px;
    color: #aaa;
    margin-top: 4px;
    letter-spacing: 0.5px;
  }

  /* ── RECIPIENT ── */
  .recipient-section {
    text-align: center;
    margin: 6px 0 10px;
  }

  .given-to {
    font-size: 10px;
    color: #888;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 3px;
  }

  .owner-name {
    font-size: 26px;
    font-weight: bold;
    color: #5A3E2B;
    letter-spacing: 1px;
    line-height: 1.1;
  }

  .batik-name {
    display: inline-block;
    margin-top: 4px;
    font-size: 12px;
    color: #B8860B;
    font-weight: bold;
    letter-spacing: 1px;
    border-bottom: 1.5px solid #e8d5b0;
    padding-bottom: 2px;
  }

  /* ── BODY: table + QR side by side ── */
  .body-row {
    display: flex;
    gap: 20px;
    flex: 1;
    align-items: flex-start;
    margin-top: 2px;
  }

  /* ── INFO TABLE ── */
  .info-wrap {
    flex: 1;
    text-align: center;
}

  .info-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
  }

  .info-table tr:not(:last-child) td {
    border-bottom: 1px solid #f0e6d0;
  }

  .info-table td {
    padding: 6px 6px;
    vertical-align: middle;
  }

  .label {
    width: 145px;
    color: #888;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .value {
    color: #3a3a3a;
    font-weight: bold;
    font-size: 11px;
  }

  .status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    background: #16a34a;
    color: #fff;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 1px;
  }
.info-table {
    width: 70%;
    margin: 0 auto;
    border-collapse: collapse;
    font-size: 11px;
}
  /* ── QR + SIGNATURE COLUMN ── */
  .right-col {
    width: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }

  .qr-box {
    text-align: center;
  }

  .qr-box img {
    width: 100px;
    border: 1px solid #e8d5b0;
    padding: 3px;
    border-radius: 4px;
  }

  .qr-label {
    font-size: 8.5px;
    color: #aaa;
    margin-top: 4px;
    letter-spacing: 0.3px;
    text-align: center;
  }

  .sig-box {
    text-align: center;
    width: 100%;
    margin-top: 4px;
  }

  .sig-space {
    height: 28px;
  }

  .sig-line {
    border-top: 1px solid #888;
    padding-top: 4px;
    font-size: 9.5px;
    color: #666;
    letter-spacing: 0.3px;
  }

  /* ── FOOTER ── */
  .footer {
    text-align: center;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #f0e6d0;
    font-size: 9px;
    color: #bbb;
    letter-spacing: 0.3px;
  }
  .label,
.value{
    text-align:center;
}
</style>
</head>
<body>
<div class="page">
  <div class="frame">

    <!-- HEADER -->
    <div class="header">
      <div class="header-top">
        <div class="ornament-line"></div>
        <span class="header-icon">✦</span>
        <div class="ornament-line"></div>
      </div>
      <div class="title">Sertifikat Lisensi Batik</div>
      <div class="subtitle">Sertifikat Kepemilikan Lisensi Motif Batik</div>
      <div class="cert-num">
        No. Sertifikat &nbsp;·&nbsp; <strong>{{ $certificate->certificate_number }}</strong>
      </div>
    </div>

    <!-- RECIPIENT -->
    <div class="recipient-section">
      <div class="given-to">Diberikan kepada</div>
      <div class="owner-name">{{ strtoupper($order->nama) }}</div>
      <div class="batik-name">{{ $batik->nama }}</div>
    </div>

    <!-- BODY ROW -->
    <div style="text-align:center;">

      <!-- INFO TABLE -->
      <div class="info-wrap">
        <table class="info-table">
          <tr>
            <td class="label">Pemegang Lisensi</td>
            <td class="value">{{ $order->nama }}</td>
          </tr>
          <tr>
            <td class="label">Motif Batik</td>
            <td class="value">{{ $batik->nama }}</td>
          </tr>
          <tr>
            <td class="label">Kode Order</td>
            <td class="value">{{ $order->kode_order }}</td>
          </tr>
          <tr>
            <td class="label">Tanggal Terbit</td>
            <td class="value">{{ $issuedAt->format('d F Y') }}</td>
          </tr>
          <tr>
            <td class="label">Tanggal Berakhir</td>
            <td class="value">{{ $expiredAt->format('d F Y') }}</td>
          </tr>
          @php
$status = now()->gt($expiredAt) ? 'EXPIRED' : 'ACTIVE';
@endphp
          <tr>
            <td class="label">Status Lisensi</td>
            <td class="value"><span class="status-badge">
    {{ $status }}
</span></td>
          </tr>
        </table>
      </div>

      <!-- QR + SIGNATURE -->
      <div class="right-col">
        <div class="qr-box">
          <img src="{{ $qrSrc }}">
          <div class="qr-label">Scan untuk verifikasi</div>
        </div>
        <div class="sig-box">
          <div class="sig-space"></div>
          <div class="sig-line">Penerbit Lisensi</div>
        </div>
      </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
      Sertifikat ini diterbitkan secara digital dan dapat diverifikasi melalui QR Code resmi.
    </div>

  </div>
</div>
</body>
</html>