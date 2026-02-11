<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::all();
        return view('backend.meja', compact('mejas'));
    }

    public function store(Request $request)
    {
        Meja::create(['nomor_meja' => $request->nomor_meja]);
        return back()->with('success', 'Meja berhasil ditambah');
    }

    public function downloadQr($id)
    {
        $meja = Meja::findOrFail($id);
        // QR akan mengarah ke route 'pelanggan.menu' dengan parameter UUID
        $url = route('pelanggan.menu', ['uuid' => $meja->uuid]);
        return view('backend.qr_print', compact('meja', 'url'));
    }
}
