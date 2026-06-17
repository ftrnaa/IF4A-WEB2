<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
    'order_id',
    'user_id',
    'certificate_number',
    'qr_token',
    'issued_at',
];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}