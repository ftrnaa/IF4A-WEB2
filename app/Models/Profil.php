<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'notif_license'     => 'boolean',
        'notif_cert'        => 'boolean',
        'notif_promo'       => 'boolean',
        'notif_news'        => 'boolean',
    ];

    // ─────────────────────────────────────────────────────────────────────
    // ACCESSOR: Nama lengkap (first + last)
    // ─────────────────────────────────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''))
               ?: $this->name;
    }

    // ─────────────────────────────────────────────────────────────────────
    // ACCESSOR: URL avatar — fallback ke inisial nama jika belum upload
    // ─────────────────────────────────────────────────────────────────────
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::url($this->avatar);
        }

        // Generate avatar dari inisial nama (pakai UI Avatars)
        $initials = urlencode(trim(
            ($this->first_name ?? '') . ' ' . ($this->last_name ?? '')
        ) ?: $this->name);

        return "https://ui-avatars.com/api/?name={$initials}&size=200&background=4A7C59&color=fff&bold=true";
    }
}