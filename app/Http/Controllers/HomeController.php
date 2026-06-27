<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Batik;

class HomeController extends Controller
{
    public function index(): View
    {
        // Ambil 6 motif secara acak untuk ditampilkan di homepage
        $motifs = Batik::inRandomOrder()
            ->take(6)
            ->get();

        // Hitung total seluruh motif
        $totalMotif = Batik::count();

        return view('pages.home', compact(
            'motifs',
            'totalMotif'
        ));
    }
}