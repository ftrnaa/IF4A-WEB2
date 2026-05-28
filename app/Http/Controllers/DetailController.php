<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Helpers\BatikHelper;

class DetailController extends Controller
{
    public function show($id)
    {
        $cacheKey = "detail_batik_$id";

        $result = Cache::remember($cacheKey, 3600, function () use ($id) {

            // =========================
            // GET ALL DATA SEKALI SAJA
            // =========================
            $res = Http::timeout(20)
                ->get('https://btx.agunghakase.my.id/api/batik/getall');

            if (!$res->successful()) {
                return null;
            }

            $all = $res->json()['batiks'] ?? [];

            // =========================
            // CARI MOTIF BERDASARKAN ID
            // =========================
            $motif = collect($all)->first(
                fn($i) => (string)($i['id'] ?? '') === (string)$id
            );

            if (!$motif) {
                return null;
            }

            return [
                'motif' => $motif,
                'all'   => $all
            ];
        });

        // =========================
        // JIKA TIDAK DITEMUKAN
        // =========================
        if (!$result) {

            return view('pages.detail', [
                'motif'         => null,
                'costumeFiles'  => [],
                'relatedMotifs' => [],
                'error'         => 'Data tidak ditemukan'
            ]);
        }

        // =========================
        // FORMAT DATA DENGAN HELPER
        // =========================
        $motif = BatikHelper::format($result['motif']);

        // =========================
        // COSTUME FILES
        // =========================
        $costumeFiles = $motif['costume'] ?? [];

        // =========================
        // RELATED MOTIFS
        // =========================
        $relatedMotifs = collect($result['all'])
            ->filter(fn($i) =>
                (string)($i['id'] ?? '') !== (string)$id
            )
            ->shuffle()
            ->take(4)
            ->map(fn($i) => BatikHelper::format($i))
            ->values()
            ->toArray();

        return view('pages.detail', compact(
            'motif',
            'costumeFiles',
            'relatedMotifs'
        ));
    }
}