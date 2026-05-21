<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KoleksiController extends Controller
{
    private int $perPage = 12;

    
    private function paginate(array $items, Request $request): LengthAwarePaginator
    {
        $collection  = collect($items);
        $currentPage = (int) $request->get('page', 1);

        return new LengthAwarePaginator(
            $collection->forPage($currentPage, $this->perPage), // item halaman ini
            $collection->count(),                                // total semua item
            $this->perPage,
            $currentPage,
            [
                'path'  => $request->url(),
                'query' => $request->query(), // biar query string (search, dll) ikut terbawa
            ]
        );
    }

    public function index(Request $request)
    {
        $response = Http::get('http://btx.agunghakase.my.id/api/batik/getbatik');
        $json     = $response->json();
        $all      = $json['batiks'] ?? [];

        $motifs = $this->paginate($all, $request);

        return view('pages.koleksi', compact('motifs'));
    }

    public function search(Request $request)
    {
        $keyword  = $request->search;
        $response = Http::get('http://btx.agunghakase.my.id/api/batik/search', [
            'q' => $keyword,
        ]);
        $json = $response->json();
        $all  = $json['batiks'] ?? [];

        $motifs = $this->paginate($all, $request);

        return view('pages.koleksi', compact('motifs'));
    }
}