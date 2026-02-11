<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Role;
use App\Models\Backend\Menu;
use Illuminate\Support\Str;


class RoleController extends Controller
{
    public function index()
    {
        return view('backend.role', [
            'roles' => Role::with('menus')->get(),
            'menus' => Menu::all()
        ]);
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        $role->menus()->sync($request->menus ?? []);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $role = Role::with('menus')->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        $role->menus()->sync($request->menus ?? []);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
    public function data()
    {
        $roles = Role::with('menus')->get();

        return response()->json($roles);
    }
}
