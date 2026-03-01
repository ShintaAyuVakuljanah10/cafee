<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role_id' => 'required',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('foto')) {
            $user->foto = $request->file('foto')->store('users', 'public');
        }
    
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role_id = $request->role_id;
    
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        return response()->json(['success' => true]);
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
    public function profile()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();
        return view('backend.profileUpdate', compact('user'));
    }
    public function profileUpdate(Request $request)
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user(); 

        // 2. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // 3. Update Data
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Handle Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
                unlink(public_path('storage/' . $user->foto));
            }
            $user->foto = $request->file('foto')->store('users', 'public');
        }

        $user->save();

        // 5. Kembalikan Response JSON (Wajib untuk AJAX)
        return response()->json([
            'success' => true, 
            'message' => 'Profil berhasil diperbarui!'
        ]);
    }


}