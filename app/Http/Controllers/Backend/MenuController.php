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

        Menu::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->is_submenu ? null : $request->route,
            'is_submenu' => $request->is_submenu ?? 0,
            'active' => $request->active ?? 1,
            'sort_order' => Menu::max('sort_order') + 1,
        ]);

        return response()->json(['message' => 'Menu berhasil ditambahkan']);
    }

    public function parentMenu()
    {
        return Menu::where('is_submenu', true)
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function show($id)
    {
        return Menu::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'active'=> 'required|boolean',
        ]);

        $menu->update($data);

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

    public function routeSelect(Request $request)
    {
        return Menu::routeSelect($request->q);
    }

}
