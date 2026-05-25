<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AdminProdukController extends Controller
{
    public function index(): View
    {
        $response = Http::timeout(30)
            ->connectTimeout(15)
            ->retry(3, 200)
            ->withoutVerifying()
            ->get('https://btx.agunghakase.my.id/api/batik/getall');

        $products = [];

        if ($response->successful()) {

            $json = $response->json();

$products = collect($json['batiks'] ?? [])->map(function ($item) {

                // =========================================
                // NAMA MOTIF
                // =========================================
                $keyword = $item['keyword'] ?? 'Motif Batik';

                // rapikan nama
                $name = ucwords(
                    str_replace(
                        ['batik pattern', ',', 'all-over'],
                        '',
                        strtolower($keyword)
                    )
                );

                // =========================================
                // KATEGORI DARI STYLE
                // ambil 2 kata setelah "batik"
                // =========================================
                $style = strtolower($item['style'] ?? '');

                $kategori = 'Kontemporer';

                if (str_contains($style, 'batik')) {

                    preg_match(
                        '/batik\s+([a-zA-Z0-9]+(?:\s+[a-zA-Z0-9]+)?)/i',
                        $style,
                        $matches
                    );

                    if (!empty($matches[1])) {
                        $kategori = ucwords(trim($matches[1]));
                    }
                }

                // =========================================
                // GENERATE DESKRIPSI
                // =========================================
                $deskripsi =
                    "{$kategori} merupakan motif batik bernuansa artistik ".
                    "dengan perpaduan elemen tradisional dan modern. ".
                    "Desain ini cocok digunakan untuk fashion premium, ".
                    "dekorasi, maupun koleksi batik eksklusif.";

                // =========================================
                // URL GAMBAR
                // =========================================
                $img = !empty($item['file_preview'])
    ? 'https://btx.agunghakase.my.id/storage/preview/' . ltrim($item['file_preview'], '/')
    : 'https://via.placeholder.com/400x300';
    
                return [
                    'id'          => $item['id'] ?? rand(1000, 9999),
                    'name'        => $name,
                    'cat'         => $kategori,
                    'price'       => rand(90000, 250000),
                    'sold'        => rand(10, 350),
                    'status'      => rand(0, 10) > 2 ? 'active' : 'draft',
                    'img'         => $img,
                    'description' => $deskripsi,
                    'style'       => $style,
                    'warna'       => $item['warna'] ?? '-',
                    'seed'        => $item['seed'] ?? '-',
                    'created_at'  => $item['created_at'] ?? now(),
                ];
            })->toArray();
        }

        // =========================================
        // AUTO GENERATE CATEGORIES
        // =========================================
        $uniqueCategories = collect($products)
            ->pluck('cat')
            ->unique()
            ->values();

        $colors = [
            '#7B5E3A',
            '#2C4A3E',
            '#C8A96E',
            '#3D6B5C',
            '#8B7355',
            '#5C4033',
            '#1A6FA8',
            '#8E44AD',
            '#C0392B',
            '#16A085',
        ];

        $categories = $uniqueCategories->map(function ($cat, $index) use ($products, $colors) {

            return [
                'id'    => $index + 1,
                'name'  => $cat,
                'desc'  => "Koleksi motif {$cat} hasil eksplorasi AI batik modern.",
                'count' => collect($products)->where('cat', $cat)->count(),
                'color' => $colors[$index % count($colors)],
            ];
        })->toArray();

        // =========================================
        // COLOR SWATCHES
        // =========================================
        $swatches = [
            '#4A3728',
            '#7B5E3A',
            '#C8A96E',
            '#2C4A3E',
            '#3D6B5C',
            '#8B7355',
            '#5C4033',
            '#1A6FA8',
            '#8E44AD',
            '#C0392B',
        ];

        return view('pages.admin.produk', compact(
            'products',
            'categories',
            'swatches'
        ));
    }
}