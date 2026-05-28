<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
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
        // PRODUCTS (PAKAI HELPER)
        // =========================
        $products = collect($raw)
            ->map(fn($item) => BatikHelper::format($item))
            ->toArray();

        // =========================
        // CATEGORIES (WAJIB ADA DI ADMIN)
        // =========================
        $categories = collect($products)
            ->pluck('kategori')
            ->unique()
            ->values()
            ->map(function ($cat, $i) use ($products) {

                return [
                    'id'    => $i + 1,
                    'name'  => $cat,
                    'desc'  => "Koleksi motif {$cat} hasil AI Batik.",
                    'count' => collect($products)->where('kategori', $cat)->count(),
                ];
            })
            ->toArray();

        return view('pages.admin.produk', compact(
            'products',
            'categories'
        ));
    }
}