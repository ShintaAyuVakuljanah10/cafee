<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Transaksi;
use App\Models\backend\Aplikasi;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

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

        return view('backend.detailTransaksi', compact('transaksi_details'));
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
}