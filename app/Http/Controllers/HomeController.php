<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Helpers\BatikHelper;

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

            // format data seperti koleksi
            $formatted = collect($data)
                ->map(fn($item) => BatikHelper::format($item))
                ->toArray();

            // random
            shuffle($formatted);

            // ambil 6
            return array_slice($formatted, 0, 6);
        });

        return view('pages.home', compact('motifs'));
    }
}