<?php

namespace App\Http\Controllers;

use App\Models\Batik;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
   public function index($id)
{
    $motif = Batik::findOrFail($id);

    $lisensiAktif = Order::where('batik_id', $motif->id)
        ->where('status', 'paid')
        ->where('license_expired_at', '>', now())
        ->latest()
        ->first();

    if ($lisensiAktif) {
        return view(
            'pages.license-unavailable',
            compact('motif', 'lisensiAktif')
        );
    }

    return view('pages.checkout', compact('motif'));
}

   public function store(Request $request)
{
    $request->validate([
        'telepon' => 'required|min:10|max:15',
        'nik' => 'required|digits:16',
    ], [
        'telepon.required' => 'Nomor HP wajib diisi.',
        'nik.required' => 'NIK wajib diisi.',
        'nik.digits' => 'NIK harus terdiri dari 16 digit.',
    ]);

    $motif = Batik::findOrFail($request->batik_id);

    $cek = Order::where('batik_id', $motif->id)
        ->where('status', 'paid')
        ->where('license_expired_at', '>', now())
        ->exists();

    if ($cek) {
        return back()->withErrors([
            'Motif ini sudah dibeli dan tidak tersedia lagi.'
        ]);
    }

    $order = Order::create([
        'user_id' => auth()->id(),

        'batik_id' => $motif->id,

        'kode_order' => 'BTK-' . strtoupper(Str::random(8)),

        'nama' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        'email' => auth()->user()->email,

        'telepon' => $request->telepon,
        'nik' => $request->nik,

        'perusahaan' => $request->perusahaan,
        'npwp' => $request->npwp,
        'bidang_usaha' => $request->bidang,
        'alamat' => $request->alamat,

        'catatan' => $request->catatan,

        'total' => $motif->harga,

        'status' => 'pending',
    ]);

    return redirect()->route('payment', $order->id);
}
};