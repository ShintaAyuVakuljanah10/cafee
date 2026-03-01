<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Backend\Transaksi;
use App\Models\backend\Aplikasi;
use App\Models\backend\Menu;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Models\Backend\TransaksiDetail;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::latest()->get();

        return view('backend.transaksi', compact('transaksis'));
    }

    public function data()
    {
        $transaksi = Transaksi::latest()->get();

        $transaksi->map(function ($item) {
            $item->barcode = DNS1D::getBarcodeHTML(
                $item->kode_transaksi,
                'C128',
                1.2,
                40
            );
            return $item;
        });

        return response()->json($transaksi);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);

        return view('backend.detailTransaksi', compact('transaksi'));
    }

    public function lunas($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status' => 'lunas']);

        return redirect()->back()->with('success', 'Transaksi berhasil dilunasi');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return response()->json(['success' => true]);
    }

    public function cetak($id)
    {
        $app = Aplikasi::first();
        $transaksi = Transaksi::findOrFail($id);

        return view('backend.cetakTransaksi', compact('transaksi','app'));
    }

    public function bayar($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status' => 'lunas'
        ]);

        return response()->json(['success' => true]);
    }
    public function kasir()
    {
        $menus = \App\Models\Backend\Makanan::with('subMakanans')->get();
        $kode = 'TRX-' . strtoupper(\Illuminate\Support\Str::random(8));

        return view('backend.kasir', compact('menus','kode'));
    }
    public function scanBarcode($kode)
    {
        // Cari transaksi berdasarkan kode yang diinput/discan
        $transaksi = Transaksi::where('kode_transaksi', $kode)->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Kode Transaksi/Barcode tidak ditemukan');
        }

        // Jika ketemu, langsung arahkan ke halaman pembayaran menggunakan kode tersebut
        return redirect()->route('backend.transaksi.pembayaran', ['kode' => $transaksi->kode_transaksi]);
    }
    public function checkout(Request $request)
    {
        $cart = $request->cart;

        if(empty($cart)){
            return response()->json(['error'=>'Cart kosong']);
        }

        $kode = 'TRX-'.Str::upper(Str::random(8));
        $total = 0;

        $transaksi = Transaksi::create([
            'kode_transaksi'=>$kode,
            'total'=>0,
            'status'=>'pending'
        ]);

        foreach ($cart as $item) {

            $subtotal = $item['qty'] * $item['harga'];
            $total += $subtotal;

            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'nama_produk' => $item['nama'],
                'harga' => $item['harga'],
                'qty' => $item['qty'],
                'subtotal' => $subtotal
            ]);
        }

        $transaksi->update([
            'total'=>$total
        ]);

        return response()->json([
            'kode'=>$kode
        ]);
    }
    
    public function pembayaran($kode)
    {
        $app = Aplikasi::first();
        $transaksi = Transaksi::with('details')
            ->where('kode_transaksi',$kode)
            ->first();

        if(!$transaksi){
            return redirect()->route('backend.transaksi.kasir')
                ->with('error','Transaksi tidak ditemukan');
        }

        return view('backend.pembayaran',compact('transaksi','app'));
    }
}