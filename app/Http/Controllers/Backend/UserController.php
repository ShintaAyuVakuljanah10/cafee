<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {   
        $roles = \App\Models\Backend\Role::all();
        return view('backend.user', compact('roles'));
    }

    public function data()
    {
        return response()->json(
            User::with('role.menus')->get()
        );
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('users', 'public');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'foto' => $foto,
        ]);
        return response()->json(['success' => true]);
    }
    public function edit($id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role_id;

        if ($request->hasFile('foto')) {

            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->foto = $request->file('foto')->store('users', 'public');
        }

        $user->save();
        return response()->json(['message' => 'User berhasil diperbarui']);
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }    

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }


}