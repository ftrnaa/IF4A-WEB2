<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number', 'user_id', 'batik_motif_id',
        'amount', 'status', 'payment_method', 'payment_channel',
        'paid_at', 'snap_token', 'payment_url', 'payment_payload', 'notes',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'paid_at'         => 'datetime',
        'payment_payload' => 'array',
    ];

    // ── Boot ───────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $model) {
            if (empty($model->invoice_number)) {
                $model->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $date   = now()->format('Ymd');
        $last   = self::withTrashed()->whereDate('created_at', today())->count() + 1;
        return 'INV-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
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

    public function license(): HasOne
    {
        return $this->hasOne(License::class);
    }

    public function certificates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ── Helpers ────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(string $method = null, string $channel = null): void
    {
        $this->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_method' => $method ?? $this->payment_method,
            'payment_channel'=> $channel ?? $this->payment_channel,
        ]);
    }
}
