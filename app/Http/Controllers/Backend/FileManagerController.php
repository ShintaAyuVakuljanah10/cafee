<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index()
    {
        return view('backend.fileManager');
    }

    public function data()
    {
        return response()->json(FileManager::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $path = $request->file('gambar')->store('file-manager', 'public');

        FileManager::create([
            'judul'  => $request->judul,
            'gambar' => $path
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $file = FileManager::findOrFail($id);

        if (Storage::disk('public')->exists($file->gambar)) {
            Storage::disk('public')->delete($file->gambar);
        }

        $file->delete();

        return response()->json(['success' => true]);
    }
}
