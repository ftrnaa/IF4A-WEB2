@extends('layouts.user-dashboard')
@section('title', 'Dashboard — BatikAI')
@section('breadcrumb', 'Beranda')

@section('content')

@php
$today = \Carbon\Carbon::today();

$myLicenses = [
    ['name'=>'Sido Mukti',  'cat'=>'Klasik',      'date'=>'2026-04-13','img'=>'batik1'],
    ['name'=>'Mega Mendung','cat'=>'Pesisir',      'date'=>'2025-12-10','img'=>'batik3'],
    ['name'=>'Parang Rusak','cat'=>'Pesisir',      'date'=>'2025-04-05','img'=>'batik5'],
];
@endphp

{{-- Welcome Banner --}}
<div class="user-welcome">
    <div>
        <h1 class="user-welcome__title">Halo, <em>Rina!</em> 👋</h1>
        <p class="user-welcome__sub">Kamu punya 3 lisensi aktif dan 1 sertifikat siap diunduh.</p>
    </div>
    <a href="/koleksi" class="user-welcome__action">🛍 Jelajahi Motif Baru</a>
</div>

{{-- Stats --}}
<div class="user-stats-grid">
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Motif Dimiliki</p>
            <p class="user-stat-card__value">3</p>
            <p class="user-stat-card__sub">lisensi aktif</p>
        </div>
        <div class="user-stat-card__icon usc-gold">🎨</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Sertifikat</p>
            <p class="user-stat-card__value">3</p>
            <p class="user-stat-card__sub">siap diunduh</p>
        </div>
        <div class="user-stat-card__icon usc-green">📜</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Total Pembelian</p>
            <p class="user-stat-card__value">Rp 350 rb</p>
            <p class="user-stat-card__sub">3 transaksi</p>
        </div>
        <div class="user-stat-card__icon usc-brown">💳</div>
    </div>
    <div class="user-stat-card">
        <div>
            <p class="user-stat-card__label">Lisensi Hampir Habis</p>
            <p class="user-stat-card__value">1</p>
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
            <a href="/dashboard/lisensi" class="user-card__action">Semua lisensi →</a>
        </div>
        <div class="user-card__body" style="padding-top:.6rem">
            @foreach($myLicenses as $lic)
            @php
                $buyDate    = \Carbon\Carbon::parse($lic['date']);
                $expiryDate = $buyDate->copy()->addYear();
                $daysLeft   = $today->diffInDays($expiryDate, false);
                $total      = 365;
                $elapsed    = $total - max(0, $daysLeft);
                $pct        = max(0, min(100, round(($elapsed / $total) * 100)));

                if ($daysLeft < 0)       { $status = 'expired';  $statusClass = 'status-badge--failed'; }
                elseif ($daysLeft <= 30) { $status = 'expiring'; $statusClass = 'status-badge--pending'; }
                else                     { $status = 'active';   $statusClass = 'status-badge--paid'; }

                $progressClass = $status === 'expiring' ? 'license-progress__fill--warn'
                               : ($status === 'expired' ? 'license-progress__fill--expired' : '');
                $statusLabel = ['active'=>'Aktif','expiring'=>'Hampir Habis','expired'=>'Kedaluwarsa'];
            @endphp
            <div class="license-item">
                <img src="https://picsum.photos/seed/{{ $lic['img'] }}/100/100"
                     class="license-item__img" alt="{{ $lic['name'] }}">
                <div style="flex:1;min-width:0">
                    <p class="license-item__cat">{{ $lic['cat'] }}</p>
                    <p class="license-item__name">{{ $lic['name'] }}</p>
                    <div class="license-item__dates">
                        <div class="license-item__date-group">
                            <p class="license-item__date-lbl">Tgl Beli</p>
                            <p class="license-item__date-val">{{ $buyDate->format('d M Y') }}</p>
                        </div>
                        <div class="license-item__date-group">
                            <p class="license-item__date-lbl">Berakhir</p>
                            <p class="license-item__date-val" style="color:{{ $status === 'expired' ? '#C0392B' : ($status === 'expiring' ? '#B8610A' : 'inherit') }}">
                                {{ $expiryDate->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="license-item__status">
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel[$status] }}</span>
                    <div class="license-progress">
                        <div class="license-progress__fill {{ $progressClass }}" style="width:{{ $pct }}%"></div>
                    </div>
                    @if($status !== 'expired')
                        <span style="font-size:.68rem;color:var(--clr-text-muted)">
                            {{ $daysLeft }} hari lagi
                        </span>
                    @else
                        <span style="font-size:.68rem;color:#C0392B">Berakhir</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Aktivitas --}}
    <div class="user-card">
        <div class="user-card__header">
            <p class="user-card__title">🕐 Aktivitas</p>
        </div>
        <div class="user-card__body" style="padding-top:.4rem">
            <div class="user-activity-item">
                <div class="user-activity-icon uai-purchase">🛍</div>
                <div class="user-activity-text">Beli <strong>Sido Mukti</strong></div>
                <div class="user-activity-time">13 Apr</div>
            </div>
            <div class="user-activity-item">
                <div class="user-activity-icon uai-cert">📜</div>
                <div class="user-activity-text">Sertifikat <strong>Sido Mukti</strong> diterima</div>
                <div class="user-activity-time">13 Apr</div>
            </div>
            <div class="user-activity-item">
                <div class="user-activity-icon uai-purchase">🛍</div>
                <div class="user-activity-text">Beli <strong>Mega Mendung</strong></div>
                <div class="user-activity-time">10 Des</div>
            </div>
            <div class="user-activity-item">
                <div class="user-activity-icon uai-license">🛡</div>
                <div class="user-activity-text">Lisensi <strong>Parang Rusak</strong> aktif</div>
                <div class="user-activity-time">5 Apr '25</div>
            </div>
            <div class="user-activity-item">
                <div class="user-activity-icon uai-profile">👤</div>
                <div class="user-activity-text">Profil diperbarui</div>
                <div class="user-activity-time">1 Apr '25</div>
            </div>
        </div>
    </div>

