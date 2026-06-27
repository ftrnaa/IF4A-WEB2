@extends('layouts.admin')
@section('title', 'Dashboard — Admin BatikAI')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ── Page Header ── --}}
<div class="dash-header">
    <div class="dash-header__left">
        <h1 class="dash-header__title">Selamat datang, Admin 👋</h1>
        <p class="dash-header__sub">Ringkasan performa BatikAI — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="dash-header__right">
        <div class="period-selector" id="period-selector">
            <button class="period-btn {{ $period === '3b' ? 'active' : '' }}" data-period="3b">
                3 Bulan
            </button>

            <button class="period-btn {{ $period === '6b' ? 'active' : '' }}" data-period="6b">
                6 Bulan
            </button>

            <button class="period-btn {{ $period === '1t' ? 'active' : '' }}" data-period="1t">
                1 Tahun
            </button>

            <button class="period-btn {{ $period === 'custom' ? 'active' : '' }}" data-period="custom">
                Custom
            </button>
        </div>

        {{-- Kotak input rentang tanggal custom — toggle lewat JS, validasi minimal 3 bulan juga di JS --}}
        <div class="period-custom-range {{ $period === 'custom' ? 'active' : '' }}" id="custom-range-box">
            <input type="date" id="startDate" value="{{ $startDate ?? '' }}">
            <span>—</span>
            <input type="date" id="endDate" value="{{ $endDate ?? '' }}">
            <button type="button" class="period-custom-range__apply" id="applyCustomRange">Terapkan</button>
            <p class="period-custom-range__error" id="customRangeError"></p>
        </div>

        <div class="export-btn-group">
            <button class="btn-export btn-export--pdf" onclick="exportReport('pdf')">
                <span>📄</span> Export PDF
            </button>
            <button class="btn-export btn-export--excel" onclick="exportReport('excel')">
                <span>📊</span> Export Excel
            </button>
        </div>
    </div>
</div>

{{-- ── KPI Cards ── --}}
<div class="kpi-grid" id="kpi-grid">

    <div class="kpi-card">
        <div class="kpi-card__icon kpi-card__icon--gold">💰</div>
        <div class="kpi-card__body">
            <p class="kpi-card__label">Total Pendapatan</p>
            <p class="kpi-card__value" id="kpi-revenue">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="kpi-card__change kpi-card__change--up">▲ dari pesanan lunas</p>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-card__icon kpi-card__icon--green">🛍️</div>
        <div class="kpi-card__body">
            <p class="kpi-card__label">Produk Terjual</p>
            <p class="kpi-card__value" id="kpi-sold">{{ number_format($productsSold) }}</p>
            <p class="kpi-card__change kpi-card__change--up">▲ total pesanan lunas</p>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-card__icon kpi-card__icon--brown">👥</div>
        <div class="kpi-card__body">
            <p class="kpi-card__label">Pembeli Aktif</p>
            <p class="kpi-card__value" id="kpi-buyers">{{ number_format($activeBuyers) }}</p>
            <p class="kpi-card__change kpi-card__change--up">▲ pengguna unik</p>
        </div>
    </div>

</div>

