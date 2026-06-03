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
use App\Http\Controllers\ForgotPasswordController;
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

    Route::view('/admin', 'pages.admin.dashboard')
        ->name('admin.dashboard');

    Route::get('/admin/produk', [AdminProdukController::class, 'index'])
        ->name('admin.produk');

    Route::view('/admin/transaksi', 'pages.admin.transaksi')
        ->name('admin.transaksi');

    Route::view('/admin/sertifikat', 'pages.admin.sertifikat')
        ->name('admin.sertifikat');

    Route::view('/admin/laporan', 'pages.admin.laporan')
        ->name('admin.laporan');
});
// dashboard user
Route::view('/dashboard', 'pages.users.dashboard')->name('user.dashboard');
Route::view('/dashboard/lisensi', 'pages.users.lisensi')->name('user.lisensi');
Route::view('/dashboard/sertifikat', 'pages.users.sertifikat')->name('user.sertifikat');
// profil user 
Route::get('/dashboard/profil', [ProfilController::class, 'index'])
    ->name('user.profil');

Route::post('/dashboard/profil/update-info', [ProfilController::class, 'updateInfo'])
    ->name('user.profile.update-info');

Route::post('/dashboard/profil/update-password', [ProfilController::class, 'updatePassword'])
    ->name('user.profile.update-password');

Route::post('/dashboard/profil/update-notifications', [ProfilController::class, 'updateNotifications'])
    ->name('user.profile.update-notif');

Route::delete('/dashboard/profil/delete-account', [ProfilController::class, 'deleteAccount'])
    ->name('user.profile.delete-account');
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

Route::view('/checkout', 'pages.checkout')->name('checkout');

Route::post('/checkout/process', function () {
    return 'Checkout berhasil (dummy)';
})->name('checkout.process');

Route::view('/payment', 'pages.payment')->name('payment');

Route::view('/succespayment', 'pages.successpayment')->name('successpayment');

Route::middleware(['auth'])->prefix('profil')->group(function () {

    Route::get('/', [ProfilController::class, 'index'])
        ->name('pages.users.profil');

    Route::post('/update-info', [ProfilController::class, 'updateInfo'])
        ->name('pages.user.profil.update-info');

    Route::post('/update-password', [ProfilController::class, 'updatePassword'])
        ->name('pages.users.profil.update-password');

    Route::post('/update-notif', [ProfilController::class, 'updateNotifications'])
        ->name('pages.users.profil.update-notif');

    Route::delete('/delete-account', [ProfilController::class, 'deleteAccount'])
        ->name('pages.users.profil.delete-account');
});

Route::middleware(['auth', 'verified', 'role:user'])
    ->prefix('dashboard')
    ->name('user.')
    ->group(function () {

    // Beranda dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Lisensi
    Route::prefix('lisensi')->name('licenses.')->group(function () {
            Route::get('/',       [LicenseController::class, 'index'])->name('index');
            Route::get('/{id}',   [LicenseController::class, 'show'])->name('show');
    });

    // Sertifikat
    Route::prefix('sertifikat')->name('certificates.')->group(function () {
            Route::get('/',           [CertificateController::class, 'index'])->name('index');
            Route::get('/{id}',       [CertificateController::class, 'show'])->name('show');
            Route::get('/{id}/unduh', [CertificateController::class, 'download'])->name('download');
    });

    // Transaksi / riwayat pembelian
    Route::prefix('transaksi')->name('transactions.')->group(function () {
            Route::get('/',     [TransactionController::class, 'index'])->name('index');
            Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    });
});