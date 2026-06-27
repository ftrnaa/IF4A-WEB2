<?php

namespace App\Http\Controllers;

use App\Models\Batik;
use App\Helpers\BatikHelper;
use App\Models\ProductLink;

class DetailController extends Controller
{
    public function show($id)
    {
        // =========================
        // AMBIL MOTIF
        // =========================
        $motif = Batik::find($id);

        // =========================
        // JIKA TIDAK ADA
        // =========================
        if (!$motif) {

    return view('pages.detail', [
        'motif' => null,
        'relatedMotifs' => [],
        'error' => 'Data tidak ditemukan'
    ]);
}

$productLinks = ProductLink::where('batik_id', $motif->id)
    ->latest()
    ->get();

        // =========================
        // RELATED MOTIFS
        // =========================
        $relatedMotifs = Batik::where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(function ($item) {

                $item->deskripsi = BatikHelper::generateDeskripsi(
                    $item->nama,
                    $item->kategori,
                    $item->warna
                );

                return $item;
            });

        return view('pages.detail', compact(
    'motif',
    'relatedMotifs',
    'productLinks'
));
    }
}