<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\Certificate;


class LicenseController extends Controller
{
    public function index()
{
    $orders = Order::with([
        'batik',
        'productLinks'
    ])
    ->where('user_id', auth()->id())
    ->where('status', 'paid')
    ->latest()
    ->get();

    // Tambahkan ini
    $aktif = 0;
    $hampirHabis = 0;
    $kedaluwarsa = 0;

    foreach ($orders as $order) {
    $daysLeft = now()->diffInDays(
        Carbon::parse($order->created_at)->addYear(),
        false
    );

    if ($daysLeft < 0) {
        $kedaluwarsa++;
    } elseif ($daysLeft <= 30) {
        $hampirHabis++;
    } else {
        $aktif++;
    }
}

    return view('pages.users.lisensi', compact(
        'orders',
        'aktif',
        'hampirHabis',
        'kedaluwarsa'
        
    ));
}
public function downloadMotifPdf(Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $order->load('batik');

   $imageUrl = $order->batik->preview_url;

$response = Http::timeout(30)->get($imageUrl);

if (!$response->successful()) {
    abort(404, 'Gagal mengambil gambar motif');
}

$imageContent = $response->body();

$imageBase64 = base64_encode($imageContent);

$imageSrc = 'data:image/webp;base64,' . $imageBase64;

    $pdf = Pdf::loadView('pdf.motif', [
        'imageSrc' => $imageSrc
    ]);

   $pdf->setPaper('a3', 'portrait');

    return $pdf->download(
        'Motif-' . $order->batik->nama . '.pdf'
    );
}
public function downloadCertificatePdf(Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $order->load(['batik', 'user']);

    /*
    |--------------------------------------------------------------------------
    | 1. BUAT / AMBIL SERTIFIKAT
    |--------------------------------------------------------------------------
    */
    $certificate = Certificate::firstOrCreate(
        ['order_id' => $order->id],
        [
            'certificate_number' => 'BTA-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'qr_token' => Str::uuid(),
            'issued_at' => now(),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | 3. QR LINK (AMAN + VERIFIKASI)
    |--------------------------------------------------------------------------
    */
   $verifyUrl = 'http://192.168.1.11:8000/verify/' . $certificate->qr_token;

$builder = new Builder(
    writer: new PngWriter(),
    data: $verifyUrl
);

$result = $builder->build();

$qrSrc = 'data:image/png;base64,' . base64_encode(
    $result->getString()
);


    /*
    |--------------------------------------------------------------------------
    | 4. GENERATE PDF
    |--------------------------------------------------------------------------
    */
    $issuedAt = Carbon::parse($certificate->issued_at);
$expiredAt = $issuedAt->copy()->addYear();

$pdf = Pdf::loadView('pdf.certificate', [
    'order' => $order,
    'batik' => $order->batik,
    'qrSrc' => $qrSrc,
    'certificate' => $certificate,
    'issuedAt' => $issuedAt,
    'expiredAt' => $expiredAt,
]);

    return $pdf->download('Sertifikat-' . $order->kode_order . '.pdf');
}
public function verifyCertificate($token)
{
    $certificate = Certificate::with(['order.batik', 'order.user'])
        ->where('qr_token', $token)
        ->first();

    if (!$certificate) {
        return view('certificate.invalid');
    }

    return view('certificate.verify', compact('certificate'));
}
}
