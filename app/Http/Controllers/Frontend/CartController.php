<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\Aplikasi;
use App\Models\backend\SubMakanan;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;
        $harga = $request->harga;

        $subNama = null;
        $subHarga = 0;

        if($request->sub_makanan) {
            $sub = SubMakanan::find($request->sub_makanan);

            if($sub) {
                $subNama = $sub->nama;
                $subHarga = $sub->tambahan_harga;
            }
        }

        $finalHarga = $harga + $subHarga;

        if(isset($cart[$id])) {
            $cart[$id]['qty'] += $request->qty;
        } else {
            $cart[$id] = [
                "nama" => $request->nama,
                "sub" => $subNama,
                "harga" => $finalHarga,
                "gambar" => $request->gambar,
                "qty" => $request->qty
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function index()
    {
        $app = Aplikasi::first();
        $cart = session()->get('cart', []);
        return view('cart', compact('cart','app'));
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->id]);
        session()->put('cart', $cart);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if(isset($cart[$id])) {

            if($request->action == 'plus') {
                $cart[$id]['qty']++;
            }

            if($request->action == 'minus') {
                $cart[$id]['qty']--;

                if($cart[$id]['qty'] <= 0) {
                    unset($cart[$id]);
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }
}
