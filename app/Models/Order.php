<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Batik;
use App\Models\ProductLink;

class Order extends Model
{
    protected $fillable = [

        'user_id',
        'batik_id',

        'kode_order',

        'nama',
        'email',
        'telepon',
        'nik',

        'perusahaan',
        'npwp',
        'bidang_usaha',
        'alamat',

        'catatan',

        'total',

        'status',
        'payment_type',
        'payment_channel'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batik()
{
    return $this->belongsTo(Batik::class, 'batik_id');
}
public function productLinks()
{
    return $this->hasMany(ProductLink::class);
}
public function certificate()
{
    return $this->hasOne(Certificate::class);
}
}