<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Makanan;
use App\Models\Backend\Category;
use App\Models\Backend\FileManager;

class MakananController extends Controller
{
    public function index()
    {
        $makanans = Makanan::with('category')->get();
        $categories = Category::all();
        $files = FileManager::all(); 

        return view('backend.makanan', compact('makanans','categories','files'));
    }

    public function data()
    {
        return Makanan::with('category')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'id_category' => 'required',
            'harga' => 'required|numeric'
        ]);

        Makanan::create($request->all());

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $makanan = Makanan::findOrFail($id);

        $makanan->update($request->all());

        return back()->with('success', 'Makanan berhasil diupdate');
    }

    public function destroy($id)
    {
        Makanan::destroy($id);
        return back()->with('success', 'Makanan berhasil dihapus');
    }
}
