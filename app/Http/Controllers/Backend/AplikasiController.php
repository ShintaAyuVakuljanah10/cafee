<?php

namespace App\Http\Controllers\backend;

use App\Models\backend\Aplikasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AplikasiController extends Controller
{
    public function index()
    {
        $aplikasi = Aplikasi::firstOrCreate([]); // ambil 1 data

    return view('backend.aplikasi', compact('aplikasi'));
    }

    public function update(Request $request)
    {
        $setting = Aplikasi::first();

        if (!$setting) {
            $setting = new Aplikasi();
        }

        $setting->nama_aplikasi = $request->nama_aplikasi;
        $setting->logo = $request->logo;
        $setting->deskripsi = $request->deskripsi;
        $setting->alamat = $request->alamat;
        $setting->telepon = $request->telepon;
        $setting->email = $request->email;
        $setting->weekday = $request->weekday;
        $setting->weekend = $request->weekend;

        // upload gambar
        if ($request->hasFile('logo')) {
            $file = $request->file('logo')->store('setting','public');
            $setting->banner = $file;
        }

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan'
        ]);
    }
}
