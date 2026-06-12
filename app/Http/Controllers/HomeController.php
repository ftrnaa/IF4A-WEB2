<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Batik;

class HomeController extends Controller
{
    public function index(): View
    {
        $motifs = Batik::inRandomOrder()
            ->take(6)
            ->get();

        return view('pages.home', compact('motifs'));
    }
}