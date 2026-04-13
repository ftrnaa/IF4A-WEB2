@extends('layouts.admin')
@section('title', 'Laporan — Admin BatikAI')
@section('breadcrumb', 'Laporan')

@section('content')

{{-- ── Header + Export Buttons ── --}}
<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Laporan Penjualan</h1>
        <p>Analisis performa produk, pendapatan, dan transaksi secara menyeluruh.</p>
    </div>

    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
        {{-- Period Selector --}}
        <div class="period-selector">
            <button class="period-btn" onclick="setPeriod(this,'7h')">7 Hari</button>
            <button class="period-btn active" onclick="setPeriod(this,'30h')">30 Hari</button>
            <button class="period-btn" onclick="setPeriod(this,'3b')">3 Bulan</button>
            <button class="period-btn" onclick="setPeriod(this,'1t')">1 Tahun</button>
        </div>

        {{-- Export Buttons --}}
        <div class="export-btn-group">
            <button class="btn-export btn-export--pdf" onclick="exportReport('pdf')">
                <span class="btn-export__icon">📄</span> Export PDF
            </button>
            <button class="btn-export btn-export--excel" onclick="exportReport('excel')">
                <span class="btn-export__icon">📊</span> Export Excel
            </button>
            <button class="btn-export btn-export--csv" onclick="exportReport('csv')">
                <span class="btn-export__icon">📋</span> CSV
            </button>
        </div>
    </div>
</div>

{{-- ── Summary Cards ── --}}
<div class="laporan-summary-grid">
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Pendapatan</p>
            <p class="admin-stat-card__value">Rp 24,7 Jt</p>
            <p class="admin-stat-card__change up">▲ 12% vs periode lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--gold">💰</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Transaksi</p>
            <p class="admin-stat-card__value">1.284</p>
            <p class="admin-stat-card__change up">▲ 8% vs periode lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--green">🛍️</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Rata-rata Nilai Transaksi</p>
            <p class="admin-stat-card__value">Rp 127 rb</p>
            <p class="admin-stat-card__change up">▲ 3% vs periode lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--brown">📈</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Tingkat Konversi</p>
            <p class="admin-stat-card__value">68,4%</p>
            <p class="admin-stat-card__change down">▼ 2% vs periode lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--blue">🎯</div>
    </div>
</div>

