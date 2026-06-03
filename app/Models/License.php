<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class License extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'license_number', 'user_id', 'batik_motif_id',
        'transaction_id', 'started_at', 'expired_at',
        'type', 'is_active',
    ];

    protected $casts = [
        'started_at' => 'date',
        'expired_at' => 'date',
        'is_active'  => 'boolean',
    ];

    // ── Boot ───────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $model) {
            if (empty($model->license_number)) {
                $model->license_number = self::generateLicenseNumber();
            }
        });
    }

    public static function generateLicenseNumber(): string
    {
        $date = now()->format('Ymd');
        $last = self::withTrashed()->whereDate('created_at', today())->count() + 1;
        return 'LIC-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batikMotif(): BelongsTo
    {
        return $this->belongsTo(BatikMotif::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // ── Computed Helpers (sesuai logika Blade) ─────────────

    /**
     * Status: active | expiring | expired
     */
    public function getStatusAttribute(): string
    {
        $daysLeft = $this->daysLeft;
        if ($daysLeft < 0)       return 'expired';
        if ($daysLeft <= 30)     return 'expiring';
        return 'active';
    }

    /**
     * Sisa hari dari hari ini ke expired_at
     */
    public function getDaysLeftAttribute(): int
    {
        return (int) today()->diffInDays($this->expired_at, false);
    }

    /**
     * Persentase progress bar (0–100) sama persis seperti Blade
     */
    public function getProgressPercentAttribute(): int
    {
        $total   = 365;
        $elapsed = $total - max(0, $this->daysLeft);
        return max(0, min(100, (int) round(($elapsed / $total) * 100)));
    }

    /**
     * Label status dalam bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'Aktif',
            'expiring' => 'Hampir Habis',
            'expired'  => 'Kedaluwarsa',
            default    => '-',
        };
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('expired_at', '>=', today());
    }

    public function scopeExpiring($query, int $days = 30)
    {
        return $query->active()
                     ->where('expired_at', '<=', today()->addDays($days));
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<', today());
    }
}
