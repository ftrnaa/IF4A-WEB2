<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Batik;
use App\Models\ProductLink;
use Carbon\Carbon;

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
        'payment_channel',

        'license_expired_at',
        'is_renewal',
        'renew_from_id',
        'renewed_at'

    ];

    protected $casts = [
        'license_expired_at' => 'datetime',
        'is_renewal'          => 'boolean',
        'total'               => 'decimal:2',
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


    // ── Scopes ─────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Filter berdasarkan status lisensi terhitung (active | expiring | expired).
     * Dihitung dari created_at + 1 tahun, sama seperti LicenseController::index.
     */
    public function scopeLicenseStatus($query, string $status)
{
    return match ($status) {
        'active' => $query->where('status', 'paid')
            ->where('license_expired_at', '>', now()->addDays(30)),

        'expiring' => $query->where('status', 'paid')
            ->whereBetween('license_expired_at', [now(), now()->addDays(30)]),

        'expired' => $query->where('status', 'paid')
            ->where('license_expired_at', '<', now()),

        default => $query,
    };
}
    // ── Computed Helpers (selaras dengan LicenseController & License model) ──

    /**
     * Tanggal berakhir lisensi = tanggal beli + 1 tahun.
     * Catatan: kolom license_expired_at tersedia di DB tapi perhitungan
     * existing (LicenseController::index) memakai created_at + 1 tahun,
     * sehingga accessor ini ikut pola tersebut demi konsistensi tampilan.
     */
    

    /**
     * Sisa hari dari hari ini ke tanggal kedaluwarsa lisensi.
     * Negatif berarti sudah lewat.
     */
public function getDaysLeftAttribute(): int
{
    return $this->license_expired_at
        ? now()->diffInDays($this->license_expired_at, false)
        : 0;
}

    /**
     * Status lisensi: active | expiring | expired | none
     * 'none' dipakai jika order belum/tidak paid.
     */
    
    /**
     * Label status lisensi dalam Bahasa Indonesia, untuk dipakai langsung di view/JSON.
     */
    public function getLicenseStatusAttribute(): string
{
    if ($this->status !== 'paid' || !$this->license_expired_at) {
        return 'none';
    }

    $daysLeft = now()->diffInDays($this->license_expired_at, false);

    if ($daysLeft < 0) {
        return 'expired';
    }

    if ($daysLeft <= 30) {
        return 'expiring';
    }

    return 'active';
}



    public function certificate()
{
    return $this->hasOne(Certificate::class, 'order_id');
}
public function scopeActiveLicense($query)
{
    return $query->where('status', 'paid')
        ->whereNotNull('license_expired_at')
        ->where('license_expired_at', '>', now());
}

}