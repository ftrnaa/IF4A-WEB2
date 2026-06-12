<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Batik;
use App\Helpers\BatikHelper;

class SyncBatik extends Command
{
    protected $signature = 'batik:sync';

    protected $description = 'Sync batik data from API';

    public function handle()
    {
        $response = Http::get('https://btx.agunghakase.my.id/api/batik/getall');

       $data = $response->json('batiks');

        foreach ($data as $item) {
$nama = BatikHelper::extractNama($item['keyword'] ?? '');

$kategori = BatikHelper::extractKategori($item['style'] ?? '');

$deskripsi = BatikHelper::generateDeskripsi(
    $nama,
    $kategori,
    $item['warna'] ?? 'beragam'
);
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

        $this->info('Sync selesai');
    }

   
}