<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KoleksiController extends Controller
{
    private int $perPage = 12;

    private function paginate(array $items, Request $request): LengthAwarePaginator
    {
        $collection = collect($items);

        $currentPage = (int) $request->get('page', 1);

        return new LengthAwarePaginator(
            $collection->forPage($currentPage, $this->perPage)->values(),
            $collection->count(),
            $this->perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    public function index(Request $request)
    {
        // CACHE 
        $all = Cache::rememberForever('all_batiks', function () {

            $response = Http::timeout(120)
                ->get('https://btx.agunghakase.my.id/api/batik/getall');

            $json = $response->json();

            return $json['batiks'] ?? [];
        });

        // AUTO DETECT KATEGORI DARI STYLE
$all = collect($all)->map(function ($item) {

    $style = strtolower(
        trim($item['style'] ?? '')
    );

    $words = explode(' ', $style);

    // hapus kata "batik" jika ada di depan
    if (($words[0] ?? '') === 'batik') {
        array_shift($words);
    }

    // ambil 2 kata pertama
    $kategori = array_slice($words, 0, 2);

    $item['kategori'] = implode(' ', $kategori);

    return $item;

})->toArray();
        // SEMUA KATEGORI UNIK
        $categories = collect($all)
            ->pluck('kategori')
            ->unique()
            ->sort()
            ->values();
        // FILTER KATEGORI
        $selectedCategory = strtolower(trim($request->kategori ?? ''));
        // SEARCH LOCAL
        $keyword = strtolower(trim($request->search ?? ''));
        // FILTER BERDASARKAN KATEGORI
if ($selectedCategory && $selectedCategory !== 'semua') {

    $all = collect($all)->filter(function ($item) use ($selectedCategory) {

        return strtolower($item['kategori'] ?? '') === $selectedCategory;

    })->values()->toArray();
}
        if ($keyword) {

            $all = collect($all)->filter(function ($item) use ($keyword) {

                $text =
                    strtolower($item['keyword'] ?? '') . ' ' .
                    strtolower($item['kategori'] ?? '') . ' ' .
                    strtolower($item['deskripsi'] ?? '');

                return str_contains($text, $keyword);

            })->values()->toArray();
        }

        $motifs = $this->paginate($all, $request);

        return view(
            'pages.koleksi',
            compact('motifs', 'categories')
        );
    }
}