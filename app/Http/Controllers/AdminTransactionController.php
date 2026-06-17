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

        $totalTransaksi = Order::paid()->count();
        $totalPemasukan = Order::paid()->sum('total');
        $lisensiHampirHabis = Order::licenseStatus('expiring')->count();

        return view('pages.admin.transaksi', compact(
            'orders',
            'totalTransaksi',
            'totalPemasukan',
            'lisensiHampirHabis'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'batik']);

        return view('pages.admin.transaksi-detail', compact('order'));
    }

    /**
     * ADMIN HANYA VIEW SERTIFIKAT
     * Tidak download.
     * Tidak menyimpan data certificate ke database.
     */
    public function viewCertificate(Order $order)
    {
        abort_unless(
            $order->status === 'paid',
            404,
            'Order belum lunas.'
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