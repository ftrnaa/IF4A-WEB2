@extends('layouts.user-dashboard')
@section('title', 'Sertifikat — BatikAI')
@section('breadcrumb', 'Sertifikat')

@section('content')

<div class="admin-page-header">
    <h1>Sertifikat Saya</h1>
    <p>Semua sertifikat keaslian dan file lisensi yang sudah kamu terima.</p>
</div>

@php
$certs = [
    [
        'name'      => 'Sido Mukti',
        'cat'       => 'Klasik',
        'img'       => 'batik1',
        'date'      => '13 Apr 2026',
        'no'        => 'CERT-2026-001',
        'files'     => [
            ['label'=>'Sertifikat Keaslian', 'icon'=>'📜', 'type'=>'PDF', 'size'=>'248 KB'],
            ['label'=>'Lisensi Komersial',   'icon'=>'📄', 'type'=>'PDF', 'size'=>'185 KB'],
            ['label'=>'File Motif (SVG)',     'icon'=>'🖼️', 'type'=>'ZIP', 'size'=>'2,4 MB'],
        ],
    ],
    [
        'name'      => 'Mega Mendung',
        'cat'       => 'Pesisir',
        'img'       => 'batik3',
        'date'      => '10 Des 2025',
        'no'        => 'CERT-2025-089',
        'files'     => [
            ['label'=>'Sertifikat Keaslian', 'icon'=>'📜', 'type'=>'PDF', 'size'=>'231 KB'],
            ['label'=>'Lisensi Komersial',   'icon'=>'📄', 'type'=>'PDF', 'size'=>'179 KB'],
        ],
    ],
    [
        'name'      => 'Parang Rusak',
        'cat'       => 'Pesisir',
        'img'       => 'batik5',
        'date'      => '05 Apr 2025',
        'no'        => 'CERT-2025-032',
        'files'     => [
            ['label'=>'Sertifikat Keaslian', 'icon'=>'📜', 'type'=>'PDF', 'size'=>'219 KB'],
            ['label'=>'Lisensi Komersial',   'icon'=>'📄', 'type'=>'PDF', 'size'=>'166 KB'],
            ['label'=>'File Motif (PNG HD)',  'icon'=>'🖼️', 'type'=>'ZIP', 'size'=>'8,1 MB'],
        ],
    ],
];
@endphp

<div style="display:flex;flex-direction:column;gap:1.2rem">
    @foreach($certs as $c)
    <div class="user-card">
        <div class="user-card__header">
            <div style="display:flex;align-items:center;gap:.85rem">
                <img src="https://picsum.photos/seed/{{ $c['img'] }}/80/80"
                     style="width:44px;height:44px;border-radius:8px;object-fit:cover" alt="{{ $c['name'] }}">
                <div>
                    <p style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--clr-brown-dark)">{{ $c['name'] }}</p>
                    <p style="font-size:.72rem;color:var(--clr-text-muted)">{{ $c['cat'] }} · No. {{ $c['no'] }} · Diterima {{ $c['date'] }}</p>
                </div>
            </div>
            <span class="status-badge status-badge--paid">✓ Lengkap</span>
        </div>
        <div class="user-card__body">
            <div style="display:flex;flex-direction:column;gap:.5rem">
                @foreach($c['files'] as $file)
                <div style="display:flex;align-items:center;gap:.85rem;padding:.75rem 1rem;background:var(--clr-cream-light);border-radius:var(--radius-sm);border:1px solid rgba(200,169,110,.15)">
                    <span style="font-size:1.3rem;flex-shrink:0">{{ $file['icon'] }}</span>
                    <div style="flex:1">
                        <p style="font-size:.85rem;font-weight:600;color:var(--clr-brown-dark)">{{ $file['label'] }}</p>
                        <p style="font-size:.72rem;color:var(--clr-text-muted)">{{ $file['type'] }} · {{ $file['size'] }}</p>
                    </div>
                    <div style="display:flex;gap:.4rem">
                        <button class="cert-btn cert-btn--view"
                                onclick="viewCert('{{ $file['label'] }} — {{ $c['name'] }}','{{ $c['date'] }}')">
                            👁 Lihat
                        </button>
                        <button class="cert-btn cert-btn--dl" onclick="userToast('✓ {{ $file['label'] }} diunduh')">
                            ⬇ Unduh
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

@include('pages.users.cert-modal')

@endsection