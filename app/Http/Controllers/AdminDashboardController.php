<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminDashboardController extends Controller
{
    /**
     * Daftar periode yang didukung dan label tampilnya.
     * Dipakai bersama oleh index(), export(), dan resolvePeriod().
     */
    protected const PERIODS = [
    '3b'  => ['days' => 90,  'label' => '3 Bulan'],
    '6b'  => ['days' => 180, 'label' => '6 Bulan'],
    '1t'  => ['days' => 365, 'label' => '1 Tahun'],
];


    public function index(Request $request)
    {
        $period = $this->resolvePeriod($request->query('period'));
        [$start, $end] = $this->periodRange($period);

        // ======================
        // KPI DASHBOARD (mengikuti periode terpilih)
        // ======================
        $baseQuery = Order::whereBetween('created_at', [$start, $end]);

        $totalRevenue = (clone $baseQuery)->where('status', 'paid')->sum('total');
        $productsSold = (clone $baseQuery)->where('status', 'paid')->count();
        $activeBuyers = (clone $baseQuery)->distinct('user_id')->count('user_id');
        $pendingPayment = (clone $baseQuery)->where('status', 'pending')->count();
        $failedPayment = (clone $baseQuery)->where('status', 'failed')->count();

        // ======================
        // TRANSAKSI (sesuai periode, untuk tabel & activity feed)
        // ======================
        $transactions = Order::with(['user', 'batik'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // ======================
        // REVENUE CHART (mengikuti periode terpilih)
        // ======================
        $chart = $this->buildChartData($period, $start, $end);

        return view('pages.admin.dashboard', compact(
            'totalRevenue',
            'productsSold',
            'activeBuyers',
            'pendingPayment',
            'failedPayment',
            'transactions',
            'period'
        ) + $chart);
    }

    /**
     * Detail satu order — dipakai modal "Detail" lewat fetch AJAX.
     */
    public function show(Order $order)
{
    $order->load('batik');

    return response()->json([
        'invoice' => $order->kode_order,

        'status' => $order->status,
        'status_label' => match ($order->status) {
            'paid' => 'Lunas',
            'pending' => 'Menunggu',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Expired',
            default => ucfirst($order->status),
        },

        // USER (DARI ORDER LANGSUNG, BUKAN RELASI)
        'user' => [
            'name' => $order->nama ?? 'Tidak ada',
            'email' => $order->email ?? '-',
        ],

        // PRODUCT (FIX FIELD + IMAGE API)
        'product' => [
            'name' => $order->batik->nama ?? 'Tidak ada produk',

            'image' => $order->batik->preview_image
                ? 'https://btx.agunghakase.my.id/api/image/' . $order->batik->preview_image
                : null,

            'price' => $order->batik->harga
                ? 'Rp ' . number_format($order->batik->harga, 0, ',', '.')
                : '—',
        ],

        // PAYMENT (FIX 100%)
        'payment' => [
    'type' => $order->payment_type ?? 'Tidak ada',
    'channel' => $order->payment_channel ?? 'Tidak ada',
],

        // DATE (FIX FINAL)
        'created_at' => optional($order->created_at)->format('d M Y, H:i'),

       'expired_at' => $order->license_expired_at
    ? Carbon::parse($order->license_expired_at)->format('d M Y, H:i')
    : 'Belum ditentukan',

        'total' => $order->total,
        'total_formatted' => 'Rp ' . number_format($order->total, 0, ',', '.'),
    ]);
}

    /**
     * Export laporan transaksi ke Excel atau PDF, mengikuti periode terpilih.
     */
    public function export(Request $request, string $type)
    {
        $period = $this->resolvePeriod($request->query('period'));
        [$start, $end] = $this->periodRange($period);

        $transactions = Order::with(['user', 'batik'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $filename = 'laporan-batikai-' . $period . '-' . now()->format('Ymd-His');

        if ($type === 'excel') {
            return Excel::download(new OrdersExport($transactions), $filename . '.xlsx');
        }

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('exports.orders-pdf', [
                'transactions' => $transactions,
                'periodLabel'  => self::PERIODS[$period]['label'],
                'totalRevenue' => $transactions->where('status', 'paid')->sum('total'),
                'generatedAt'  => now()->translatedFormat('d F Y, H:i'),
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        abort(404, 'Tipe export tidak dikenali.');
    }

    /**
     * Endpoint AJAX ringan untuk reload chart + KPI saat period diganti
     * tanpa reload halaman penuh (dipanggil dari JS setPeriod()).
     */
    public function chartData(Request $request)
    {
        $period = $this->resolvePeriod($request->query('period'));
        [$start, $end] = $this->periodRange($period);

        $baseQuery = Order::whereBetween('created_at', [$start, $end]);

        return response()->json([
            'period' => $period,
            'kpi' => [
                'total_revenue'  => (clone $baseQuery)->where('status', 'paid')->sum('total'),
                'products_sold'  => (clone $baseQuery)->where('status', 'paid')->count(),
                'active_buyers'  => (clone $baseQuery)->distinct('user_id')->count('user_id'),
                'pending'        => (clone $baseQuery)->where('status', 'pending')->count(),
                'failed'         => (clone $baseQuery)->where('status', 'failed')->count(),
            ],
            'chart' => $this->buildChartData($period, $start, $end),
        ]);
    }

    /**
     * Validasi parameter period dari request; fallback ke '30h' jika tidak valid.
     */
    protected function resolvePeriod(?string $period): string
{
    return ($period !== null && array_key_exists($period, self::PERIODS))
        ? $period
        : '3b';
}
    /**
     * Hitung rentang tanggal [start, end] berdasarkan kode periode.
     */
    protected function periodRange(string $period): array
    {
        $days = self::PERIODS[$period]['days'];
        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays($days - 1)->startOfDay();

        return [$start, $end];
    }

    /**
     * Bangun data chart sesuai granularitas periode:
     * - 7h / 30h -> grup per hari
     * - 3b       -> grup per minggu
     * - 1t       -> grup per bulan
     */
    protected function buildChartData(string $period, Carbon $start, Carbon $end): array
{
    $months = match ($period) {
        '3b' => 3,
        '6b' => 6,
        default => 12,
    };

    $raw = Order::select(
            DB::raw('YEAR(created_at) as y'),
            DB::raw('MONTH(created_at) as m'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', 'paid')
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('y', 'm')
        ->get()
        ->mapWithKeys(fn ($row) => [
            $row->y.'-'.$row->m => $row->total
        ]);

    $labels = [];
    $values = [];

    $cursor = now()
        ->startOfMonth()
        ->subMonths($months - 1);

    for ($i = 0; $i < $months; $i++) {

        $key = $cursor->year.'-'.$cursor->month;

        $labels[] = $cursor->translatedFormat('M Y');
        $values[] = (float) ($raw[$key] ?? 0);

        $cursor->addMonth();
    }

    return [
        'chartLabels' => $labels,
        'chartValues' => $values,
    ];
}
}