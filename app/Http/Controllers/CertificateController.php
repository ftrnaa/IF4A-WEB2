<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    /**
     * Daftar semua sertifikat milik user.
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['batikMotif'])
            ->where('user_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type); // 'Sertifikat' | 'Lisensi'
        }

        $certificates = $query->latest('issued_at')->paginate(10);

        return view('pages.users.sertifikat.index', compact('certificates'));
    }

    /**
     * Preview / lihat sertifikat di modal (JSON untuk AJAX).
     */
    public function show(int $id)
    {
        $cert = Certificate::with(['batikMotif', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Jika request AJAX (dari modal JS), kembalikan JSON
        if (request()->expectsJson()) {
            return response()->json([
                'id'             => $cert->id,
                'display_name'   => $cert->display_name,
                'type'           => $cert->type,
                'icon'           => $cert->icon,
                'issued_at'      => $cert->issued_at->format('d M Y'),
                'certificate_number' => $cert->certificate_number,
                'motif_name'     => $cert->batikMotif->name,
                'motif_category' => $cert->batikMotif->category,
                'user_name'      => $cert->user->name,
                'has_file'       => $cert->hasFile(),
                'download_url'   => $cert->download_url,
            ]);
        }

        return view('pages.users.sertifikat.show', compact('cert'));
    }

    /**
     * Download file PDF sertifikat.
     * Route: GET /dashboard/sertifikat/{id}/unduh
     */
    public function download(int $id): StreamedResponse
    {
        $cert = Certificate::where('user_id', Auth::id())
            ->findOrFail($id);

        abort_if(! $cert->hasFile(), 404, 'File sertifikat tidak ditemukan.');

        $filename = 'sertifikat-' . str($cert->certificate_number)->slug() . '.pdf';

        return Storage::download($cert->file_path, $filename);
    }
}
