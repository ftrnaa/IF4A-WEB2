<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $response = Http::get('http://btx.agunghakase.my.id/api/batik/getbatik');

        $json = $response->json();

        $motifs = $json['batiks'] ?? [];

        // 🔥 ambil 6 data saja untuk homepage
        $motifs = array_slice($motifs, 0, 6);

        return view('pages.home', compact('motifs'));
    }
}