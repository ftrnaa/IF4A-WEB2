<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'google_id',

        'phone',
        'city',
        'province',
        'bio',
        'avatar',

        'notif_license',
        'notif_cert',
        'notif_promo',
        'notif_news',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'user_id');
}
public function paidOrders()
{
    return $this->hasMany(Order::class, 'user_id')
                ->where('status', 'paid');
}
public function getFullNameAttribute()
{
    return $this->first_name . ' ' . $this->last_name;
}
}