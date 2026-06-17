<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    /**
     * User melihat / download sertifikat miliknya sendiri.
     */
    public function downloadCertificatePdf(Order $order)
{
    abort_unless(
        $order->user_id === Auth::id(),
        403
    );

    abort_unless(
        $order->status === 'paid',
        403
    );

    $order->load(['user', 'batik']);

    $certificate = Certificate::firstOrCreate(
        [
            'order_id' => $order->id
        ],
        [
            'user_id'            => $order->user_id,
            'certificate_number' => 'BATIKAI-' .
                date('Y') . '-' .
                strtoupper(Str::random(8)),

            'qr_token' => (string) Str::uuid(),

            'issued_at' => now(),
        ]
    );

    $pdf = AdminTransactionController::buildCertificatePdf(
        $order,
        $certificate
    );

    return $pdf->download(
        'Sertifikat-' . $order->kode_order . '.pdf'
    );
}

    /**
     * Admin melihat sertifikat user.
     */
    public function viewCertificatePdf(Order $order)
    {
        abort_unless(
            Auth::check()
            && Auth::user()->role === 'admin',
            403,
            'Akses ditolak.'
        );

        $order->load(['user', 'batik']);

        return app(AdminTransactionController::class)
            ->viewCertificate($order);
    }

    public function verifyCertificate($token)
{
    $certificate = Certificate::with([
        'order',
        'order.batik',
        'order.user'
    ])->where('qr_token', $token)->first();

    if (!$certificate) {
        abort(404, 'Sertifikat tidak ditemukan');
    }

    return view(
        'certificate.verify',
        compact('certificate')
    );
}

    /**
     * Halaman lisensi user.
     */
    public function index()
    {
        return view('pages.dashboard.licenses');
    }
}