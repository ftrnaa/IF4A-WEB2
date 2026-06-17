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
        $today = Carbon::today();

        return match ($status) {
            'active' => $query->where('status', 'paid')
                ->whereDate(
                    $query->getConnection()->raw("DATE_ADD(created_at, INTERVAL 1 YEAR)"),
                    '>',
                    $today->copy()->addDays(30)
                ),
            'expiring' => $query->where('status', 'paid')
                ->whereDate(
                    $query->getConnection()->raw("DATE_ADD(created_at, INTERVAL 1 YEAR)"),
                    '>=',
                    $today
                )
                ->whereDate(
                    $query->getConnection()->raw("DATE_ADD(created_at, INTERVAL 1 YEAR)"),
                    '<=',
                    $today->copy()->addDays(30)
                ),
            'expired' => $query->where('status', 'paid')
                ->whereDate(
                    $query->getConnection()->raw("DATE_ADD(created_at, INTERVAL 1 YEAR)"),
                    '<',
                    $today
                ),
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
    public function getLicenseExpiryAttribute(): Carbon
    {
        return Carbon::parse($this->created_at)->addYear();
    }

    /**
     * Sisa hari dari hari ini ke tanggal kedaluwarsa lisensi.
     * Negatif berarti sudah lewat.
     */
    public function getDaysLeftAttribute(): int
    {
        return (int) Carbon::today()->diffInDays($this->license_expiry, false);
    }

    /**
     * Status lisensi: active | expiring | expired | none
     * 'none' dipakai jika order belum/tidak paid.
     */
    public function getLicenseStatusAttribute(): string
    {
        if ($this->status !== 'paid') {
            return 'none';
        }

        $daysLeft = $this->days_left;

        if ($daysLeft < 0) {
            return 'expired';
        }

        if ($daysLeft <= 30) {
            return 'expiring';
        }

        return 'active';
    }

    /**
     * Label status lisensi dalam Bahasa Indonesia, untuk dipakai langsung di view/JSON.
     */
    public function getLicenseStatusLabelAttribute(): string
    {
        return match ($this->license_status) {
            'active'   => 'Aktif',
            'expiring' => 'Hampir Habis',
            'expired'  => 'Kedaluwarsa',
            default    => '-',
        };
    }

    public function certificate()
{
    return $this->hasOne(Certificate::class, 'order_id');
}
}