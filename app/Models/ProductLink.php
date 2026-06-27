<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLink extends Model
{
   protected $fillable = [
    'order_id',
    'user_id',
    'batik_id',
    'title',
    'url',
];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}