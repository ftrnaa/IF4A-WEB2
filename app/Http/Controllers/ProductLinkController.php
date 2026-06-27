<?php

namespace App\Http\Controllers;

use App\Models\ProductLink;
use Illuminate\Http\Request;

class ProductLinkController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'url' => 'required|url',
        'title' => 'nullable|string|max:255',
    ]);

    $order = \App\Models\Order::findOrFail($request->order_id);

    // Pastikan hanya pemilik order yang bisa menambah link
    if ($order->user_id != auth()->id()) {
        abort(403);
    }

    // Maksimal 5 link per user untuk motif ini
    $count = ProductLink::where('user_id', auth()->id())
        ->where('batik_id', $order->batik_id)
        ->count();

    if ($count >= 5) {
        return response()->json([
            'success' => false,
            'message' => 'Maksimal 5 link untuk motif ini.'
        ], 422);
    }

    $link = ProductLink::create([
        'order_id' => $order->id,
        'user_id'  => auth()->id(),
        'batik_id' => $order->batik_id,
        'title'    => $request->title,
        'url'      => $request->url,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Link berhasil disimpan',
        'data' => $link
    ], 201);
}
    public function destroy(ProductLink $productLink)
{
    $order = $productLink->order;

    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $productLink->delete();

    return response()->json([
        'success' => true,
        'message' => 'Link berhasil dihapus'
    ]);
}
}