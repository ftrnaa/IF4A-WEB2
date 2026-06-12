<?php

namespace App\Helpers;

class BatikHelper
{
    public static function extractKategori($style): string
    {
        $style = strtolower($style);

        $words = explode(' ', $style);

        $index = array_search('batik', $words);

        if ($index !== false) {

            return implode(' ', array_slice($words, $index + 1, 2));
        }

        return 'kontemporer';
    }

    public static function extractNama($keyword): string
    {
        $keyword = strtolower(trim($keyword));

        $words = explode(' ', $keyword);

        $index = array_search('batik', $words);

        if ($index !== false) {

            return ucwords(
                implode(' ', array_slice($words, $index + 1, 3))
            );
        }

        return 'Motif Batik';
    }
    public static function generateDeskripsi($nama, $kategori, $warna)
{
    $templates = [
        "Motif batik {$nama} merupakan batik {$kategori} dengan dominasi warna {$warna}. Cocok digunakan untuk berbagai acara formal maupun santai.",

        "Batik {$nama} menghadirkan keindahan motif {$kategori} dengan perpaduan warna {$warna} yang elegan dan berkarakter.",

        "Desain {$nama} termasuk kategori {$kategori} yang menampilkan nuansa warna {$warna}. Memberikan kesan unik dan bernilai seni tinggi.",

        "Motif {$nama} adalah salah satu variasi batik {$kategori} dengan sentuhan warna {$warna} yang menarik dan modern.",
    ];

    return $templates[array_rand($templates)];
}
}