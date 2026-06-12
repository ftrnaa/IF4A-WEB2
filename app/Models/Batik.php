<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batik extends Model
{
   protected $fillable = [
    'api_id',
    'nama',
    'harga',
    'keyword',
    'deskripsi',
    'kategori',
    'warna',
    'preview_image',
    'costume_images',
    'video',
    'seed',
    'api_created_at',
];

    protected $casts = [
        'costume_images' => 'array',
    ];

    public function getPreviewUrlAttribute()
    {
        return 'https://btx.agunghakase.my.id/api/image/' . $this->preview_image;
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'batik_id');
}
}