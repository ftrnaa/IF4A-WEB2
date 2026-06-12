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

        return view('pages.checkout', compact('motif'));
    }

    public function store(Request $request)
    {
        $motif = Batik::findOrFail(
            $request->batik_id
        );

        $order = Order::create([

            'user_id' => auth()->id(),

            'batik_id' => $motif->id,

            'kode_order' =>
                'BTK-' . strtoupper(Str::random(8)),

            'nama' => $request->nama,
            'email' => $request->email,
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

        return redirect()->route(
            'payment',
            $order->id
        );
    }
}