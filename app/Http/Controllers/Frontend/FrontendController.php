<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\Aplikasi;
use App\Models\Backend\Category;
use App\Models\Backend\Makanan;
use App\Models\Backend\SubMakanan;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function index()
    {
        $app = Aplikasi::first();

        $categories = Category::with([
            'makanans' => function ($query) {
                $query->where('status', 'aktif')
                      ->with('subMakanans');
            }
        ])->get();

        return view('frontend', compact('categories', 'app'));
    }

    public function detail($id)
    {
        $app = Aplikasi::first();
        $makanan = Makanan::with('subMakanans')->findOrFail($id);

        return view('detail', compact('makanan','app'));
    }

    public function addToCart(Request $request)
{
    $cart = session()->get('cart', []);

    $id = $request->makanan_id;
    $subNama = null;

    if ($request->sub_makanan) {
        $sub = SubMakanan::where('id_sub_makanan', $request->sub_makanan)->first();
        $subNama = $sub ? $sub->nama : null;
    }

    $key = $id . '-' . ($request->sub_makanan ?? '0');

    if (isset($cart[$key])) {
        $cart[$key]['qty'] += 1;
    } else {
        $cart[$key] = [
            "nama"   => $request->nama,
            "harga"  => $request->harga,
            "gambar" => $request->gambar,
            "qty"    => 1,
            "sub"    => $subNama
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'total_items' => collect($cart)->sum('qty')
    ]);
}
}