<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductLinkController;
use App\Http\Controllers\LicenseController;
use App\Http\controllers\AdminDashboardController;
use App\Http\Controllers\adminTransactionController;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::view('/tentang', 'pages.about')->name('about');

Route::view('/masuk', 'pages.login')->name('login');

Route::view('/daftar', 'pages.register')->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');
    

// STEP 1: Form email
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.request');

// STEP 1: Kirim OTP
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('password.sendOtp');

// STEP 2: Verifikasi OTP
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.verifyOtp');

// STEP 3: Reset Password (FINAL)
Route::post('/reset-password-with-otp', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.resetWithOtp');
// dashboard admin
Route::middleware(['auth', 'admin'])->group(function () {

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

    // PRODUK
    Route::get('/admin/produk', [AdminProdukController::class, 'index'])
        ->name('admin.produk');

    Route::put('/admin/produk/{id}/deskripsi', 
        [AdminProdukController::class, 'updateDeskripsi']
    );

    // TRANSAKSI
        Route::get('/admin/transaksi', [AdminTransactionController::class, 'index'])
            ->name('admin.transaksi');
 
        Route::get('/admin/transaksi/{order}', [AdminTransactionController::class, 'show'])
            ->name('admin.transaksi.show');
 

    Route::delete('/admin/produk/{id}', [AdminProdukController::class, 'destroy'])->name('admin.produk.destroy');

    
});
// dashboard user
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get(
    '/dashboard/lisensi',
    [LicenseController::class, 'index']
)->name('dashboard.licenses');
    Route::post('/product-links', [ProductLinkController::class, 'store'])
        ->name('product-links.store');
    Route::get(
    '/dashboard/license/{order}/motif-pdf',
    [LicenseController::class, 'downloadMotifPdf']
        )->name('license.motif.pdf');
    Route::get('/license/certificate/{order}', [LicenseController::class, 'downloadCertificatePdf'])
    ->name('license.certificate.pdf');
    Route::delete('/product-links/{productLink}', [ProductLinkController::class, 'destroy'])
    ->name('product-links.destroy');
Route::get(
    '/license/certificate/{order}',
    [LicenseController::class, 'downloadCertificatePdf']
)->name('license.certificate.pdf');
    Route::get(
    '/admin/transaksi/{order}/sertifikat',
    [LicenseController::class, 'viewCertificatePdf']
)->name('admin.transaksi.sertifikat');

});
Route::get('/verify/{token}', [LicenseController::class, 'verifyCertificate'])
    ->name('certificate.verify');

Route::get('/phpinfo', function () {
    phpinfo();
});

// profil user 
Route::get('/dashboard/profil', [ProfilController::class, 'index'])
    ->name('pages.users.profil');

Route::post('/dashboard/profil/update-info', [ProfilController::class, 'updateInfo'])
    ->name('pages.users.profil.update-info');

Route::post('/dashboard/profil/update-password', [ProfilController::class, 'updatePassword'])
    ->name('pages.users.profil.update-password');

Route::post('/dashboard/profil/update-notifications', [ProfilController::class, 'updateNotifications'])
    ->name('pages.users.profil.update-notif');

Route::delete('/dashboard/profil/delete-account', [ProfilController::class, 'deleteAccount'])
    ->name('pages.users.profil.delete-account');

// ================== KOLEKSI BATIK ==================
Route::get('/koleksi', [KoleksiController::class, 'index'])->name('koleksi');

// Route detail batik berdasarkan ID dari API
Route::get('/koleksi/{id}', [DetailController::class, 'show'])->name('detail');

Route::get('/motif/{slug}', function ($slug) {

    $motifs = [
        ['nama'=>'Parang Kusumo','kategori'=>'klasik','harga'=>250000,'img'=>'images/batik1.jpg'],
        ['nama'=>'Mega Mendung','kategori'=>'pesisir','harga'=>180000,'img'=>'images/batik2.jpg'],
        ['nama'=>'Kawung','kategori'=>'keraton','harga'=>300000,'img'=>'images/batik3.jpg'],
        ['nama'=>'Batik Kontemporer','kategori'=>'modern','harga'=>150000,'img'=>'images/batik4.jpg'],
        ['nama'=>'Sidomukti','kategori'=>'klasik','harga'=>220000,'img'=>'images/batik5.jpg'],
        ['nama'=>'Truntum','kategori'=>'keraton','harga'=>200000,'img'=>'images/batik6.jpg'],
    ];

    $found = null;

    foreach ($motifs as $m) {
        if (Str::slug($m['nama']) === $slug) {
            $found = $m;
            break;
        }
    }

    if (!$found) abort(404);

    $motif = (object)[
        'nama' => $found['nama'],
        'kategori' => $found['kategori'],
        'harga' => $found['harga'],
        'thumbnail' => asset($found['img']),
        'galeri' => [
            asset($found['img']),
            asset($found['img']),
            asset($found['img']),
        ],
        'deskripsi' => 'Motif '.$found['nama'].' adalah warisan budaya Nusantara yang memiliki filosofi mendalam.'
    ];

    $relatedMotifs = collect($motifs)->where('nama','!=',$found['nama'])->take(4);

    return view('pages.detail', compact('motif','relatedMotifs'));
})->name('motif.detail');

// Checkout
Route::middleware('auth')->group(function () {

    Route::get(
        '/checkout/{id}',
        [CheckoutController::class, 'index']
    )->name('checkout');

    Route::post(
        '/checkout/store',
        [CheckoutController::class, 'store']
    )->name('checkout.store');

});
// payment 
Route::middleware('auth')->group(function () {

    Route::get(
        '/payment/{order}',
        [PaymentController::class, 'show']
    )->name('payment');

});
Route::post('/midtrans/notification', [PaymentController::class, 'notification']);

Route::get('/payment/success/{order}', [PaymentController::class, 'success'])
    ->name('successpayment');

Route::get( '/profil',                  [ProfilController::class, 'index'])               ->name('profil');

Route::post('/profil/update-info',      [ProfilController::class, 'updateInfo'])          ->name('profil.update-info');

Route::post('/profil/update-password',  [ProfilController::class, 'updatePassword'])      ->name('profil.update-password');

Route::post('/profil/update-notif',     [ProfilController::class, 'updateNotifications']) ->name('profil.update-notif');

Route::post('/profil/delete-account',   [ProfilController::class, 'deleteAccount'])       ->name('profil.delete-account');