<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\BatikHelper;

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
        $raw = Cache::remember('batik_products', 3600, function () {

            $response = Http::timeout(60)
                ->get('https://btx.agunghakase.my.id/api/batik/getall');

            return $response->json()['batiks'] ?? [];
        });

        // helper transform (SAMA DENGAN ADMIN)
        // helper transform + nama otomatis dari keyword
$all = collect($raw)
    ->map(function ($item) {

        $formatted = BatikHelper::format($item);

        $keyword = strtolower(trim($item['keyword'] ?? ''));

        // pecah keyword jadi array kata
        $words = explode(' ', $keyword);

        // cari posisi kata "batik"
        $batikIndex = array_search('batik', $words);

        if ($batikIndex !== false) {

            // ambil 3 kata setelah "batik"
            $nameWords = array_slice($words, $batikIndex + 1, 3);

            $formatted['name'] = ucwords(implode(' ', $nameWords));
        }

        return $formatted;
    })
    ->toArray();

        // =========================
        // FILTER KATEGORI
        // =========================
        $selectedCategory = strtolower(trim($request->kategori ?? ''));
        $keyword = strtolower(trim($request->search ?? ''));

        if ($selectedCategory && $selectedCategory !== 'semua') {
            $all = collect($all)
                ->filter(fn($item) =>
                    strtolower($item['kategori'] ?? '') === $selectedCategory
                )
                ->values()
                ->toArray();
        }

        if ($keyword) {
            $all = collect($all)
                ->filter(function ($item) use ($keyword) {

                    $text =
                        strtolower($item['name'] ?? '') . ' ' .
                        strtolower($item['kategori'] ?? '') . ' ' .
                        strtolower($item['description'] ?? '');

                    return str_contains($text, $keyword);
                })
                ->values()
                ->toArray();
        }

        $categories = collect($all)
            ->pluck('kategori')
            ->unique()
            ->sort()
            ->values();

        $motifs = $this->paginate($all, $request);

        return view('pages.koleksi', compact('motifs', 'categories'));
    }
}