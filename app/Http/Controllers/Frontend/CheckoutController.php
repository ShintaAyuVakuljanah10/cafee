<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Aplikasi;
use App\Models\Backend\Transaksi;
use App\Models\Backend\TransaksiDetail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {   
        $request->validate([
            'nama' => 'required'
        ]);

        $cart = session('cart');

        if (!$cart || count($cart) == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        $kode = 'TRX-' . strtoupper(Str::random(8));
        
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kode,
            'nama_customer'  => $request->nama,
            'id_meja'        => session('id_meja'),
            'total'          => $total,
            'status'         => 'pending'
        ]);

        foreach ($cart as $item) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'nama_produk'  => $item['nama'],
                'harga'        => $item['harga'],
                'qty'          => $item['qty'],
                'subtotal'     => $item['harga'] * $item['qty'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('checkout.barcode', $transaksi->id);
    }

    public function barcode($id)
    {
        $app = Aplikasi::first(); 
        $transaksi = Transaksi::with('meja')->findOrFail($id);
        return view('barcode', compact('transaksi','app'));
    }

    public function struk($id)
    {
        $app = Aplikasi::first(); 
        $transaksi = Transaksi::with('details')->findOrFail($id);

        return view('struk', compact('transaksi','app'));
    }
}
