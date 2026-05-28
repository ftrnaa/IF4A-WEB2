<?php

namespace App\Helpers;

class BatikHelper
{
    public static function format($item)
    {
        // =========================
        // NAMA
        // =========================
        $name = $item['keyword'] ?? 'Motif Batik';

        // =========================
        // STYLE & KATEGORI
        // =========================
        $style = strtolower($item['style'] ?? '');

        $words = explode(' ', $style);

        $kategori = 'kontemporer';

        $batikIndex = array_search('batik', $words);

        if ($batikIndex !== false) {

            $slice = array_slice($words, $batikIndex + 1, 2);

            if (!empty($slice)) {
                $kategori = implode(' ', $slice);
            }
        }

        // =========================
        // DESKRIPSI
        // =========================
        $seed = crc32(
            ($item['seed'] ?? '') .
            ($item['id'] ?? '')
        );

        $templates = [
            "Motif {$kategori} dengan karakter elegan dan modern.",
            "Desain {$kategori} terinspirasi unsur alam dan budaya.",
            "Koleksi {$kategori} bernuansa artistik dan premium.",
            "Motif {$kategori} cocok untuk fashion dan dekorasi eksklusif.",
        ];

        $description = $templates[
            $seed % count($templates)
        ];

        // =========================
        // BASE URL IMAGE
        // =========================
        $baseUrl = 'https://btx.agunghakase.my.id/api/image/';

        // =========================
        // IMAGE UTAMA
        // =========================
        $image = 'https://via.placeholder.com/400x300?text=BatikAI';

        if (!empty($item['file_preview'])) {

            $preview = $item['file_preview'];

            // kalau sudah URL full
            if (filter_var($preview, FILTER_VALIDATE_URL)) {

                $image = $preview;

            } else {

                $image = $baseUrl . ltrim($preview, '/');
            }
        }

        // =========================
        // PREVIEW CODE
        // contoh:
        // 9543_312036356272651_xxx.webp
        // hasil => 9543
        // =========================
        $previewCode = null;

        if (!empty($item['file_preview'])) {

            preg_match(
                '/^(\d+)_/',
                $item['file_preview'],
                $match
            );

            $previewCode = $match[1] ?? null;
        }

        // =========================
        // COSTUME IMAGE
        // =========================
        $costume = [];

        if (!empty($item['file_costume'])) {

            $data = is_string($item['file_costume'])
                ? json_decode($item['file_costume'], true)
                : $item['file_costume'];

            if (is_array($data)) {

                $costume = array_map(function ($file) use ($baseUrl) {

                    // kalau sudah URL full
                    if (filter_var($file, FILTER_VALIDATE_URL)) {
                        return $file;
                    }

                    return $baseUrl . ltrim($file, '/');

                }, $data);
            }
        }

        // =========================
        // PRICE
        // =========================
        $price = 150000;

        // =========================
        // RETURN FINAL
        // =========================
        return [

            'id'          => $item['id'] ?? null,

            'name'        => $name,

            'kategori'    => $kategori,

            'price'       => $price,

            'img'         => $image,

            'costume'     => $costume,

            'description' => $description,

            'style'       => $style,

            'warna'       => $item['warna'] ?? '-',

            'seed'        => $item['seed'] ?? '-',

            // kode depan filename
            'code'        => $previewCode,

            'created_at'  => $item['created_at'] ?? null,
        ];
    }
}