<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi BatikAI</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1C1008; font-size: 11px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h1 { margin: 0; color: #4A2C0A; font-size: 18px; }
        .header p { margin: 2px 0; color: #7C6A56; font-size: 11px; }
        .summary {
            display: block;
            width: 100%;
            margin-bottom: 14px;
            border: 1px solid #EAE0D0;
            border-radius: 6px;
            padding: 10px 14px;
            background: #FAF7F2;
        }
        .summary strong { color: #4A2C0A; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #EAE0D0; padding: 6px 8px; font-size: 10px; text-align: left; }
        th { background: #C8971A; color: #fff; text-transform: uppercase; font-size: 9px; }
        tr:nth-child(even) td { background: #FAF7F2; }
        .status { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .status-paid { background: #E8F5EE; color: #2E7D52; }
        .status-pending { background: #FEF0E6; color: #C05A1A; }
        .status-failed { background: #FEE2E2; color: #B91C1C; }
        .footer { margin-top: 16px; font-size: 9px; color: #7C6A56; text-align: right; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi — BatikAI</h1>
        <p>Periode: {{ $periodLabel }}</p>
    </div>

    <div class="summary">
        <strong>Total Pendapatan (Lunas):</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Jumlah Transaksi:</strong> {{ $transactions->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>Email</th>
                <th>Produk</th>
                <th class="text-right">Jumlah</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $tx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $tx->nama ?? '—' }}</td>
<td>{{ $tx->email ?? '—' }}</td>
<td>{{ $tx->batik->nama ?? '—' }}</td>
                <td class="text-right">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                <td>
                    <span class="status status-{{ $tx->status }}">
                        @if($tx->status === 'paid') Lunas
                        @elseif($tx->status === 'pending') Menunggu
                        @else Gagal
                        @endif
                    </span>
                </td>
                <td>{{ optional($tx->created_at)->format('d-m-Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding: 16px;">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak otomatis oleh sistem BatikAI pada {{ $generatedAt }}</div>
</body>
</html>