</div>

{{-- Sertifikat Terbaru --}}
<div class="user-card">
    <div class="user-card__header">
        <p class="user-card__title">📜 Sertifikat Terbaru</p>
        <a href="/dashboard/sertifikat" class="user-card__action">Lihat semua →</a>
    </div>
    <div class="user-card__body" style="padding-top:.5rem">
        @php
        $certs = [
            ['name'=>'Sertifikat Keaslian — Sido Mukti',  'type'=>'Sertifikat','date'=>'13 Apr 2026','img'=>'batik1'],
            ['name'=>'Lisensi Komersial — Sido Mukti',    'type'=>'Lisensi',   'date'=>'13 Apr 2026','img'=>'batik1'],
            ['name'=>'Sertifikat Keaslian — Mega Mendung','type'=>'Sertifikat','date'=>'10 Des 2025','img'=>'batik3'],
        ];
        @endphp
        @foreach($certs as $cert)
        <div class="cert-item">
            <div class="cert-item__icon">
                {{ $cert['type'] === 'Sertifikat' ? '📜' : '📄' }}
            </div>
            <div>
                <p class="cert-item__name">{{ $cert['name'] }}</p>
                <p class="cert-item__meta">{{ $cert['type'] }} · Diterima {{ $cert['date'] }}</p>
            </div>
            <div class="cert-item__actions">
                <button class="cert-btn cert-btn--view"
                        onclick="viewCert('{{ $cert['name'] }}','{{ $cert['date'] }}')">
                    👁 Lihat
                </button>
                <button class="cert-btn cert-btn--dl" onclick="userToast('✓ File diunduh')">
                    ⬇ Unduh
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

@include('pages.users.cert-modal')
@endsection