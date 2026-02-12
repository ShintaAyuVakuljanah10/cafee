<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        return view('backend.category');
    }

    public function data() {
        $data = Category::orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:categories,name']);
        
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Kategori berhasil ditambahkan']);
    }

    public function edit($id) {
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $request->validate(['name' => 'required|unique:categories,name,' . $id]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Kategori berhasil diperbarui']);
    }

    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}