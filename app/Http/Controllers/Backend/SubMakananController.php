<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\SubMakanan;
use App\Models\Backend\Makanan;

class SubMakananController extends Controller
{
    public function index()
    {
        $makanans = Makanan::all(); 
        return view('backend.submakanan', compact('makanans'));
    }

    public function data()
    {
        return SubMakanan::with('makanan')
            ->orderBy('id_sub_makanan', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_makanan' => 'required',
            'nama' => 'required'
        ]);

        SubMakanan::create([
            'id_makanan' => $request->id_makanan,
            'nama' => $request->nama,
            'tambahan_harga' => $request->tambahan_harga ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $sub = SubMakanan::findOrFail($id);

        $sub->update([
            'id_makanan' => $request->id_makanan,
            'nama' => $request->nama,
            'tambahan_harga' => $request->tambahan_harga
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        SubMakanan::destroy($id);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return SubMakanan::findOrFail($id);
    }
}