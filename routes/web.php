<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

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
// dashboard admin
Route::view('/admin', 'pages.admin.dashboard')->name('admin.dashboard');
Route::view('/admin/transaksi', 'pages.admin.transaksi')->name('admin.transaksi');
Route::view('/admin/produk', 'pages.admin.produk')->name('admin.produk');
Route::view('/admin/sertifikat', 'pages.admin.sertifikat')->name('admin.sertifikat');
Route::view('/admin/laporan', 'pages.admin.laporan')->name('admin.laporan');

// dashboard user
Route::view('/dashboard', 'pages.users.dashboard')->name('user.dashboard');
Route::view('/dashboard/lisensi', 'pages.users.lisensi')->name('user.lisensi');
Route::view('/dashboard/sertifikat', 'pages.users.sertifikat')->name('user.sertifikat');
Route::view('/dashboard/profil', 'pages.users.profil')->name('user.profil');

// ================== KOLEKSI BATIK ==================
Route::view('/koleksi', 'pages.koleksi')->name('koleksi');
Route::view('/detail', 'pages.detail')->name('detail');
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