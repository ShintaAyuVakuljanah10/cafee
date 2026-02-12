<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackEnd\SubMenu;
use App\Models\BackEnd\Menu;
use Illuminate\Support\Facades\Route;

class SubMenuController extends Controller
{
    public function index()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_contains($route->getName(), 'backend.');
        })->map(function ($route) {
            $name = $route->getName();
            // Menghilangkan 'backend.' dan '.index'
            $cleaned = str_replace(['backend.', '.index'], '', $name);
            return [
                'original' => $name,
                'cleaned'  => $cleaned
            ];
        })->values();

        return view('backend.submenu', compact('routes'));
    }

    public function data()
    {
        $data = SubMenu::with('parent')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'name'        => $item->name,
                    'icon'        => $item->icon ?? '-',
                    'route'       => $item->route,
                    'parent_id'   => $item->parent_id,
                    // Pastikan key ini yang digunakan atau kirim object parent-nya
                    'parent_name' => $item->parent->name ?? '-', 
                    'active'      => $item->active,
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'icon'      => 'nullable|string|max:100',
            'route'     => 'nullable|string|max:255',
            'parent_id' => 'required|exists:menus,id',
            'active'    => 'boolean',
        ]);
        
        if (!empty($data['route'])) {
            $data['route'] = str_replace(['backend.', '.index'], '', $data['route']);
        }

        SubMenu::create($data);

        return response()->json(['message' => 'Sub menu berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(SubMenu::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $submenu = SubMenu::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'icon'      => 'nullable|string|max:100',
            'route'     => 'nullable|string|max:255',
            'parent_id' => 'required|exists:menus,id',
            'active'    => 'boolean',
        ]);

        if (!empty($data['route'])) {
            $data['route'] = str_replace(['backend.', '.index'], '', $data['route']);
        }

        $submenu->update($data);

        return response()->json(['message' => 'Sub menu berhasil diupdate']);
    }

    public function destroy($id)
    {
        SubMenu::findOrFail($id)->delete();

        return response()->json(['message' => 'Sub menu berhasil dihapus']);
    }
}
