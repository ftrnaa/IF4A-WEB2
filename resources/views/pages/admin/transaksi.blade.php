@extends('layouts.admin')
@section('title', 'Transaksi — Admin BatikAI')
@section('breadcrumb', 'Transaksi')

@section('content')

<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Data Transaksi</h1>
        <p>Kelola semua transaksi pembelian motif batik.</p>
    </div>
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
        <select class="admin-form-select" style="width:auto;font-size:.82rem">
            <option>Semua Status</option>
            <option>Lunas</option>
            <option>Menunggu</option>
            <option>Gagal</option>
        </select>
        <select class="admin-form-select" style="width:auto;font-size:.82rem">
            <option>Semua Lisensi</option>
            <option>Aktif</option>
            <option>Hampir Habis</option>
            <option>Kedaluwarsa</option>
        </select>
        <input type="text" class="admin-form-input" placeholder="Cari pembeli / produk..." style="width:220px;font-size:.82rem">
    </div>
</div>

{{-- Summary Strip --}}
<div class="admin-stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.4rem">
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
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Lisensi Hampir Habis</p>
            <p class="admin-stat-card__value">12</p>
            <p class="admin-stat-card__change down">▼ ≤ 30 hari</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--brown">⚠️</div>
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
            ['id'=>'TRX-001','name'=>'Rina Susanti',  'email'=>'rina@mail.com',  'product'=>'Sido Mukti',  'cat'=>'Klasik',      'amount'=>120000,'status'=>'paid',   'date'=>'2026-04-13','cert'=>true, 'img'=>'person4', 'motif'=>'batik1'],
            ['id'=>'TRX-002','name'=>'Budi Hartono',  'email'=>'budi@mail.com',  'product'=>'Kawung',      'cat'=>'Klasik',      'amount'=>110000,'status'=>'pending','date'=>'2026-04-13','cert'=>false,'img'=>'person5', 'motif'=>'batik4'],
            ['id'=>'TRX-003','name'=>'Dewi Lestari',  'email'=>'dewi@mail.com',  'product'=>'Mega Mendung','cat'=>'Pesisir',     'amount'=>135000,'status'=>'paid',   'date'=>'2026-04-12','cert'=>true, 'img'=>'person6', 'motif'=>'batik3'],
            ['id'=>'TRX-004','name'=>'Agus Prasetyo', 'email'=>'agus@mail.com',  'product'=>'Truntum',     'cat'=>'Modern',      'amount'=>150000,'status'=>'failed', 'date'=>'2026-04-12','cert'=>false,'img'=>'person7', 'motif'=>'batik2'],
            ['id'=>'TRX-005','name'=>'Sari Kusuma',   'email'=>'sari@mail.com',  'product'=>'Parang Rusak','cat'=>'Pesisir',     'amount'=>95000, 'status'=>'paid',   'date'=>'2026-04-11','cert'=>true, 'img'=>'person8', 'motif'=>'batik5'],
            ['id'=>'TRX-006','name'=>'Rizky Nugroho', 'email'=>'rizky@mail.com', 'product'=>'Sekar Jagad', 'cat'=>'Kontemporer', 'amount'=>175000,'status'=>'pending','date'=>'2026-04-11','cert'=>false,'img'=>'person9', 'motif'=>'batik6'],
            ['id'=>'TRX-007','name'=>'Hendra Wijaya', 'email'=>'hendra@mail.com','product'=>'Sido Mukti',  'cat'=>'Klasik',      'amount'=>120000,'status'=>'paid',   'date'=>'2025-12-10','cert'=>true, 'img'=>'person10','motif'=>'batik1'],
            ['id'=>'TRX-008','name'=>'Fitriana Putri','email'=>'fitri@mail.com', 'product'=>'Kawung',      'cat'=>'Klasik',      'amount'=>110000,'status'=>'paid',   'date'=>'2025-04-20','cert'=>true, 'img'=>'person11','motif'=>'batik4'],
            ['id'=>'TRX-009','name'=>'Wahyu Santoso', 'email'=>'wahyu@mail.com', 'product'=>'Truntum',     'cat'=>'Modern',      'amount'=>150000,'status'=>'paid',   'date'=>'2025-04-05','cert'=>true, 'img'=>'person12','motif'=>'batik2'],
        ];
        $statusLabel = ['paid'=>'Lunas','pending'=>'Menunggu','failed'=>'Gagal'];
        $today = \Carbon\Carbon::today();
        @endphp

        {{-- ── Tab: Semua ── --}}
        <div class="admin-tab-panel active" id="tab-all">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tgl Beli</th>
                            <th>Tgl Berakhir</th>
                            <th>Status Bayar</th>
                            <th>Status Lisensi</th>
                            <th>Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        @php
                            $buyDate    = \Carbon\Carbon::parse($tx['date']);
                            $expiryDate = $buyDate->copy()->addYear();
                            $daysLeft   = $today->diffInDays($expiryDate, false);

                            if ($tx['status'] !== 'paid') {
                                $licenseStatus = 'none';
                            } elseif ($daysLeft < 0) {
                                $licenseStatus = 'expired';
                            } elseif ($daysLeft <= 30) {
                                $licenseStatus = 'expiring';
                            } else {
                                $licenseStatus = 'active';
                            }
                        @endphp
                        <tr>
                            <td style="font-size:.72rem;color:var(--clr-text-muted);font-weight:700">{{ $tx['id'] }}</td>

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

                            {{-- Tgl Beli --}}
                            <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">
                                {{ $buyDate->format('d M Y') }}
                            </td>

                            {{-- Tgl Berakhir --}}
                            <td style="white-space:nowrap">
                                @if($tx['status'] === 'paid')
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

                            <td><span class="status-badge status-badge--{{ $tx['status'] }}">{{ $statusLabel[$tx['status']] }}</span></td>

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

            {{-- Pagination --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 0;margin-top:.5rem">
                <p style="font-size:.78rem;color:var(--clr-text-muted)">Menampilkan 1–9 dari 1.284 transaksi</p>
                <div style="display:flex;gap:.4rem">
                    @foreach([1,2,3,'...',128] as $pg)
                    <button style="width:32px;height:32px;border-radius:6px;border:1.5px solid {{ $pg === 1 ? 'var(--clr-brown-dark)' : 'rgba(200,169,110,.25)' }};background:{{ $pg === 1 ? 'var(--clr-brown-dark)' : 'transparent' }};color:{{ $pg === 1 ? 'var(--clr-cream-light)' : 'var(--clr-text-muted)' }};font-size:.78rem;cursor:pointer">{{ $pg }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Tab: Lunas ── --}}
        <div class="admin-tab-panel" id="tab-paid">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tgl Beli</th>
                            <th>Tgl Berakhir</th>
                            <th>Status Lisensi</th>
                            <th>Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        @if($tx['status'] === 'paid')
                        @php
                            $buyDate    = \Carbon\Carbon::parse($tx['date']);
                            $expiryDate = $buyDate->copy()->addYear();
                            $daysLeft   = $today->diffInDays($expiryDate, false);
                            if ($daysLeft < 0)       $licenseStatus = 'expired';
                            elseif ($daysLeft <= 30) $licenseStatus = 'expiring';
                            else                     $licenseStatus = 'active';
                        @endphp
                        <tr>
                            <td style="font-size:.72rem;color:var(--clr-text-muted);font-weight:700">{{ $tx['id'] }}</td>
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
                            <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">{{ $buyDate->format('d M Y') }}</td>
                            <td style="white-space:nowrap">
                                <p style="font-size:.82rem;font-weight:600;color:{{ $licenseStatus === 'expired' ? '#C0392B' : ($licenseStatus === 'expiring' ? '#B8610A' : 'var(--clr-brown-dark)') }}">
                                    {{ $expiryDate->format('d M Y') }}
                                </p>
                                @if($licenseStatus === 'active')
                                    <p style="font-size:.7rem;color:var(--clr-text-muted)">{{ $daysLeft }} hari lagi</p>
                                @elseif($licenseStatus === 'expiring')
                                    <p style="font-size:.7rem;color:#B8610A;font-weight:600">⚠ {{ $daysLeft }} hari lagi</p>
                                @else
                                    <p style="font-size:.7rem;color:#C0392B;font-weight:600">Berakhir {{ abs($daysLeft) }} hari lalu</p>
                                @endif
                            </td>
                            <td>
                                @if($licenseStatus === 'active')
                                    <span class="status-badge status-badge--paid">Aktif</span>
                                @elseif($licenseStatus === 'expiring')
                                    <span class="status-badge status-badge--pending">Hampir Habis</span>
                                @else
                                    <span class="status-badge status-badge--failed">Kedaluwarsa</span>
                                @endif
                            </td>
                            <td>
                                @if($tx['cert'])
                                    <span class="status-badge status-badge--sent">Terkirim</span>
                                @else
                                    <span style="font-size:.75rem;color:var(--clr-text-muted)">Belum</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-actions-group">
                                    <button class="admin-action-btn admin-action-btn--outline" onclick="openSendModal('{{ $tx['name'] }}','{{ $tx['product'] }}')">
                                        {{ $tx['cert'] ? '🔄 Kirim Ulang' : '📄 Kirim' }}
                                    </button>
                                    <button class="admin-action-btn admin-action-btn--outline">👁 Detail</button>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Tab: Menunggu ── --}}
        <div class="admin-tab-panel" id="tab-pending">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tgl Beli</th>
                            <th>Tgl Berakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        @if($tx['status'] === 'pending')
                        @php $buyDate = \Carbon\Carbon::parse($tx['date']); @endphp
                        <tr>
                            <td style="font-size:.72rem;color:var(--clr-text-muted);font-weight:700">{{ $tx['id'] }}</td>
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
                            <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">{{ $buyDate->format('d M Y') }}</td>
                            <td style="font-size:.75rem;color:var(--clr-text-muted)">Aktif setelah lunas</td>
                            <td>
                                <div class="admin-actions-group">
                                    <button class="admin-action-btn admin-action-btn--primary">✓ Konfirmasi Bayar</button>
                                    <button class="admin-action-btn admin-action-btn--danger">✕ Batalkan</button>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Tab: Gagal ── --}}
        <div class="admin-tab-panel" id="tab-failed">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Tgl Beli</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        @if($tx['status'] === 'failed')
                        @php $buyDate = \Carbon\Carbon::parse($tx['date']); @endphp
                        <tr>
                            <td style="font-size:.72rem;color:var(--clr-text-muted);font-weight:700">{{ $tx['id'] }}</td>
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
                            <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">{{ $buyDate->format('d M Y') }}</td>
                            <td><span class="status-badge status-badge--failed">Pembayaran gagal</span></td>
                            <td>
                                <div class="admin-actions-group">
                                    <button class="admin-action-btn admin-action-btn--outline">↩ Proses Ulang</button>
                                    <button class="admin-action-btn admin-action-btn--outline">👁 Detail</button>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@include('pages.admin.partials.modal-send-cert')

@endsection