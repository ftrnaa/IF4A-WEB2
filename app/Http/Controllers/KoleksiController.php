<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batik;

class KoleksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Batik::query();

        // =========================
        // FILTER KATEGORI
        // =========================
        $selectedCategory = strtolower(trim($request->kategori ?? ''));

        if ($selectedCategory && $selectedCategory !== 'semua') {

            $query->whereRaw('LOWER(kategori) = ?', [$selectedCategory]);
        }

        // =========================
        // SEARCH
        // =========================
        $keyword = strtolower(trim($request->search ?? ''));

        if ($keyword) {

            $query->where(function ($q) use ($keyword) {

                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$keyword}%"]);
            });
        }

        // =========================
        // PAGINATION
        // =========================
        $motifs = $query
    ->orderByRaw('costume_images IS NULL ASC')
    ->latest()
    ->paginate(12)
    ->withQueryString();

        // =========================
        // CATEGORIES
        // =========================
        $categories = Batik::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('pages.koleksi', compact(
            'motifs',
            'categories'
        ));
    }
}