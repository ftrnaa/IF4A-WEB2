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
            'title'    => 'nullable|string|max:255',
            'url'      => 'required|url',
        ]);

        ProductLink::create([
            'order_id' => $request->order_id,
            'title'    => $request->title,
            'url'      => $request->url,
        ]);

        return response()->json([
            'success' => true
        ]);
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