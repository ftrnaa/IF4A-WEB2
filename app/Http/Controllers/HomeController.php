<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured motifs.
     */
    public function index(): View
{
    $featuredMotifs = [
        [
            'id' => 1,
            'name' => 'Motif Batik Modern',
            'category' => 'Batik',
            'price' => 150000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-batik-modern',
        ],
        [
            'id' => 2,
            'name' => 'Motif Floral Elegan',
            'category' => 'Floral',
            'price' => 120000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-floral-elegan',
        ],
        [
            'id' => 3,
            'name' => 'Motif Geometris',
            'category' => 'Geometric',
            'price' => 180000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-geometris',
        ],
        [
            'id' => 4,
            'name' => 'Motif Tradisional',
            'category' => 'Tradisional',
            'price' => 200000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-tradisional',
        ],
        [
            'id' => 5,
            'name' => 'Motif Abstract Art',
            'category' => 'Abstract',
            'price' => 170000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-abstract-art',
        ],
        [
            'id' => 6,
            'name' => 'Motif Luxury Gold',
            'category' => 'Luxury',
            'price' => 250000,
            'image' => 'https://via.placeholder.com/300',
            'slug' => 'motif-luxury-gold',
        ],
    ];

    return view('pages.home', compact('featuredMotifs'));
}
}