{{-- ── Revenue Chart + Category Breakdown ── --}}
<div class="admin-grid-3-1" style="margin-bottom:1.4rem">

    {{-- Revenue & Orders Chart --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Pendapatan & Jumlah Transaksi</p>
            <div class="chart-legend">
                <div class="chart-legend-item">
                    <div class="chart-legend-color" style="background:var(--clr-green)"></div>
                    Pendapatan
                </div>
                <div class="chart-legend-item">
                    <div class="chart-legend-color" style="background:var(--clr-gold)"></div>
                    Transaksi
                </div>
            </div>
        </div>
        <div class="admin-card__body">
            @php
            $chartData = [
                ['lbl'=>'Jan', 'rev'=>35, 'ord'=>28],
                ['lbl'=>'Feb', 'rev'=>52, 'ord'=>44],
                ['lbl'=>'Mar', 'rev'=>41, 'ord'=>35],
                ['lbl'=>'Apr', 'rev'=>63, 'ord'=>55],
                ['lbl'=>'Mei', 'rev'=>58, 'ord'=>49],
                ['lbl'=>'Jun', 'rev'=>72, 'ord'=>60],
                ['lbl'=>'Jul', 'rev'=>68, 'ord'=>57],
                ['lbl'=>'Agt', 'rev'=>85, 'ord'=>72],
                ['lbl'=>'Sep', 'rev'=>79, 'ord'=>66],
                ['lbl'=>'Okt', 'rev'=>91, 'ord'=>78],
                ['lbl'=>'Nov', 'rev'=>88, 'ord'=>74],
                ['lbl'=>'Des', 'rev'=>100,'ord'=>85],
            ];
            @endphp
            <div class="chart-wrap--multi">
                @foreach($chartData as $d)
                <div class="chart-group">
                    <div class="chart-bar--revenue" style="height:{{ $d['rev'] }}%">
                        <span class="chart-bar__tooltip">Rp {{ number_format($d['rev'] * 250000, 0, ',', '.') }}</span>
                    </div>
                    <div class="chart-bar--orders" style="height:{{ $d['ord'] }}%">
                        <span class="chart-bar__tooltip">{{ round($d['ord'] * 1.07) }} transaksi</span>
                    </div>
                    <span class="chart-group__label">{{ $d['lbl'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Category Donut --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Penjualan per Kategori</p>
        </div>
        <div class="admin-card__body" style="display:flex;flex-direction:column;align-items:center;gap:1.4rem">
            <div style="position:relative;width:160px;height:160px">
                <div class="donut-chart"></div>
                <div class="donut-center">
                    <span class="donut-center__num">1.284</span>
                    <span class="donut-center__lbl">TOTAL</span>
                </div>
            </div>
            <div class="donut-legend">
                @php
                $cats = [
                    ['name'=>'Klasik',       'pct'=>'38%', 'color'=>'var(--clr-green)'],
                    ['name'=>'Pesisir',       'pct'=>'27%', 'color'=>'var(--clr-gold)'],
                    ['name'=>'Modern',        'pct'=>'17%', 'color'=>'var(--clr-brown)'],
                    ['name'=>'Kontemporer',   'pct'=>'11%', 'color'=>'var(--clr-green-mid)'],
                    ['name'=>'Lainnya',       'pct'=>'7%',  'color'=>'var(--clr-cream-dark)'],
                ];
                @endphp
                @foreach($cats as $c)
                <div class="donut-legend-item">
                    <div class="donut-legend-dot" style="background:{{ $c['color'] }}"></div>
                    <span>{{ $c['name'] }}</span>
                    <span class="donut-legend-pct">{{ $c['pct'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ── Top Products Table + Pembayaran Breakdown ── --}}
<div class="admin-grid-2" style="margin-bottom:1.4rem">

    {{-- Top Products --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Produk Terlaris</p>
            <span style="font-size:.75rem;color:var(--clr-text-muted)">30 hari terakhir</span>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Motif</th>
                        <th>Terjual</th>
                        <th>Pendapatan</th>
                        <th>Tren</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $topProds = [
                        ['rank'=>1,'name'=>'Sido Mukti',  'cat'=>'Klasik',       'sold'=>214,'rev'=>'25,6 Jt','trend'=>'up',  'img'=>'batik1'],
                        ['rank'=>2,'name'=>'Parang Rusak','cat'=>'Pesisir',      'sold'=>187,'rev'=>'17,8 Jt','trend'=>'up',  'img'=>'batik2'],
                        ['rank'=>3,'name'=>'Mega Mendung','cat'=>'Pesisir',      'sold'=>164,'rev'=>'22,1 Jt','trend'=>'down','img'=>'batik3'],
                        ['rank'=>4,'name'=>'Kawung',      'cat'=>'Klasik',       'sold'=>143,'rev'=>'15,7 Jt','trend'=>'up',  'img'=>'batik4'],
                        ['rank'=>5,'name'=>'Truntum',     'cat'=>'Modern',       'sold'=>128,'rev'=>'19,2 Jt','trend'=>'up',  'img'=>'batik5'],
                        ['rank'=>6,'name'=>'Sekar Jagad', 'cat'=>'Kontemporer',  'sold'=>98, 'rev'=>'17,2 Jt','trend'=>'down','img'=>'batik6'],
                    ];
                    @endphp
                    @foreach($topProds as $p)
                    <tr>
                        <td>
                            <span style="font-family:var(--font-display);font-size:.85rem;font-weight:700;color:var(--clr-gold)">#{{ $p['rank'] }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.6rem">
                                <img src="https://picsum.photos/seed/{{ $p['img'] }}/60/60" class="admin-table__motif-img" alt="{{ $p['name'] }}">
                                <div>
                                    <p style="font-weight:600;font-size:.85rem;color:var(--clr-brown-dark)">{{ $p['name'] }}</p>
                                    <p style="font-size:.72rem;color:var(--clr-text-muted)">{{ $p['cat'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:600;font-size:.88rem">{{ $p['sold'] }}</td>
                        <td style="font-weight:600;color:var(--clr-green);font-size:.88rem">Rp {{ $p['rev'] }}</td>
                        <td>
                            @if($p['trend'] === 'up')
                                <span style="color:#27AE60;font-size:.85rem;font-weight:600">▲</span>
                            @else
                                <span style="color:#E74C3C;font-size:.85rem;font-weight:600">▼</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment Status Breakdown --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Status Pembayaran</p>
            <span style="font-size:.75rem;color:var(--clr-text-muted)">30 hari terakhir</span>
        </div>
        <div class="admin-card__body">

            {{-- Visual bar --}}
            @php
            $payStats = [
                ['label'=>'Lunas',    'count'=>876, 'pct'=>68, 'color'=>'var(--clr-green)',      'badge'=>'paid'],
                ['label'=>'Menunggu', 'count'=>271, 'pct'=>21, 'color'=>'var(--clr-gold)',        'badge'=>'pending'],
                ['label'=>'Gagal',    'count'=>137, 'pct'=>11, 'color'=>'#E74C3C',                'badge'=>'failed'],
            ];
            @endphp

            {{-- Stacked bar --}}
            <div style="height:14px;border-radius:8px;overflow:hidden;display:flex;margin-bottom:1.4rem">
                @foreach($payStats as $s)
                <div style="width:{{ $s['pct'] }}%;background:{{ $s['color'] }};transition:width .4s"></div>
                @endforeach
            </div>

            <div style="display:flex;flex-direction:column;gap:.85rem">
                @foreach($payStats as $s)
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:.6rem">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $s['color'] }}"></div>
                        <span class="status-badge status-badge--{{ $s['badge'] }}">{{ $s['label'] }}</span>
                    </div>
                    <div style="text-align:right">
                        <span style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--clr-brown-dark)">{{ number_format($s['count'],0,',','.') }}</span>
                        <span style="font-size:.75rem;color:var(--clr-text-muted);margin-left:.4rem">{{ $s['pct'] }}%</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top:1.6rem;padding-top:1.2rem;border-top:1px solid rgba(200,169,110,.12)">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem">
                    <div style="background:rgba(39,174,96,.07);border-radius:10px;padding:.85rem;text-align:center">
                        <p style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:#1A7A43">Rp 22,4 Jt</p>
                        <p style="font-size:.7rem;color:var(--clr-text-muted);margin-top:.2rem">Sudah masuk</p>
                    </div>
                    <div style="background:rgba(230,126,34,.07);border-radius:10px;padding:.85rem;text-align:center">
                        <p style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:#B8610A">Rp 2,3 Jt</p>
                        <p style="font-size:.7rem;color:var(--clr-text-muted);margin-top:.2rem">Menunggu</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ── Full Transaction Log ── --}}
<div class="admin-card">
    <div class="admin-card__header">
        <p class="admin-card__title">Log Transaksi Lengkap</p>
        <div class="export-btn-group">
            <button class="btn-export btn-export--pdf" onclick="exportReport('pdf')">
                <span class="btn-export__icon">📄</span> PDF
            </button>
            <button class="btn-export btn-export--excel" onclick="exportReport('excel')">
                <span class="btn-export__icon">📊</span> Excel
            </button>
            <button class="btn-export btn-export--csv" onclick="exportReport('csv')">
                <span class="btn-export__icon">📋</span> CSV
            </button>
        </div>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Pembeli</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Tgl Beli</th>
                    <th>Tgl Berakhir</th>
                    <th>Metode Bayar</th>
                    <th>Status Bayar</th>
                    <th>Status Lisensi</th>
                    <th>Sertifikat</th>
                </tr>
            </thead>
            <tbody>
                @php
                $logData = [
                    ['id'=>'TRX-001','name'=>'Rina Susanti',  'email'=>'rina@mail.com',  'product'=>'Sido Mukti',  'amount'=>120000,'date'=>'2026-04-13','method'=>'Transfer Bank','status'=>'paid',   'cert'=>true, 'img'=>'person4', 'motif'=>'batik1'],
                    ['id'=>'TRX-002','name'=>'Budi Hartono',  'email'=>'budi@mail.com',  'product'=>'Kawung',      'amount'=>110000,'date'=>'2026-04-13','method'=>'QRIS',         'status'=>'pending','cert'=>false,'img'=>'person5', 'motif'=>'batik4'],
                    ['id'=>'TRX-003','name'=>'Dewi Lestari',  'email'=>'dewi@mail.com',  'product'=>'Mega Mendung','amount'=>135000,'date'=>'2026-04-12','method'=>'GoPay',        'status'=>'paid',   'cert'=>true, 'img'=>'person6', 'motif'=>'batik3'],
                    ['id'=>'TRX-004','name'=>'Agus Prasetyo', 'email'=>'agus@mail.com',  'product'=>'Truntum',     'amount'=>150000,'date'=>'2026-04-12','method'=>'OVO',          'status'=>'failed', 'cert'=>false,'img'=>'person7', 'motif'=>'batik2'],
                    ['id'=>'TRX-005','name'=>'Sari Kusuma',   'email'=>'sari@mail.com',  'product'=>'Parang Rusak','amount'=>95000, 'date'=>'2026-04-11','method'=>'Transfer Bank','status'=>'paid',   'cert'=>true, 'img'=>'person8', 'motif'=>'batik5'],
                    ['id'=>'TRX-006','name'=>'Rizky Nugroho', 'email'=>'rizky@mail.com', 'product'=>'Sekar Jagad', 'amount'=>175000,'date'=>'2026-04-11','method'=>'Dana',         'status'=>'pending','cert'=>false,'img'=>'person9', 'motif'=>'batik6'],
                    ['id'=>'TRX-007','name'=>'Hendra Wijaya', 'email'=>'hendra@mail.com','product'=>'Sido Mukti',  'amount'=>120000,'date'=>'2025-12-10','method'=>'QRIS',         'status'=>'paid',   'cert'=>true, 'img'=>'person10','motif'=>'batik1'],
                    ['id'=>'TRX-008','name'=>'Fitriana Putri','email'=>'fitri@mail.com', 'product'=>'Kawung',      'amount'=>110000,'date'=>'2025-04-20','method'=>'GoPay',        'status'=>'paid',   'cert'=>true, 'img'=>'person11','motif'=>'batik4'],
                    ['id'=>'TRX-009','name'=>'Wahyu Santoso', 'email'=>'wahyu@mail.com', 'product'=>'Truntum',     'amount'=>150000,'date'=>'2025-04-05','method'=>'Dana',         'status'=>'paid',   'cert'=>true, 'img'=>'person12','motif'=>'batik2'],
                ];
                $statusLabel = ['paid'=>'Lunas','pending'=>'Menunggu','failed'=>'Gagal'];
                $today = \Carbon\Carbon::today();
                @endphp

                @foreach($logData as $row)
                @php
                    $buyDate    = \Carbon\Carbon::parse($row['date']);
                    $expiryDate = $buyDate->copy()->addYear();
                    $daysLeft   = $today->diffInDays($expiryDate, false); // negative = sudah lewat

                    if ($row['status'] !== 'paid') {
                        $licenseStatus = 'none';       // tidak berlaku
                    } elseif ($daysLeft < 0) {
                        $licenseStatus = 'expired';    // sudah habis
                    } elseif ($daysLeft <= 30) {
                        $licenseStatus = 'expiring';   // hampir habis (≤30 hari)
                    } else {
                        $licenseStatus = 'active';     // masih aktif
                    }
                @endphp
                <tr>
                    <td style="font-size:.72rem;font-weight:700;color:var(--clr-text-muted)">{{ $row['id'] }}</td>

                    <td>
                        <div class="admin-table__user">
                            <img src="https://picsum.photos/seed/{{ $row['img'] }}/40/40" class="admin-table__avatar" alt="{{ $row['name'] }}">
                            <div>
                                <p class="admin-table__user-name">{{ $row['name'] }}</p>
                                <p class="admin-table__user-email">{{ $row['email'] }}</p>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <img src="https://picsum.photos/seed/{{ $row['motif'] }}/60/60" class="admin-table__motif-img" alt="{{ $row['product'] }}">
                            <span style="font-size:.85rem;font-weight:500">{{ $row['product'] }}</span>
                        </div>
                    </td>

                    <td style="font-weight:600;color:var(--clr-green)">Rp {{ number_format($row['amount'],0,',','.') }}</td>

                    {{-- Tgl Beli --}}
                    <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">
                        {{ $buyDate->format('d M Y') }}
                    </td>

                    {{-- Tgl Berakhir = +1 tahun, hanya tampil jika sudah lunas --}}
                    <td style="white-space:nowrap">
                        @if($row['status'] === 'paid')
                            <div>
                                <p style="font-size:.82rem;font-weight:600;color:{{ $licenseStatus === 'expired' ? '#C0392B' : ($licenseStatus === 'expiring' ? '#B8610A' : 'var(--clr-brown-dark)') }}">
                                    {{ $expiryDate->format('d M Y') }}
                                </p>
                                @if($licenseStatus === 'active')
                                    <p style="font-size:.7rem;color:var(--clr-text-muted)">{{ $daysLeft }} hari lagi</p>
                                @elseif($licenseStatus === 'expiring')
                                    <p style="font-size:.7rem;color:#B8610A;font-weight:600">⚠ {{ $daysLeft }} hari lagi</p>
                                @elseif($licenseStatus === 'expired')
                                    <p style="font-size:.7rem;color:#C0392B;font-weight:600">Berakhir {{ abs($daysLeft) }} hari lalu</p>
                                @endif
                            </div>
                        @else
                            <span style="font-size:.75rem;color:var(--clr-text-muted)">—</span>
                        @endif
                    </td>

                    <td style="font-size:.82rem">{{ $row['method'] }}</td>

                    <td><span class="status-badge status-badge--{{ $row['status'] }}">{{ $statusLabel[$row['status']] }}</span></td>

                    {{-- Status Lisensi --}}
                    <td>
                        @if($licenseStatus === 'active')
                            <span class="status-badge status-badge--paid">Aktif</span>
                        @elseif($licenseStatus === 'expiring')
                            <span class="status-badge status-badge--pending">Hampir Habis</span>
                        @elseif($licenseStatus === 'expired')
                            <span class="status-badge status-badge--failed">Kedaluwarsa</span>
                        @else
                            <span style="font-size:.75rem;color:var(--clr-text-muted)">—</span>
                        @endif
                    </td>

                    <td>
                        @if($row['cert'])
                            <span class="status-badge status-badge--sent">✓ Terkirim</span>
                        @else
                            <span style="font-size:.75rem;color:var(--clr-text-muted)">Belum</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination placeholder --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.6rem;border-top:1px solid rgba(200,169,110,.1)">
        <p style="font-size:.78rem;color:var(--clr-text-muted)">Menampilkan 1–9 dari 1.284 transaksi</p>
        <div style="display:flex;gap:.4rem">
            @foreach([1,2,3,'...',128] as $pg)
            <button style="width:32px;height:32px;border-radius:6px;border:1.5px solid {{ $pg === 1 ? 'var(--clr-brown-dark)' : 'rgba(200,169,110,.25)' }};background:{{ $pg === 1 ? 'var(--clr-brown-dark)' : 'transparent' }};color:{{ $pg === 1 ? 'var(--clr-cream-light)' : 'var(--clr-text-muted)' }};font-size:.78rem;cursor:pointer">{{ $pg }}</button>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Export Loading Overlay ── --}}
<div class="export-overlay" id="export-overlay">
    <div class="export-spinner-box">
        <div class="export-spinner"></div>
        <strong id="export-overlay-title">Menyiapkan file...</strong>
        <p id="export-overlay-msg">Harap tunggu, laporan sedang diproses</p>
    </div>
</div>

@endsection