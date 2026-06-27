<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Certificate;



class LicenseController extends Controller
{
    public function index()
{
    $orders = Order::with([
    'batik',
    'productLinks' => function ($query) {
        $query->where('user_id', auth()->id());
    }
])
->where('user_id', auth()->id())
->where('status', 'paid')
->latest()
->get();

    $aktif = 0;
    $hampirHabis = 0;
    $kedaluwarsa = 0;

    foreach ($orders as $order) {

        if (!$order->license_expired_at) {
            continue;
        }

        $daysLeft = now()->diffInDays(
            Carbon::parse($order->license_expired_at),
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

    return view(
        'pages.users.lisensi',
        compact(
            'orders',
            'aktif',
            'hampirHabis',
            'kedaluwarsa'
        )
    );
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
   $verifyUrl = url('/verify/' . $certificate->qr_token);

$builder = new Builder(
    writer: new PngWriter(),
    data: $verifyUrl,
    size: 300,
    margin: 10,
);

$result = $builder->build();

$qrSrc = 'data:image/png;base64,' . base64_encode($result->getString());


    /*
    |--------------------------------------------------------------------------
    | 4. GENERATE PDF
    |--------------------------------------------------------------------------
    */
    $issuedAt = $order->created_at;
$expiredAt = $order->license_expired_at;

$pdf = Pdf::loadView('pdf.certificate', [
    'order' => $order,
    'batik' => $order->batik,
    'qrSrc' => $qrSrc,
    'certificate' => $certificate,
    'issuedAt' => $issuedAt,
    'expiredAt' => $expiredAt,
    'verifyUrl' => $verifyUrl,
]);

    return $pdf->download('Sertifikat-' . $order->kode_order . '.pdf');
}
public function verifyCertificate($token)
{
    $certificate = Certificate::with(['order.batik', 'order.user'])
        ->where('qr_token', $token)
        ->first();

    if (!$certificate) {
    abort(404, 'Sertifikat tidak ditemukan');
}

    return view('certificate.verify', compact('certificate'));
}
public function renewPayment(Order $order)
{
    if ($order->user_id != auth()->id()) {
        abort(403);
    }

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // Buat order baru khusus perpanjangan
    $renewOrder = Order::create([

    'user_id' => $order->user_id,
    'batik_id' => $order->batik_id,

    'kode_order' =>
        'RENEW-' . strtoupper(Str::random(10)),

    'nama' => $order->nama,
    'email' => $order->email,
    'telepon' => $order->telepon,
    'nik' => $order->nik,

    'perusahaan' => $order->perusahaan,
    'npwp' => $order->npwp,
    'bidang_usaha' => $order->bidang_usaha,
    'alamat' => $order->alamat,

    'total' => $order->total,

    'status' => 'pending',

    // Tambahan
    'is_renewal' => true,
    'renew_from_id' => $order->id,
]);
    $params = [

        'transaction_details' => [
            'order_id' => $renewOrder->kode_order,
            'gross_amount' => (int) $renewOrder->total,
        ],

        'customer_details' => [
            'first_name' => $renewOrder->nama,
            'email' => $renewOrder->email,
            'phone' => $renewOrder->telepon,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    return view(
        'pages.payment',
        [
            'order' => $renewOrder,
            'snapToken' => $snapToken
        ]
    );
}
public function previewCertificate(Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $order->load(['batik', 'user']);

    $certificate = Certificate::firstOrCreate(
        ['order_id' => $order->id],
        [
            'certificate_number' => 'BTA-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'qr_token' => Str::uuid(),
            'issued_at' => now(),
        ]
    );

    $verifyUrl = url('/verify/' . $certificate->qr_token);

    $result = \Endroid\QrCode\Builder\Builder::create()
        ->writer(new \Endroid\QrCode\Writer\PngWriter())
        ->data($verifyUrl)
        ->size(300)
        ->margin(10)
        ->build();

    $qrSrc = 'data:image/png;base64,' . base64_encode($result->getString());

    $issuedAt = $order->created_at;
$expiredAt = $order->license_expired_at;

    return view('pdf.certificate', [
    'order' => $order,
    'batik' => $order->batik, // ✅ INI YANG KURANG
    'qrSrc' => $qrSrc,
    'certificate' => $certificate,
    'issuedAt' => $issuedAt,
    'expiredAt' => $expiredAt,
     'verifyUrl' => $verifyUrl, 
]);
}
public function viewCertificatePdf(Order $order)
{
    abort_unless(
        auth()->check() &&
        auth()->user()->role === 'admin',
        403,
        'Akses ditolak.'
    );

    $order->load(['user', 'batik']);

    return app(AdminTransactionController::class)
        ->viewCertificate($order);
}
}