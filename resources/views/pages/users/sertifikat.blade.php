<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  @page {
    size: A4 portrait;
    margin: 0;
  }

  body {
    font-family: 'DejaVu Sans', 'Arial', sans-serif;
    background: #FFF9F0;
    width: 210mm;
    height: 297mm;
    overflow: hidden;
    position: relative;
  }

  /* ── Outer border frame ─────────────────────────────── */
  .outer-border {
    position: absolute;
    top: 10mm; left: 10mm;
    right: 10mm; bottom: 10mm;
    border: 4px solid #4A2C0A;
    background: #FFF9F0;
  }

  .gold-border {
    position: absolute;
    top: 11mm; left: 11mm;
    right: 11mm; bottom: 11mm;
    border: 1.5px solid #D4A843;
  }

  .inner-border {
    position: absolute;
    top: 13mm; left: 13mm;
    right: 13mm; bottom: 13mm;
    border: 1px solid #7B4A1E;
    background: #FFF9F0;
  }

  /* ── Corner ornaments ───────────────────────────────── */
  .corner {
    position: absolute;
    width: 18mm;
    height: 18mm;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #D4A843;
    font-weight: bold;
  }
  .corner-tl { top: 14mm; left: 14mm; }
  .corner-tr { top: 14mm; right: 14mm; }
  .corner-bl { bottom: 14mm; left: 14mm; }
  .corner-br { bottom: 14mm; right: 14mm; }

  .corner-inner {
    width: 14mm;
    height: 14mm;
    border: 2px solid #D4A843;
    transform: rotate(45deg);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .corner-dot {
    width: 5mm;
    height: 5mm;
    background: #C68642;
    transform: rotate(45deg);
  }

  /* ── Header band ────────────────────────────────────── */
  .header-band {
    position: absolute;
    top: 18mm; left: 18mm; right: 18mm;
    height: 28mm;
    background: #4A2C0A;
    border-top: 1.5px solid #D4A843;
    border-bottom: 1.5px solid #D4A843;
  }

  .header-band-inner {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  .logo-circle {
    position: absolute;
    left: 6mm;
    top: 50%;
    transform: translateY(-50%);
    width: 18mm;
    height: 18mm;
    border-radius: 50%;
    background: #FDF6EC;
    border: 1.5px solid #D4A843;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 7px;
    font-weight: bold;
    color: #4A2C0A;
    line-height: 1.3;
    text-align: center;
  }

  .cert-no-block {
    position: absolute;
    right: 6mm;
    bottom: 3mm;
    font-size: 6.5px;
    color: #D4A843;
    text-align: right;
  }

  .header-title {
    font-size: 22px;
    font-weight: bold;
    color: #FFFFFF;
    letter-spacing: 3px;
    text-transform: uppercase;
  }

  .header-subtitle {
    font-size: 8px;
    color: #D4A843;
    letter-spacing: 2px;
    margin-top: 2px;
  }

  /* ── Batik diamond strip ────────────────────────────── */
  .batik-strip {
    position: absolute;
    left: 18mm; right: 18mm;
    height: 6mm;
    background: #7B4A1E;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding: 0 2mm;
  }

  .batik-strip-top  { top: 46mm; }
  .batik-strip-bot  { bottom: 26mm; }

  .diamond-row {
    display: flex;
    align-items: center;
    gap: 0;
    width: 100%;
  }

  .diamond {
    width: 6mm;
    height: 6mm;
    min-width: 6mm;
    background: #D4A843;
    transform: rotate(45deg) scale(0.55);
  }

  /* ── Body content ───────────────────────────────────── */
  .body-content {
    position: absolute;
    top: 52mm; left: 22mm; right: 22mm;
    bottom: 38mm;
  }

  /* Issued-by block */
  .issuer-block {
    border-bottom: 0.8px solid #C68642;
    padding-bottom: 4mm;
    margin-bottom: 4mm;
  }

  .issuer-name {
    font-size: 10px;
    font-weight: bold;
    color: #4A2C0A;
    letter-spacing: 0.5px;
  }

  .issuer-sub {
    font-size: 7.5px;
    color: #7B4A1E;
    margin-top: 1.5mm;
    line-height: 1.5;
  }

  /* Grant block */
  .grant-intro {
    font-size: 8px;
    font-style: italic;
    color: #7B4A1E;
    margin-bottom: 1.5mm;
  }

  .buyer-name {
    font-size: 18px;
    font-weight: bold;
    color: #4A2C0A;
    margin-bottom: 1mm;
  }

  .buyer-address {
    font-size: 8px;
    color: #7B4A1E;
    margin-bottom: 4mm;
  }

  /* Detail table */
  .detail-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 5mm;
  }

  .detail-table tr:nth-child(even) td {
    background: #F5EBD8;
  }

  .detail-table td {
    padding: 1.8mm 2mm;
    font-size: 8px;
    vertical-align: middle;
  }

  .td-label {
    font-weight: bold;
    color: #7B4A1E;
    width: 38mm;
  }

  .td-colon {
    color: #4A2C0A;
    width: 5mm;
    text-align: center;
  }

  .td-value {
    color: #1A0A00;
    font-weight: normal;
  }

  .td-value.motif-val {
    font-weight: bold;
    color: #4A2C0A;
    font-size: 8.5px;
  }

  /* Compliance */
  .compliance-block {
    margin-top: 3mm;
  }

  .compliance-main {
    font-size: 8px;
    font-weight: bold;
    color: #4A2C0A;
    margin-bottom: 2mm;
  }

  .compliance-law {
    font-size: 8px;
    font-weight: bold;
    color: #8B1A1A;
    margin-bottom: 1.5mm;
  }

  .compliance-note {
    font-size: 7px;
    color: #7B4A1E;
  }

  /* ── Bottom section ─────────────────────────────────── */
  .bottom-section {
    position: absolute;
    bottom: 12mm; left: 22mm; right: 22mm;
    height: 14mm;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
  }

  .qr-block {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 1mm;
  }

  .qr-block img {
    width: 20mm;
    height: 20mm;
    border: 1px solid #C68642;
  }

  .qr-label {
    font-size: 6px;
    color: #7B4A1E;
  }

  .signature-block {
    text-align: center;
    min-width: 55mm;
  }

  .sig-line {
    border-top: 0.8px solid #7B4A1E;
    margin-bottom: 2mm;
    width: 55mm;
  }

  .sig-name {
    font-size: 8px;
    font-weight: bold;
    color: #4A2C0A;
  }

  .sig-title {
    font-size: 7px;
    color: #7B4A1E;
    margin-top: 0.5mm;
  }

  /* ── Footer bar ─────────────────────────────────────── */
  .footer-bar {
    position: absolute;
    bottom: 10.5mm; left: 18mm; right: 18mm;
    height: 4.5mm;
    background: #4A2C0A;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .footer-text {
    font-size: 6px;
    color: #D4A843;
    text-align: center;
    letter-spacing: 0.3px;
  }
</style>
</head>
<body>

  {{-- ── Borders ── --}}
  <div class="outer-border"></div>
  <div class="gold-border"></div>
  <div class="inner-border"></div>

  {{-- ── Corner ornaments ── --}}
  @foreach(['corner-tl','corner-tr','corner-bl','corner-br'] as $cls)
  <div class="corner {{ $cls }}">
    <div class="corner-inner"><div class="corner-dot"></div></div>
  </div>
  @endforeach

  {{-- ── Header band ── --}}
  <div class="header-band">
    <div class="header-band-inner">

      {{-- Logo --}}
      <div class="logo-circle">
        <span>BATIK</span>
        <span>AI</span>
      </div>

      <div class="header-title">CERTIFICATE</div>
      <div class="header-subtitle">OF BATIK MOTIF LICENSE</div>

      <div class="cert-no-block">
        Certificate No. {{ $certificate->certificate_number }}
      </div>
    </div>
  </div>

  {{-- ── Top batik strip ── --}}
  <div class="batik-strip batik-strip-top">
    <div class="diamond-row">
      @for($i = 0; $i < 45; $i++)
        <div class="diamond"></div>
      @endfor
    </div>
  </div>

  {{-- ── Body ── --}}
  <div class="body-content">

    {{-- Issuer --}}
    <div class="issuer-block">
      <div class="issuer-name">BATIKAI CERTIFICATION SERVICES</div>
      <div class="issuer-sub">
        Platform Sertifikasi Motif Batik Digital Indonesia<br>
        Web: batikai.id &nbsp;|&nbsp; Email: cert@batikai.id
      </div>
    </div>

    {{-- Grant --}}
    <div class="grant-intro">Hereby grants a license to:</div>
    <div class="buyer-name">{{ $order->nama }}</div>
    <div class="buyer-address">{{ $order->user->address ?? $order->email }}</div>

    {{-- Detail rows --}}
    <table class="detail-table">
      <tr>
        <td class="td-label">MOTIF</td>
        <td class="td-colon">:</td>
        <td class="td-value motif-val">{{ $batik->nama ?? '-' }}</td>
      </tr>
      <tr>
        <td class="td-label">KATEGORI</td>
        <td class="td-colon">:</td>
        <td class="td-value">{{ $batik->kategori ?? '-' }}</td>
      </tr>
      <tr>
        <td class="td-label">JENIS LISENSI</td>
        <td class="td-colon">:</td>
        <td class="td-value">Lisensi Komersial</td>
      </tr>
      <tr>
        <td class="td-label">TANGGAL TERBIT</td>
        <td class="td-colon">:</td>
        <td class="td-value">{{ $issuedAt->format('d F Y') }}</td>
      </tr>
      <tr>
        <td class="td-label">BERLAKU HINGGA</td>
        <td class="td-colon">:</td>
        <td class="td-value">{{ $expiredAt->format('d F Y') }}</td>
      </tr>
    </table>

    {{-- Compliance --}}
    <div class="compliance-block">
      <div class="compliance-main">
        Telah memenuhi syarat penggunaan lisensi motif batik digital sesuai dengan:
      </div>
      <div class="compliance-law">
        Ketentuan Lisensi BatikAI — Hak Kekayaan Intelektual Motif Batik Digital
      </div>
      <div class="compliance-note">
        Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta &amp; Peraturan Menteri terkait Kekayaan Intelektual
      </div>
    </div>

  </div>{{-- /body-content --}}

  {{-- ── Bottom batik strip ── --}}
  <div class="batik-strip batik-strip-bot">
    <div class="diamond-row">
      @for($i = 0; $i < 45; $i++)
        <div class="diamond"></div>
      @endfor
    </div>
  </div>

  {{-- ── Bottom section: QR + Signature ── --}}
  <div class="bottom-section">

    <div class="qr-block">
      <img src="{{ $qrSrc }}" alt="QR Verifikasi">
      <div class="qr-label">Scan untuk verifikasi keaslian sertifikat</div>
    </div>

    <div class="signature-block">
      <div class="sig-line"></div>
      <div class="sig-name">Administrator BatikAI</div>
      <div class="sig-title">Kepala Divisi Sertifikasi</div>
    </div>

  </div>

  {{-- ── Footer bar ── --}}
  <div class="footer-bar">
    <div class="footer-text">
      Sertifikat ini berlaku dari {{ $issuedAt->format('d F Y') }} s.d. {{ $expiredAt->format('d F Y') }}
      &nbsp;|&nbsp; Verifikasi: batikai.id/verify
    </div>
  </div>

</body>
</html>