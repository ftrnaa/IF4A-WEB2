<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        return asset('storage/' . $this->avatar);
    }

    return asset('images/default-avatar.png');
}

public function getFullNameAttribute()
{
    return trim(
        ($this->first_name ?? '') .
        ' ' .
        ($this->last_name ?? '')
    );
}

public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

public function dashboardStats(): array
    {
        $activeLicenses = $this->licenses()
            ->where('is_active', true)
            ->where('expired_at', '>=', today())
            ->count();

        $expiringLicenses = $this->licenses()
            ->where('is_active', true)
            ->where('expired_at', '>=', today())
            ->where('expired_at', '<=', today()->addDays(30))
            ->count();

        $totalCerts = $this->certificates()->count();

        $totalSpent = $this->transactions()
            ->where('status', 'paid')
            ->sum('amount');

        $txCount = $this->transactions()
            ->where('status', 'paid')
            ->count();

        return [
            'active_licenses'   => $activeLicenses,
            'expiring_licenses' => $expiringLicenses,
            'total_certs'       => $totalCerts,
            'total_spent'       => $totalSpent,
            'transaction_count' => $txCount,
        ];
    }
}