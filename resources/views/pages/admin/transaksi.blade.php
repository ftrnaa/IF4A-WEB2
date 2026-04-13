@extends('layouts.admin')
@section('title', 'Transaksi — Admin BatikAI')
@section('breadcrumb', 'Transaksi')

@section('content')

<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Data Transaksi</h1>
        <p>Kelola semua transaksi pembelian motif batik.</p>
    </div>
    {{-- Filter bar --}}
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
        <select class="admin-form-select" style="width:auto;font-size:.82rem">
            <option>Semua Status</option>
            <option>Lunas</option>
            <option>Menunggu</option>
            <option>Gagal</option>
        </select>
        <input type="text" class="admin-form-input" placeholder="Cari pembeli / produk..." style="width:220px;font-size:.82rem">
    </div>
</div>

{{-- Summary Strip --}}
<div class="admin-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.4rem">
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Transaksi</p>
            <p class="admin-stat-card__value">1.284</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--green">📊</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Pemasukan</p>
            <p class="admin-stat-card__value">Rp 24,7 Jt</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--gold">💰</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Menunggu Bayar</p>
            <p class="admin-stat-card__value">37</p>
            <p class="admin-stat-card__change down">▼ perlu konfirmasi</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--blue">⏳</div>
    </div>
</div>

{{-- Tabs --}}
<div class="admin-card">
    <div class="admin-card__body" style="padding-top:1.2rem">

        <div class="admin-tabs">
            <button class="admin-tab-btn active" onclick="switchTab(this,'tab-all')">Semua</button>
            <button class="admin-tab-btn" onclick="switchTab(this,'tab-paid')">Lunas</button>
            <button class="admin-tab-btn" onclick="switchTab(this,'tab-pending')">Menunggu</button>
            <button class="admin-tab-btn" onclick="switchTab(this,'tab-failed')">Gagal</button>
        </div>

        @php
        $transactions = [
            ['id'=>'TRX-001','name'=>'Rina Susanti','email'=>'rina@mail.com','product'=>'Sido Mukti','cat'=>'Klasik','amount'=>120000,'status'=>'paid','date'=>'13 Apr 2026','cert'=>true,'img'=>'person4','motif'=>'batik1'],
            ['id'=>'TRX-002','name'=>'Budi Hartono','email'=>'budi@mail.com','product'=>'Kawung','cat'=>'Klasik','amount'=>110000,'status'=>'pending','date'=>'13 Apr 2026','cert'=>false,'img'=>'person5','motif'=>'batik4'],
            ['id'=>'TRX-003','name'=>'Dewi Lestari','email'=>'dewi@mail.com','product'=>'Mega Mendung','cat'=>'Pesisir','amount'=>135000,'status'=>'paid','date'=>'12 Apr 2026','cert'=>true,'img'=>'person6','motif'=>'batik3'],
            ['id'=>'TRX-004','name'=>'Agus Prasetyo','email'=>'agus@mail.com','product'=>'Truntum','cat'=>'Modern','amount'=>150000,'status'=>'failed','date'=>'12 Apr 2026','cert'=>false,'img'=>'person7','motif'=>'batik2'],
            ['id'=>'TRX-005','name'=>'Sari Kusuma','email'=>'sari@mail.com','product'=>'Parang Rusak','cat'=>'Pesisir','amount'=>95000,'status'=>'paid','date'=>'11 Apr 2026','cert'=>true,'img'=>'person8','motif'=>'batik5'],
            ['id'=>'TRX-006','name'=>'Rizky Nugroho','email'=>'rizky@mail.com','product'=>'Sekar Jagad','cat'=>'Kontemporer','amount'=>175000,'status'=>'pending','date'=>'11 Apr 2026','cert'=>false,'img'=>'person9','motif'=>'batik6'],
        ];
        $statusLabel = ['paid'=>'Lunas','pending'=>'Menunggu','failed'=>'Gagal'];
        @endphp

        <div class="admin-tab-panel active" id="tab-all">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        <tr>
                            <td style="font-size:.75rem;color:var(--clr-text-muted);font-weight:600">{{ $tx['id'] }}</td>
                            <td>
                                <div class="admin-table__user">
                                    <img src="https://picsum.photos/seed/{{ $tx['img'] }}/40/40" class="admin-table__avatar" alt="{{ $tx['name'] }}">
                                    <div>
                                        <p class="admin-table__user-name">{{ $tx['name'] }}</p>
                                        <p class="admin-table__user-email">{{ $tx['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:.6rem">
                                    <img src="https://picsum.photos/seed/{{ $tx['motif'] }}/60/60" class="admin-table__motif-img" alt="{{ $tx['product'] }}">
                                    <div>
                                        <p style="font-weight:500;font-size:.85rem">{{ $tx['product'] }}</p>
                                        <p style="font-size:.72rem;color:var(--clr-text-muted)">{{ $tx['cat'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight:600;color:var(--clr-green)">Rp {{ number_format($tx['amount'],0,',','.') }}</td>
                            <td style="font-size:.82rem;color:var(--clr-text-muted)">{{ $tx['date'] }}</td>
                            <td><span class="status-badge status-badge--{{ $tx['status'] }}">{{ $statusLabel[$tx['status']] }}</span></td>
                            <td>
                                @if($tx['cert'])
                                    <span class="status-badge status-badge--sent">Terkirim</span>
                                @else
                                    <span style="font-size:.75rem;color:var(--clr-text-muted)">Belum</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-actions-group">
                                    @if($tx['status'] === 'paid' && !$tx['cert'])
                                        <button class="admin-action-btn admin-action-btn--primary" onclick="openSendModal('{{ $tx['name'] }}','{{ $tx['product'] }}')">📄 Kirim</button>
                                    @elseif($tx['status'] === 'paid')
                                        <button class="admin-action-btn admin-action-btn--outline" onclick="openSendModal('{{ $tx['name'] }}','{{ $tx['product'] }}')">🔄 Kirim Ulang</button>
                                    @elseif($tx['status'] === 'pending')
                                        <button class="admin-action-btn admin-action-btn--primary">✓ Konfirmasi</button>
                                    @endif
                                    <button class="admin-action-btn admin-action-btn--outline">👁 Detail</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-tab-panel" id="tab-paid">
            <p style="color:var(--clr-text-muted);font-size:.88rem;padding:1rem 0">Menampilkan transaksi berstatus <strong>Lunas</strong>.</p>
        </div>
        <div class="admin-tab-panel" id="tab-pending">
            <p style="color:var(--clr-text-muted);font-size:.88rem;padding:1rem 0">Menampilkan transaksi <strong>Menunggu Pembayaran</strong>.</p>
        </div>
        <div class="admin-tab-panel" id="tab-failed">
            <p style="color:var(--clr-text-muted);font-size:.88rem;padding:1rem 0">Menampilkan transaksi <strong>Gagal</strong>.</p>
        </div>

    </div>
</div>

@include('pages.admin.partials.modal-send-cert')
@endsection