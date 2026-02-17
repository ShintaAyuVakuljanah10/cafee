<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\backend\Aplikasi;
use App\Models\backend\SubMakanan;
use Illuminate\Http\Request;
use App\Models\backend\Makanan; 

class CartController extends Controller
{
    public function add(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->makanan_id;
        $subId = $request->sub_makanan;

        $key = $id . '-' . ($subNama ?? 'no-level');

        $makanan = Makanan::where('id_makanan', $id)->first();

        if(!$makanan){
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        $subNama = null;
        $subHarga = 0;

        if($subId){
            $sub = SubMakanan::where('id_sub_makanan', $subId)->first();

            if($sub){
                $subNama = $sub->nama;
                $subHarga = $sub->tambahan_harga;
            }
        }

        $finalHarga = $makanan->harga + $subHarga;
        $cartKey = $id . '-' . ($subId ?? 0);

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
        } else {
            $cart[$cartKey] = [
                "id" => $id,
                "sub_id" => $subId,
                "nama" => $makanan->nama,    
                "sub" => $subNama,
                "harga" => $finalHarga,
                "gambar" => asset('storage/'.$makanan->gambar),
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
