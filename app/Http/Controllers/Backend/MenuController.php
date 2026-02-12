<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackEnd\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function index()
    {
        return view('backend.menu');
    }

    public function data()
    {
        return Menu::orderBy('sort_order')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'route' => 'nullable|string',
            'is_submenu' => 'boolean',
            'active' => 'boolean',
        ]);

        // Proses pembersihan route: hilangkan backend. dan .index
        $cleanRoute = str_replace(['backend.', '.index'], '', $request->route);

        Menu::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->is_submenu ? null : $cleanRoute, // Simpan route yang sudah bersih
            'is_submenu' => $request->is_submenu ?? 0,
            'active' => $request->active ?? 1,
            'sort_order' => Menu::max('sort_order') + 1,
        ]);

        return response()->json(['message' => 'Menu berhasil ditambahkan']);
    }

    public function getParentData() 
    {
        return response()->json(Menu::all());
    }

    public function show($id)
    {
        return Menu::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'active'=> 'required|boolean',
        ]);

        // Proses pembersihan route: hilangkan backend. dan .index
        $cleanRoute = str_replace(['backend.', '.index'], '', $request->route);

        $menu->update([
            'name'   => $request->name,
            'icon'   => $request->icon,
            'route'  => $cleanRoute, // Update dengan route yang sudah bersih
            'active' => $request->active,
        ]);

        return response()->json(['message' => 'Menu berhasil diupdate']);
    }

    public function orderUp($id)
    {
        DB::transaction(function () use ($id) {
            $menu = Menu::findOrFail($id);

            $above = Menu::where('sort_order', '<', $menu->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();

            if (!$above) {
                return;
            }

            $currentOrder = $menu->sort_order;
            $menu->sort_order = $above->sort_order;
            $above->sort_order = $currentOrder;

            $menu->save();
            $above->save();
        });

        return response()->json(['success' => true]);
    }

    public function orderDown($id)
    {
        DB::transaction(function () use ($id) {
            $menu = Menu::findOrFail($id);

            $below = Menu::where('sort_order', '>', $menu->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();

            if (!$below) {
                return;
            }

            $currentOrder = $menu->sort_order;
            $menu->sort_order = $below->sort_order;
            $below->sort_order = $currentOrder;

            $menu->save();
            $below->save();
        });

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();

        return response()->json(['message' => 'Menu berhasil dihapus']);
    }

    public function routeSelect(Request $request) {
        $searchTerm = $request->q;

        $routes = collect(Route::getRoutes())->filter(function ($route) use ($searchTerm) {
            $name = $route->getName();
            return str_contains($name, 'backend.') && 
                (empty($searchTerm) || str_contains(strtolower($name), strtolower($searchTerm)));
        })->map(function ($route) {
            $fullName = $route->getName();
            
            // Menghapus 'backend.' dan '.index'
            $cleanName = str_replace(['backend.', '.index'], '', $fullName);
            
            // Mengubah tanda titik menjadi spasi dan huruf kapital di awal (opsional agar lebih rapi)
            $cleanName = ucwords(str_replace('.', ' ', $cleanName));

            return [
                'id' => $fullName, // ID tetap menggunakan nama asli untuk keperluan sistem
                'text' => $cleanName // Tampilan yang bersih untuk user
            ];
        })->values();

        return response()->json($routes);
    }

}
