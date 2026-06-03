<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    /**
     * Daftar semua lisensi milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = License::with(['batikMotif'])
            ->where('user_id', $user->id);

        // Filter berdasarkan status (opsional)
        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->active(),
                'expiring' => $query->expiring(),
                'expired'  => $query->expired(),
                default    => null,
            };
        }

        // Filter berdasarkan kategori motif
        if ($request->filled('category')) {
            $query->whereHas('batikMotif', fn($q) =>
                $q->where('category', $request->category)
            );
        }

        $licenses = $query->orderBy('expired_at', 'asc')->paginate(10);

        // Map ke format yang dibutuhkan view
        $licenses->getCollection()->transform(function (License $lic) {
            $lic->append(['status', 'status_label', 'days_left', 'progress_percent']);
            return $lic;
        });

        return view('pages.users.lisensi.index', compact('licenses'));
    }

    /**
     * Detail satu lisensi.
     */
    public function show(int $id)
    {
        $license = License::with(['batikMotif', 'certificates', 'transaction'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $license->append(['status', 'status_label', 'days_left', 'progress_percent']);

        return view('pages.users.lisensi.show', compact('license'));
    }
}
