<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Certificate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Halaman utama dashboard user.
     * Menyediakan SEMUA data yang dibutuhkan oleh Blade dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // ── 1. Statistik ringkas ───────────────────────────────
        $stats = $user->dashboardStats();

        // ── 2. Lisensi Aktif (maks 5 untuk tampilan dashboard) ─
        $myLicenses = License::with(['batikMotif'])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->orderByRaw("
                CASE
                    WHEN expired_at < CURDATE() THEN 3
                    WHEN expired_at <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('expired_at', 'asc')
            ->take(5)
            ->get()
            ->map(function (License $lic) {
                return [
                    'id'             => $lic->id,
                    'name'           => $lic->batikMotif->name,
                    'cat'            => $lic->batikMotif->category,
                    'img'            => $lic->batikMotif->image,
                    'image_url'      => $lic->batikMotif->image_url,
                    'date'           => $lic->started_at->format('Y-m-d'),
                    'buy_date'       => $lic->started_at->format('d M Y'),
                    'expiry_date'    => $lic->expired_at->format('d M Y'),
                    'days_left'      => $lic->days_left,
                    'progress_pct'   => $lic->progress_percent,
                    'status'         => $lic->status,
                    'status_label'   => $lic->status_label,
                    'license_number' => $lic->license_number,
                ];
            });

        // ── 3. Sertifikat terbaru (3 item) ─────────────────────
        $latestCerts = Certificate::with(['batikMotif'])
            ->where('user_id', $user->id)
            ->latest('issued_at')
            ->take(3)
            ->get()
            ->map(function (Certificate $cert) {
                return [
                    'id'           => $cert->id,
                    'display_name' => $cert->display_name,
                    'type'         => $cert->type,
                    'icon'         => $cert->icon,
                    'issued_at'    => $cert->issued_at->format('d M Y'),
                    'download_url' => $cert->download_url,
                    'has_file'     => $cert->hasFile(),
                ];
            });

        // ── 4. Aktivitas terbaru (5 item) ──────────────────────
        $activities = ActivityLog::where('user_id', $user->id)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function (ActivityLog $log) {
                // Format tanggal seperti di Blade: "13 Apr" atau "5 Apr '25"
                $year    = $log->created_at->year;
                $current = now()->year;
                $date    = $year < $current
                    ? $log->created_at->format('j M \'') . substr($year, 2)
                    : $log->created_at->format('j M');

                return [
                    'type'        => $log->type,
                    'icon'        => $log->icon,
                    'description' => $log->description,
                    'date'        => $date,
                ];
            });

        return view('pages.users.dashboard', compact(
            'user', 'stats', 'myLicenses', 'latestCerts', 'activities'
        ));
    }
}
