@extends('layouts.admin')
@section('title', 'Dashboard — Admin BatikAI')
@section('breadcrumb', 'Dashboard')

@section('content')

<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Selamat datang, Admin 👋</h1>
        <p>Ringkasan performa BatikAI hari ini — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
        <div class="period-selector">
            <button class="period-btn" onclick="setPeriod(this,'7h')">7 Hari</button>
            <button class="period-btn active" onclick="setPeriod(this,'30h')">30 Hari</button>
            <button class="period-btn" onclick="setPeriod(this,'3b')">3 Bulan</button>
            <button class="period-btn" onclick="setPeriod(this,'1t')">1 Tahun</button>
        </div>
        <div class="export-btn-group">
            <button class="btn-export btn-export--pdf" onclick="exportReport('pdf')">
                <span class="btn-export__icon">📄</span> Export PDF
            </button>
            <button class="btn-export btn-export--excel" onclick="exportReport('excel')">
                <span class="btn-export__icon">📊</span> Export Excel
            </button>
            
        </div>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="admin-stats-grid">

    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Pendapatan</p>
            <p class="admin-stat-card__value">Rp 24,7 Jt</p>
            <p class="admin-stat-card__change up">▲ 12% dari bulan lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--gold">💰</div>
    </div>

    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Produk Terjual</p>
            <p class="admin-stat-card__value">1.284</p>
            <p class="admin-stat-card__change up">▲ 8% dari bulan lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--green">🛍️</div>
    </div>

    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Pembeli Aktif</p>
            <p class="admin-stat-card__value">986</p>
            <p class="admin-stat-card__change up">▲ 5% dari bulan lalu</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--brown">👥</div>
    </div>

    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Menunggu Bayar</p>
            <p class="admin-stat-card__value">37</p>
            <p class="admin-stat-card__change down">▼ perlu tindakan</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--blue">⏳</div>
    </div>

</div>

{{-- ── Chart + Top Products ── --}}
<div class="admin-grid-3-1">

    {{-- Revenue Chart --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Pendapatan Bulanan</p>
            <a href="/admin/laporan" class="admin-card__action">Lihat laporan →</a>
        </div>
        <div class="admin-card__body">
            <div class="chart-wrap" id="revenue-chart">
                @php
                    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                    $values = [35,52,41,63,58,72,68,85,79,91,88,100];
                @endphp
                @foreach($months as $i => $month)
                <div class="chart-bar-group">
                    <div class="chart-bar {{ $i === 11 ? 'chart-bar--gold' : '' }}"
                         style="height: {{ $values[$i] }}%">
                        <span class="chart-bar__tooltip">Rp {{ number_format($values[$i] * 250000, 0, ',', '.') }}</span>
                    </div>
                    <span class="chart-label">{{ $month }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    

</div>

{{-- ── Recent Transactions + Activity ── --}}
<div class="admin-grid-3-1">

    {{-- Recent Transactions --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Transaksi Terbaru</p>
            <a href="/admin/transaksi" class="admin-card__action">Lihat semua →</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $txs = [
                        ['name'=>'Rina Susanti','email'=>'rina@mail.com','product'=>'Sido Mukti','amount'=>'Rp 120.000','status'=>'paid','img'=>'person4'],
                        ['name'=>'Budi Hartono','email'=>'budi@mail.com','product'=>'Kawung','amount'=>'Rp 110.000','status'=>'pending','img'=>'person5'],
                        ['name'=>'Dewi Lestari','email'=>'dewi@mail.com','product'=>'Mega Mendung','amount'=>'Rp 135.000','status'=>'paid','img'=>'person6'],
                        ['name'=>'Agus Prasetyo','email'=>'agus@mail.com','product'=>'Truntum','amount'=>'Rp 150.000','status'=>'failed','img'=>'person7'],
                    ];
                    $statusLabel = ['paid'=>'Lunas','pending'=>'Menunggu','failed'=>'Gagal'];
                    @endphp
                    @foreach($txs as $tx)
                    <tr>
                        <td>
                            <div class="admin-table__user">
                                <img src="https://picsum.photos/seed/{{ $tx['img'] }}/40/40" class="admin-table__avatar" alt="{{ $tx['name'] }}">
                                <div>
                                    <p class="admin-table__user-name">{{ $tx['name'] }}</p>
                                    <p class="admin-table__user-email">{{ $tx['email'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $tx['product'] }}</td>
                        <td>{{ $tx['amount'] }}</td>
                        <td><span class="status-badge status-badge--{{ $tx['status'] }}">{{ $statusLabel[$tx['status']] }}</span></td>
                        <td>
                            @if($tx['status'] === 'paid')
                                <button class="admin-action-btn admin-action-btn--primary" onclick="openSendModal('{{ $tx['name'] }}','{{ $tx['product'] }}')">📄 Kirim</button>
                            @else
                                <span style="font-size:.75rem;color:var(--clr-text-muted)">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <p class="admin-card__title">Aktivitas Terkini</p>
        </div>
        <div class="admin-card__body" style="padding-top:.4rem">
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--sale">💳</div>
                <div class="activity-item__text"><strong>Rina Susanti</strong> membeli Sido Mukti</div>
                <div class="activity-item__time">2 mnt</div>
            </div>
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--cert">📜</div>
                <div class="activity-item__text">Sertifikat dikirim ke <strong>Dewi Lestari</strong></div>
                <div class="activity-item__time">15 mnt</div>
            </div>
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--product">🎨</div>
                <div class="activity-item__text">Motif baru <strong>Sekar Jagad</strong> ditambahkan</div>
                <div class="activity-item__time">1 jam</div>
            </div>
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--user">👤</div>
                <div class="activity-item__text"><strong>Rizky Nugroho</strong> mendaftar sebagai pengguna baru</div>
                <div class="activity-item__time">2 jam</div>
            </div>
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--sale">💳</div>
                <div class="activity-item__text"><strong>Budi Hartono</strong> menunggu konfirmasi bayar</div>
                <div class="activity-item__time">3 jam</div>
            </div>
            <div class="activity-item">
                <div class="activity-item__icon activity-item__icon--cert">📜</div>
                <div class="activity-item__text">Lisensi dikirim ke <strong>Sari Kusuma</strong> — Parang Rusak</div>
                <div class="activity-item__time">5 jam</div>
            </div>
        </div>
    </div>

</div>

{{-- ── Send Certificate Modal ── --}}
@include('pages.admin.partials.modal-send-cert')

{{-- ── Export Loading Overlay ── --}}
<div class="export-overlay" id="export-overlay">
    <div class="export-spinner-box">
        <div class="export-spinner"></div>
        <strong id="export-overlay-title">Menyiapkan file...</strong>
        <p id="export-overlay-msg">Harap tunggu, laporan sedang diproses</p>
    </div>
</div>

@endsection