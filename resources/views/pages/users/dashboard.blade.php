@extends('layouts.user-dashboard')
@section('title', 'Dashboard — BatikAI')
@section('breadcrumb', 'Beranda')

@section('content')

{{-- Welcome Banner --}}
<div class="user-welcome">
    <div>
        <h1 class="user-welcome__title">Halo, <em>{{ $user->name }}!</em> 👋</h1>
        <p class="user-welcome__sub">
            Kamu punya {{ $stats['active_licenses'] }} lisensi aktif
            dan {{ $stats['total_certs'] }} sertifikat siap diunduh.
        </p>
    </div>
    <a href="{{ route('koleksi') }}" class="user-welcome__action">🛍 Jelajahi Motif Baru</a>
</div>

{{-- Stats --}}
<div class="user-stats-grid">
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Motif Dimiliki</p>
            <p class="user-stat-card__value">{{ $stats['active_licenses'] }}</p>
            <p class="user-stat-card__sub">lisensi aktif</p>
        </div>
        <div class="user-stat-card__icon usc-gold">🎨</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Sertifikat</p>
            <p class="user-stat-card__value">{{ $stats['total_certs'] }}</p>
            <p class="user-stat-card__sub">siap diunduh</p>
        </div>
        <div class="user-stat-card__icon usc-green">📜</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Total Pembelian</p>
            <p class="user-stat-card__value">Rp {{ number_format($stats['total_spent'] / 1000, 0, ',', '.') }} rb</p>
            <p class="user-stat-card__sub">{{ $stats['transaction_count'] }} transaksi</p>
        </div>
        <div class="user-stat-card__icon usc-brown">💳</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Lisensi Hampir Habis</p>
            <p class="user-stat-card__value">{{ $stats['expiring_licenses'] }}</p>
            <p class="user-stat-card__sub">perlu diperhatikan</p>
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
            <a href="{{ route('user.licenses.index') }}" class="user-card__action">Semua lisensi →</a>
        </div>
        <div class="user-card__body" style="padding-top:.6rem">
            @forelse($myLicenses as $lic)
            <div class="license-item">
                <img src="{{ $lic['image_url'] }}"
                     class="license-item__img" alt="{{ $lic['name'] }}">
                <div style="flex:1;min-width:0">
                    <p class="license-item__cat">{{ $lic['cat'] }}</p>
                    <p class="license-item__name">{{ $lic['name'] }}</p>
                    <div class="license-item__dates">
                        <div class="license-item__date-group">
                            <p class="license-item__date-lbl">Tgl Beli</p>
                            <p class="license-item__date-val">{{ $lic['buy_date'] }}</p>
                        </div>
                        <div class="license-item__date-group">
                            <p class="license-item__date-lbl">Berakhir</p>
                            <p class="license-item__date-val"
                               style="color:{{ $lic['status'] === 'expired' ? '#C0392B' : ($lic['status'] === 'expiring' ? '#B8610A' : 'inherit') }}">
                                {{ $lic['expiry_date'] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="license-item__status">
                    @php
                        $badgeClass = match($lic['status']) {
                            'expired'  => 'status-badge--failed',
                            'expiring' => 'status-badge--pending',
                            default    => 'status-badge--paid',
                        };
                        $progressClass = match($lic['status']) {
                            'expiring' => 'license-progress__fill--warn',
                            'expired'  => 'license-progress__fill--expired',
                            default    => '',
                        };
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">{{ $lic['status_label'] }}</span>
                    <div class="license-progress">
                        <div class="license-progress__fill {{ $progressClass }}"
                             style="width:{{ $lic['progress_pct'] }}%"></div>
                    </div>
                    @if($lic['status'] !== 'expired')
                        <span style="font-size:.68rem;color:var(--clr-text-muted)">
                            {{ $lic['days_left'] }} hari lagi
                        </span>
                    @else
                        <span style="font-size:.68rem;color:#C0392B">Berakhir</span>
                    @endif
                </div>
            </div>
            @empty
                <p style="color:var(--clr-text-muted);padding:.5rem 0">Belum ada lisensi aktif.</p>
            @endforelse
        </div>
    </div>

    {{-- Aktivitas --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">🕐 Aktivitas</p>
        </div>
        <div class="user-card__body" style="padding-top:.4rem">
            @forelse($activities as $act)
            <div class="user-activity-item">
                <div class="user-activity-icon uai-{{ $act['type'] }}">{{ $act['icon'] }}</div>
                <div class="user-activity-text">{!! $act['description'] !!}</div>
                <div class="user-activity-time">{{ $act['date'] }}</div>
            </div>
            @empty
                <p style="color:var(--clr-text-muted);padding:.5rem 0">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Sertifikat Terbaru --}}
<div class="user-card">
    <div class="user-card__header">
        <p class="user-card__title">📜 Sertifikat Terbaru</p>
        <a href="{{ route('user.certificates.index') }}" class="user-card__action">Lihat semua →</a>
    </div>
    <div class="user-card__body" style="padding-top:.5rem">
        @forelse($latestCerts as $cert)
        <div class="cert-item">
            <div class="cert-item__icon">{{ $cert['icon'] }}</div>
            <div>
                <p class="cert-item__name">{{ $cert['display_name'] }}</p>
                <p class="cert-item__meta">{{ $cert['type'] }} · Diterima {{ $cert['issued_at'] }}</p>
            </div>
            <div class="cert-item__actions">
                <button class="cert-btn cert-btn--view"
                        onclick="viewCert('{{ $cert['display_name'] }}', '{{ $cert['issued_at'] }}')">
                    👁 Lihat
                </button>
                @if($cert['has_file'])
                    <a href="{{ $cert['download_url'] }}" class="cert-btn cert-btn--dl">
                        ⬇ Unduh
                    </a>
                @else
                    <button class="cert-btn cert-btn--dl"
                            onclick="userToast('✓ File sedang disiapkan')">
                        ⬇ Unduh
                    </button>
                @endif
            </div>
        </div>
        @empty
            <p style="color:var(--clr-text-muted);padding:.5rem 0">Belum ada sertifikat.</p>
        @endforelse
    </div>
</div>

@include('pages.users.cert-modal')
@endsection
