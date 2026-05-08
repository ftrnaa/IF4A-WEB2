<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function show(Request $request, $id)
    {
        $motif   = null;
        $all     = [];
        $keyword = $request->query('q');

        if ($keyword) {
            try {
                $response = Http::timeout(5)
                    ->retry(2, 100)
                    ->get('http://btx.agunghakase.my.id/api/batik/search', [
                        'q' => $keyword,
                    ]);

                if ($response->successful()) {
                    $batiks = $response->json()['batiks'] ?? [];
                    $all    = $batiks;
                    $motif  = collect($batiks)->first(
                        fn($item) => (string)($item['id'] ?? '') === (string)$id
                    );
                }
            } catch (\Exception $e) {
                $motif = null;
            }
        }

        // Fallback: brute force kalau tidak ada keyword
        if (!$motif) {
            $page    = 1;
            $maxPage = 50;

            while ($page <= $maxPage) {
                try {
                    $response = Http::timeout(5)
                        ->retry(2, 100)
                        ->get('http://btx.agunghakase.my.id/api/batik/getbatik', [
                            'page' => $page,
                        ]);

                    if (!$response->successful()) break;

                    $json   = $response->json();
                    $batiks = $json['batiks'] ?? [];

                    if (empty($batiks)) break;

                    $all = array_merge($all, $batiks);

                    $found = collect($batiks)->first(
                        fn($item) => (string)($item['id'] ?? '') === (string)$id
                    );

                    if ($found) {
                        $motif = $found;
                        break;
                    }

                    $page++;
                } catch (\Exception $e) {
                    break;
                }
            }
        }

        if (!$motif) {
            return view('pages.detail', [
                'motif'         => null,
                'costumeFiles'  => [],
                'relatedMotifs' => [],
                'error'         => 'Data tidak ditemukan atau API bermasalah'
            ]);
        }

        // Decode file_costume
        $costumeFiles = [];
        if (!empty($motif['file_costume'])) {
            $decoded = json_decode($motif['file_costume'], true);
            if (is_array($decoded)) {
                $costumeFiles = $decoded;
            }
        }

        // Related motifs
        $relatedMotifs = collect($all)
            ->filter(fn($item) => (string)($item['id'] ?? '') !== (string)$id)
            ->take(4)
            ->values()
            ->toArray();

        return view('pages.detail', [
            'motif'         => $motif,
            'costumeFiles'  => $costumeFiles,
            'relatedMotifs' => $relatedMotifs,
            'error'         => null
        ]);
    }
}