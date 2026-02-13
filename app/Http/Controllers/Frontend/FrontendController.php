<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\Aplikasi;
use App\Models\Backend\Category;
use App\Models\Backend\Makanan;
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

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $request->qty;
        } else {
            $cart[$id] = [
                "nama"   => $request->nama,
                "harga"  => $request->harga,
                "gambar" => $request->gambar,
                "qty"    => $request->qty,
                "sub"    => $request->sub_makanan ?? null
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ditambahkan ke keranjang'
        ]);
    }
}