{{-- ── Chart + Activity ── --}}
<div class="dash-row">

    {{-- Revenue Bar Chart --}}
    <div class="dash-card dash-card--wide">
        <div class="dash-card__header">
            <p class="dash-card__title" id="chart-title">
                Pendapatan —
                @switch($period)
                    @case('7h') 7 Hari Terakhir @break
                    @case('30h') 30 Hari Terakhir @break
                    @case('6b') 6 Bulan Terakhir @break
                    @case('1t') 1 Tahun Terakhir @break
                    @case('custom') Periode Custom @break
                    @default 3 Bulan Terakhir
                @endswitch
            </p>
            <a href="/admin/laporan" class="dash-card__action">Lihat laporan →</a>
        </div>
        <div class="dash-card__body">
            <div class="bar-chart" id="bar-chart">
                @php $maxVal = max($chartValues ?: [0]) ?: 1; @endphp
                @foreach($chartLabels as $idx => $label)
                    @php
                        $val = $chartValues[$idx] ?? 0;
                        $pct = max(round(($val / $maxVal) * 100), 2);
                    @endphp
                    <div class="bar-chart__group">
                        <div class="bar-chart__bar" style="height: {{ $pct }}%">
                            <span class="bar-chart__tooltip">Rp {{ number_format($val, 0, ',', '.') }}</span>
                        </div>
                        <span class="bar-chart__label">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="dash-card dash-card--narrow">
        <div class="dash-card__header">
            <p class="dash-card__title">Aktivitas Terkini</p>
        </div>
        <div class="dash-card__body activity-feed">
            @forelse($transactions->take(6) as $tx)
                @php $buyerName = $tx->nama ?? 'Pengguna'; @endphp
                <div class="activity-item">
                    <div class="activity-item__icon
                        {{ $tx->status === 'paid' ? 'activity-item__icon--sale' : '' }}
                        {{ $tx->status === 'pending' ? 'activity-item__icon--warn' : '' }}
                        {{ $tx->status === 'cancelled' ? 'activity-item__icon--fail' : '' }}">
                        {{ $tx->status === 'paid' ? '💳' : ($tx->status === 'pending' ? '⏳' : '❌') }}
                    </div>
                    <div class="activity-item__text">
                        <strong>{{ $buyerName !== '' ? $buyerName : 'Pengguna' }}</strong>
                        — {{ $tx->batik->nama ?? 'Produk' }}
                        <span class="status-pill status-pill--{{ $tx->status }}">
                            {{ $tx->status === 'paid' ? 'Lunas' : ($tx->status === 'pending' ? 'Pending' : 'Dibatalkan') }}
                        </span>
                    </div>
                    <div class="activity-item__time">
                        {{ $tx->created_at->diffForHumans(null, true, true) }}
                    </div>
                </div>
            @empty
                <p class="dash-empty">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- ── Recent Transactions Table ── --}}
<div class="dash-card" style="margin-top:1.5rem">
    <div class="dash-card__header">
        <p class="dash-card__title">Transaksi Terbaru</p>
        <a href="/admin/transaksi" class="dash-card__action">Lihat semua →</a>
    </div>
    <div class="table-wrap">
        <table class="dash-table">
            <thead>
                <tr>
                    <th>Pembeli</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    @php $buyerName = $tx->nama ?? 'Pengguna'; @endphp
                    <tr>
                        <td>
                            <div class="dash-table__user">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($buyerName !== '' ? $buyerName : 'U') }}&background=8B6914&color=fff&size=36"
                                     class="dash-table__avatar"
                                     alt="{{ $buyerName }}">
                                <div>
                                    <p class="dash-table__name">{{ $buyerName !== '' ? $buyerName : '—' }}</p>
                                    <p class="dash-table__email">{{ $tx->email ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="dash-table__product">
                                @if(!empty($tx->batik->preview_image))
                                    <img src="https://btx.agunghakase.my.id/api/image/{{ $tx->batik->preview_image }}"
                                         class="dash-table__product-img"
                                         alt="{{ $tx->batik->nama ?? '—' }}"
                                         onerror="this.src='https://placehold.co/40x40/EAE0D0/7C6A56?text=%20'">
                                @else
                                    <div class="dash-table__product-img dash-table__product-img--placeholder">🧵</div>
                                @endif
                                <span class="dash-table__product-name">{{ $tx->batik->nama ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="dash-table__amount">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                        <td class="dash-table__time">{{ $tx->created_at->format('d M, H:i') }}</td>
                        <td>
                            <span class="status-pill status-pill--{{ $tx->status }}">
                                @if($tx->status === 'paid') Lunas
                                @elseif($tx->status === 'pending') Menunggu
                                @elseif($tx->status === 'cancelled') Dibatalkan
                                @else Gagal
                                @endif
                            </span>
                        </td>
                        <td>
                            <button class="btn-action btn-action--primary" onclick="openDetailModal({{ $tx->id }})">
                                🔍 Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="dash-empty">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Order Detail Modal ── --}}
@include('pages.admin.partials.modal-order-detail')

{{-- ── Export Overlay ── --}}
<div class="export-overlay" id="export-overlay">
    <div class="export-spinner-box">
        <div class="export-spinner"></div>
        <strong id="export-overlay-title">Menyiapkan file...</strong>
        <p id="export-overlay-msg">Harap tunggu, laporan sedang diproses</p>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush