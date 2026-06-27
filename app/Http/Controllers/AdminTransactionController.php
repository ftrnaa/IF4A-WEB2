<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'batik'])->paid();

        if ($request->filled('license_status') && $request->license_status !== 'all') {
            $query->licenseStatus($request->license_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('batik', function ($bq) use ($search) {
                        $bq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(9)->withQueryString();

          $totalTransaksi = Order::activeLicense()->count();
        $totalPemasukan = Order::activeLicense()->sum('total');
        $lisensiHampirHabis = Order::licenseStatus('expiring')->count();

        return view('pages.admin.transaksi', compact(
            'orders',
            'totalTransaksi',
            'totalPemasukan',
            'lisensiHampirHabis'
        ));
        $orders->each(function ($order) {
    $order->expiryDate = $order->license_expired_at
        ? \Carbon\Carbon::parse($order->license_expired_at)
        : null;

    $order->daysLeft = $order->license_expired_at
        ? now()->diffInDays($order->license_expired_at, false)
        : null;

    $order->licenseStatus = $order->license_status;
});
    }

    public function show(Order $order)
{
    $order->load(['batik']);

    return response()->json([
        'id' => $order->id,
        'kode_order' => $order->kode_order,
        'nama' => $order->nama,
        'email' => $order->email,
        'total' => $order->total,
        'status' => $order->status,

        // PAYMENT (pakai kolom yang ADA di DB)
        'payment_type' => $order->payment_type,
        'payment_channel' => $order->payment_channel,

        // DATE
        'created_at' => $order->created_at?->format('d M Y'),
        'license_expired_at' => $order->license_expired_at
            ? \Carbon\Carbon::parse($order->license_expired_at)->format('d M Y')
            : '-',

        // REF (pakai kode_order karena tidak ada reference_no)
        'reference_no' => $order->kode_order,

        'batik' => [
            'nama' => $order->batik->nama ?? '-',
            'kategori' => $order->batik->kategori ?? '-',
            'preview_url' => $order->batik->preview_url ?? '',
        ]
    ]);
}

    /**
     * ADMIN HANYA VIEW SERTIFIKAT
     * Tidak download.
     * Tidak menyimpan data certificate ke database.
     */
    public function viewCertificate(Order $order)
    {
        abort_unless(
    $order->status === 'paid' && $order->license_expired_at,
    404,
    'Order belum lunas atau lisensi tidak aktif.'
);

        $order->load(['user', 'batik']);

        $certificate = (object) [
            'certificate_number' => 'BATIKAI-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'qr_token'           => Str::uuid(),
            'issued_at'          => $order->created_at,
        ];

        $pdf = self::buildCertificatePdf(
            $order,
            $certificate
        );

        return $pdf->stream(
            'Sertifikat-' . $order->kode_order . '.pdf'
        );
    }

    /**
     * Shared builder
     */
    public static function buildCertificatePdf(Order $order, object $certificate)
    {
        $verifyUrl = url('/verify/' . $certificate->qr_token);

        $builder = new Builder(
            writer: new PngWriter(),
            data: $verifyUrl
        );

        $result = $builder->build();

        $qrSrc = 'data:image/png;base64,' .
            base64_encode($result->getString());

        $issuedAt = Carbon::parse($certificate->issued_at);

        $expiredAt = $issuedAt->copy()->addYear();

        return Pdf::loadView('pdf.certificate', [
            'order'       => $order,
            'batik'       => $order->batik,
            'certificate' => $certificate,
            'qrSrc'       => $qrSrc,
            'issuedAt'    => $issuedAt,
            'expiredAt'   => $expiredAt,
            'isAdminView' => true,
        ])->setPaper('A4', 'portrait');
    }
}