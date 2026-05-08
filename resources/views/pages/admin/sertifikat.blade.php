@extends('layouts.admin')
@section('title', 'Sertifikat & Lisensi — Admin BatikAI')
@section('breadcrumb', 'Sertifikat & Lisensi')

@section('content')

<div class="admin-page-header">
    <h1>Sertifikat & Lisensi</h1>
    <p>Kelola dan kirim sertifikat serta file lisensi kepada pembeli yang sudah melunasi pembayaran.</p>
</div>

{{-- Stats strip --}}
<div class="admin-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.4rem">
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Sudah Terkirim</p>
            <p class="admin-stat-card__value">1.042</p>
            <p class="admin-stat-card__change up">▲ otomatis & manual</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--green">📜</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Belum Terkirim</p>
            <p class="admin-stat-card__value">24</p>
            <p class="admin-stat-card__change down">▼ perlu tindakan</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--blue">📭</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Terkirim Hari Ini</p>
            <p class="admin-stat-card__value">18</p>
            <p class="admin-stat-card__change up">▲ 6 manual</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--gold">✉️</div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <p class="admin-card__title">Daftar Pengiriman Sertifikat</p>
        <div style="display:flex;gap:.75rem">
            <select class="admin-form-select" style="width:auto;font-size:.78rem">
                <option>Semua Status</option>
                <option>Belum Terkirim</option>
                <option>Sudah Terkirim</option>
            </select>
            <input type="text" class="admin-form-input" placeholder="Cari..." style="width:180px;font-size:.78rem">
        </div>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pembeli</th>
                    <th>Produk / Motif</th>
                    <th>Tgl Beli</th>
                    <th>Tgl Berakhir</th>
                    <th>Sertifikat</th>
                    <th>Lisensi</th>
                    <th>Status Kirim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $today = \Carbon\Carbon::today();
                $certs = [
                    ['name'=>'Rina Susanti',  'email'=>'rina@mail.com',  'product'=>'Sido Mukti',   'date'=>'2026-04-13','cert_sent'=>true, 'license_sent'=>true, 'img'=>'person4', 'motif'=>'batik1'],
                    ['name'=>'Dewi Lestari',  'email'=>'dewi@mail.com',  'product'=>'Mega Mendung', 'date'=>'2026-04-12','cert_sent'=>true, 'license_sent'=>false,'img'=>'person6', 'motif'=>'batik3'],
                    ['name'=>'Sari Kusuma',   'email'=>'sari@mail.com',  'product'=>'Parang Rusak', 'date'=>'2026-04-11','cert_sent'=>true, 'license_sent'=>true, 'img'=>'person8', 'motif'=>'batik5'],
                    ['name'=>'Hendra Wijaya', 'email'=>'hendra@mail.com','product'=>'Kawung',       'date'=>'2026-04-10','cert_sent'=>false,'license_sent'=>false,'img'=>'person10','motif'=>'batik4'],
                    ['name'=>'Fitriana Putri','email'=>'fitri@mail.com', 'product'=>'Truntum',      'date'=>'2026-04-09','cert_sent'=>false,'license_sent'=>false,'img'=>'person11','motif'=>'batik2'],
                ];
                @endphp
                @foreach($certs as $c)
                @php
                    $buyDate    = \Carbon\Carbon::parse($c['date']);
                    $expiryDate = $buyDate->copy()->addYear();
                    $daysLeft   = $today->diffInDays($expiryDate, false);

                    if ($daysLeft < 0)       $licenseStatus = 'expired';
                    elseif ($daysLeft <= 30) $licenseStatus = 'expiring';
                    else                     $licenseStatus = 'active';
                @endphp
                <tr>
                    <td>
                        <div class="admin-table__user">
                            <img src="https://picsum.photos/seed/{{ $c['img'] }}/40/40" class="admin-table__avatar" alt="{{ $c['name'] }}">
                            <div>
                                <p class="admin-table__user-name">{{ $c['name'] }}</p>
                                <p class="admin-table__user-email">{{ $c['email'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem">
                            <img src="https://picsum.photos/seed/{{ $c['motif'] }}/60/60" class="admin-table__motif-img" alt="{{ $c['product'] }}">
                            <span style="font-weight:500;font-size:.85rem">{{ $c['product'] }}</span>
                        </div>
                    </td>

                    {{-- Tgl Beli --}}
                    <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">
                        {{ $buyDate->format('d M Y') }}
                    </td>

                    {{-- Tgl Berakhir --}}
                    <td style="white-space:nowrap">
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
                    </td>

                    <td>
                        @if($c['cert_sent'])
                            <span class="status-badge status-badge--sent">✓ Terkirim</span>
                        @else
                            <span class="status-badge status-badge--pending">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($c['license_sent'])
                            <span class="status-badge status-badge--sent">✓ Terkirim</span>
                        @else
                            <span class="status-badge status-badge--pending">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($c['cert_sent'] && $c['license_sent'])
                            <span class="status-badge status-badge--paid">Lengkap</span>
                        @elseif($c['cert_sent'] || $c['license_sent'])
                            <span class="status-badge status-badge--pending">Sebagian</span>
                        @else
                            <span class="status-badge status-badge--failed">Belum Ada</span>
                        @endif
                    </td>
                    <td>
                        <div class="admin-actions-group">
                            <button class="admin-action-btn admin-action-btn--primary"
                                    onclick="openSendModal('{{ $c['name'] }}','{{ $c['product'] }}')">
                                📤 Kirim
                            </button>
                            @if($c['cert_sent'] || $c['license_sent'])
                            <button class="admin-action-btn admin-action-btn--outline"
                                onclick="openRiwayatModal({
                                    name: '{{ $c['name'] }}',
                                    email: '{{ $c['email'] }}',
                                    product: '{{ $c['product'] }}',
                                    date: '{{ $buyDate->format('d M Y') }}',
                                    expiry: '{{ $expiryDate->format('d M Y') }}',
                                    cert_sent: {{ $c['cert_sent'] ? 'true' : 'false' }},
                                    license_sent: {{ $c['license_sent'] ? 'true' : 'false' }},
                                    img: '{{ $c['img'] }}',
                                    motif: '{{ $c['motif'] }}'
                                })">📋 Riwayat</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('pages.admin.partials.modal-send-cert')
@include('pages.admin.partials.modal-riwayat-cert')
@endsection
