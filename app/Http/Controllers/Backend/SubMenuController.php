<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackEnd\SubMenu;
use App\Models\BackEnd\Menu;

class SubMenuController extends Controller
{
    public function index()
    {
        return view('backend.submenu');
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

        $submenu->update($data);

        return response()->json(['message' => 'Sub menu berhasil diupdate']);
    }

    public function destroy($id)
    {
        SubMenu::findOrFail($id)->delete();

        return response()->json(['message' => 'Sub menu berhasil dihapus']);
    }
}
