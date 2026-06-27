<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Batik;
use App\Helpers\BatikHelper;

class BatikSyncService
{
    /**
     * Mengambil data dari API
     */
    private function getApiData()
    {
        $response = Http::get('https://btx.agunghakase.my.id/api/batik/getall');

        if (!$response->successful()) {
            return [];
        }

        return $response->json('batiks') ?? [];
    }

    /**
     * Statistik sebelum sync
     */
    public function getStatistics()
    {
        $apiData = $this->getApiData();

        $dbTotal = Batik::count();

        $dbKategori = Batik::select('kategori')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        $apiKategori = [];

        foreach ($apiData as $item) {

            $kategori = BatikHelper::extractKategori($item['style'] ?? '');

            $apiKategori[$kategori] =
                ($apiKategori[$kategori] ?? 0) + 1;
        }

        return [
            'database_total' => $dbTotal,
            'api_total' => count($apiData),
            'database_kategori' => $dbKategori,
            'api_kategori' => $apiKategori,
        ];
    }
    public function sync()
{
    $apiData = $this->getApiData();

    $inserted = 0;
    $updated = 0;
    $unchanged = 0;

    foreach ($apiData as $item) {

        $nama = BatikHelper::extractNama($item['keyword'] ?? '');

        $kategori = BatikHelper::extractKategori($item['style'] ?? '');

        $deskripsi = BatikHelper::generateDeskripsi(
            $nama,
            $kategori,
            $item['warna'] ?? 'beragam'
        );

        $existing = Batik::where('api_id', $item['id'])->first();

        // Data baru
        if (!$existing) {

            $inserted++;

        } else {

            // Apakah ada perubahan?
            $changed =
                $existing->nama != $nama ||
                $existing->keyword != ($item['keyword'] ?? null) ||
                $existing->kategori != $kategori ||
                $existing->warna != ($item['warna'] ?? null) ||
                $existing->preview_image != ($item['file_preview'] ?? null) ||
                $existing->video != ($item['file_video'] ?? null) ||
                $existing->seed != ($item['seed'] ?? null);

            if ($changed) {
                $updated++;
            } else {
                $unchanged++;
            }
        }

        Batik::updateOrCreate(
            [
                'api_id' => $item['id']
            ],
            [
                'nama' => $nama,
                'keyword' => $item['keyword'] ?? null,
                'deskripsi' => $deskripsi,
                'kategori' => $kategori,
                'warna' => $item['warna'] ?? null,
                'preview_image' => $item['file_preview'] ?? null,
                'costume_images' => !empty($item['file_costume'])
                    ? json_decode($item['file_costume'], true)
                    : null,
                'video' => $item['file_video'] ?? null,
                'seed' => $item['seed'] ?? null,
                'api_created_at' => $item['created_at'] ?? null,
            ]
        );
    }

    return [
        'inserted' => $inserted,
        'updated' => $updated,
        'unchanged' => $unchanged,
        'total' => count($apiData),
    ];
}
}