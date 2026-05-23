<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
{
    $motifs = Cache::rememberForever('home_batiks', function () {

        $response = Http::timeout(30)
            ->connectTimeout(15)
            ->retry(3, 200)
            ->withoutVerifying()
            ->get('https://btx.agunghakase.my.id/api/batik/getall');

        $json = $response->json();

        $data = $json['batiks'] ?? [];

        // random sekali lalu simpan permanen
        shuffle($data);

        // ambil 6 data
        return array_slice($data, 0, 6);
    });

    return view('pages.home', compact('motifs'));
}
}