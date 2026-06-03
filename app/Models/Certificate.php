<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'certificate_number', 'user_id', 'batik_motif_id',
        'transaction_id', 'license_id', 'type',
        'file_path', 'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // ── Boot ───────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $model) {
            if (empty($model->certificate_number)) {
                $model->certificate_number = self::generateCertNumber();
            }
            if (empty($model->issued_at)) {
                $model->issued_at = now();
            }
        });
    }

    public static function generateCertNumber(): string
    {
        $date = now()->format('Ymd');
        $last = self::withTrashed()->whereDate('created_at', today())->count() + 1;
        return 'CERT-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
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

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * Label yang muncul di Blade: "Sertifikat Keaslian" atau "Lisensi Komersial"
     */
    public function getDisplayNameAttribute(): string
    {
        $prefix = $this->type === 'Sertifikat' ? 'Sertifikat Keaslian' : 'Lisensi Komersial';
        return $prefix . ' — ' . $this->batikMotif?->name;
    }

    public function getIconAttribute(): string
    {
        return $this->type === 'Sertifikat' ? '📜' : '📄';
    }

    public function hasFile(): bool
    {
        return $this->file_path && \Storage::exists($this->file_path);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('pages.users.sertifikat.download', $this->id);
    }
}
