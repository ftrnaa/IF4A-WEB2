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

Route::view('/admin', 'pages.admin.dashboard')->name('admin.dashboard');
Route::view('/admin/transaksi', 'pages.admin.transaksi')->name('admin.transaksi');
Route::view('/admin/produk', 'pages.admin.produk')->name('admin.produk');
Route::view('/admin/sertifikat', 'pages.admin.sertifikat')->name('admin.sertifikat');