@extends('layouts.admin')
@section('title', 'Transaksi — Admin BatikAI')
@section('breadcrumb', 'Transaksi')

@section('content')

<div class="admin-page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <h1>Data Transaksi</h1>
        <p>Kelola semua transaksi pembelian motif batik.</p>
    </div>
    <form method="GET" action="{{ route('admin.transaksi') }}" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
        <select name="license_status" class="admin-form-select" style="width:auto;font-size:.82rem" onchange="this.form.submit()">
            <option value="all" {{ request('license_status', 'all') === 'all' ? 'selected' : '' }}>Semua Lisensi</option>
            <option value="active" {{ request('license_status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="expiring" {{ request('license_status') === 'expiring' ? 'selected' : '' }}>Hampir Habis</option>
            <option value="expired" {{ request('license_status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
        </select>
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="admin-form-input"
            placeholder="Cari pembeli / produk..."
            style="width:220px;font-size:.82rem"
        >
        <button type="submit" class="admin-action-btn admin-action-btn--primary" style="font-size:.82rem">Cari</button>
    </form>
</div>

{{-- Summary Strip --}}
<div class="admin-stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.4rem">
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Transaksi</p>
            <p class="admin-stat-card__value">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--green">📊</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Total Pemasukan</p>
            <p class="admin-stat-card__value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--gold">💰</div>
    </div>
    <div class="admin-stat-card">
        <div>
            <p class="admin-stat-card__label">Lisensi Hampir Habis</p>
            <p class="admin-stat-card__value">{{ number_format($lisensiHampirHabis, 0, ',', '.') }}</p>
            <p class="admin-stat-card__change down">▼ ≤ 30 hari</p>
        </div>
        <div class="admin-stat-card__icon admin-stat-card__icon--brown">⚠️</div>
    </div>
</div>

{{-- Tabs --}}
<div class="admin-card">
    <div class="admin-card__body" style="padding-top:1.2rem">

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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $licenseStatus = $order->license_status; // active | expiring | expired
                            $daysLeft      = $order->days_left;
                            $expiryDate    = $order->license_expiry;
                        @endphp
                        <tr>
                            <td style="font-size:.72rem;color:var(--clr-text-muted);font-weight:700">{{ $order->kode_order }}</td>

                            <td>
                                <div class="admin-table__user">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->nama) }}" class="admin-table__avatar" alt="{{ $order->nama }}">
                                    <div>
                                        <p class="admin-table__user-name">{{ $order->nama }}</p>
                                        <p class="admin-table__user-email">{{ $order->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div style="display:flex;align-items:center;gap:.6rem">
                                    <img src="{{ $order->batik->preview_url ?? '' }}" class="admin-table__motif-img" alt="{{ $order->batik->nama ?? '-' }}">
                                    <div>
                                        <p style="font-weight:500;font-size:.85rem">{{ $order->batik->nama ?? '-' }}</p>
                                        <p style="font-size:.72rem;color:var(--clr-text-muted)">{{ $order->batik->kategori ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td style="font-weight:600;color:var(--clr-green)">Rp {{ number_format($order->total, 0, ',', '.') }}</td>

                            {{-- Tgl Beli --}}
                            <td style="font-size:.82rem;color:var(--clr-text-muted);white-space:nowrap">
                                {{ $order->created_at->format('d M Y') }}
                            </td>

                            {{-- Tgl Berakhir --}}
                            <td style="white-space:nowrap">
                                <div>
                                    <p style="font-size:.82rem;font-weight:600;color:{{ $licenseStatus === 'expired' ? '#C0392B' : ($licenseStatus === 'expiring' ? '#B8610A' : 'var(--clr-brown-dark)') }}">
                                        {{ $expiryDate?->format('d M Y') ?? '-' }}
                                    </p>
                                    @if($daysLeft !== null)
    @if($licenseStatus === 'active')
        <p>{{ $daysLeft }} hari lagi</p>
    @elseif($licenseStatus === 'expiring')
        <p style="color:#B8610A">⚠ {{ $daysLeft }} hari lagi</p>
    @elseif($licenseStatus === 'expired')
        <p style="color:#C0392B">Berakhir {{ abs($daysLeft) }} hari lalu</p>
    @endif
@endif
                                </div>
                            </td>

                            <td><span class="status-badge status-badge--paid">Lunas</span></td>

                            {{-- Status Lisensi --}}
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
                                <div class="admin-actions-group">
                                    <a
                                        href="{{ route('admin.transaksi.sertifikat', $order->id) }}"
                                        class="admin-action-btn admin-action-btn--outline"
                                        target="_blank"
                                    >📄 View Sertifikat</a>
                                    <button
                                        class="admin-action-btn admin-action-btn--outline"
                                        onclick="openDetailModal('{{ route('admin.transaksi.show', $order->id) }}')"
                                    >👁 Detail</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:2rem;color:var(--clr-text-muted)">
                                Belum ada transaksi yang sesuai dengan filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 0;margin-top:.5rem">
                <p style="font-size:.78rem;color:var(--clr-text-muted)">
                    Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} dari {{ number_format($orders->total(), 0, ',', '.') }} transaksi
                </p>
                <div style="display:flex;gap:.4rem">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

@include('pages.admin.partials.modal-send-cert')
@include('pages.admin.partials.modal-detail-trx')

@push('scripts')
<script>
    // openDetailModal sekarang menerima URL endpoint show (JSON), bukan object statis.
    // Fungsi ini diharapkan sudah didefinisikan ulang di modal-detail-trx untuk fetch data via AJAX:
    //
    // function openDetailModal(url) {
    //     fetch(url, { headers: { 'Accept': 'application/json' } })
    //         .then(res => res.json())
    //         .then(data => { /* isi modal dengan data */ });
    // }
</script>
@endpush
@endsection