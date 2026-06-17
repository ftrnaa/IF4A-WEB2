<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\BatikHelper;

class AdminProdukController extends Controller
{
    public function index(): View
    {
        $raw = Cache::remember('batik_products', 3600, function () {

            $response = Http::timeout(60)
                ->get('https://btx.agunghakase.my.id/api/batik/getall');

            return $response->json()['batiks'] ?? [];
        });

        // =========================
        // FORMAT PRODUK
        // =========================
        $collection = collect($raw)
            ->map(function ($item) {
                return BatikHelper::format($item);
            });

        // =========================
        // PAGINATION
        // =========================
        $perPage = 12;
        $page = request()->get('page', 1);

        $products = new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        // =========================
        // CATEGORIES
        // =========================
        $categories = $collection
            ->pluck('kategori')
            ->unique()
            ->values()
            ->map(function ($cat, $i) use ($collection) {

                return [
                    'id'    => $i + 1,
                    'name'  => $cat,
                    'desc'  => "Koleksi motif {$cat} hasil AI Batik.",
                    'count' => $collection->where('kategori', $cat)->count(),
                ];
            })
            ->toArray();

        return view('pages.admin.produk', compact(
            'products',
            'categories'
        ));
    }
}