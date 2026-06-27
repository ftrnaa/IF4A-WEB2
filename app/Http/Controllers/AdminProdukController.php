<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Batik;

class AdminProdukController extends Controller
{
    public function index(): View
    {
        $products = Batik::latest()->paginate(24);

        $products->getCollection()->transform(function ($item) {
            return [
                'id'             => $item->id,
                'nama'           => $item->nama,
                'kategori'       => $item->kategori,
                'warna'          => $item->warna,
                'deskripsi'      => $item->deskripsi,
                'preview'        => $item->preview_image,
                'costume_images' => $item->costume_images,
                'created_at'     => $item->api_created_at,
            ];
        });

        $allCategories = Batik::select('kategori')
            ->distinct()
            ->pluck('kategori');

        $categories = $allCategories->values()->map(function ($cat, $i) {
            return [
                'id'    => $i + 1,
                'name'  => $cat,
                'desc'  => "Koleksi motif {$cat} hasil AI Batik.",
                'count' => Batik::where('kategori', $cat)->count(),
            ];
        })->toArray();

        return view('pages.admin.produk', compact('products', 'categories'));
    }

    public function destroy($id)
    {
        $batik = Batik::findOrFail($id);
        $batik->delete();

        return response()->json(['success' => true]);
    }
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'kategori' => 'required',
        'warna' => 'nullable',
        'deskripsi' => 'nullable',
        'preview_image' => 'nullable',
    ]);

    Batik::create([
        'nama' => $request->nama,
        'kategori' => $request->kategori,
        'warna' => $request->warna,
        'deskripsi' => $request->deskripsi,
        'preview_image' => $request->preview_image,
        'costume_images' => [],
    ]);

    return response()->json([
        'success'=>true,
        'message'=>'Produk berhasil ditambahkan'
    ]);
}
public function update(Request $request,$id)
{
    $batik = Batik::findOrFail($id);

    $request->validate([
        'nama'=>'required',
        'kategori'=>'required',
    ]);

    $batik->update([
        'nama'=>$request->nama,
        'kategori'=>$request->kategori,
        'warna'=>$request->warna,
        'deskripsi'=>$request->deskripsi,
        'preview_image'=>$request->preview_image,
    ]);

    return response()->json([
        'success'=>true,
        'message'=>'Produk berhasil diubah'
    ]);
}
}