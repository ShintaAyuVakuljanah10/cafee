<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SubMenuController;
use App\Http\Controllers\Backend\MejaController;

Route::get('/', function () {
    return view('cek');
});
Route::get('/backend', function () {
    return view('auth.login');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');
Route::get('/dashboard', [HomeController::class, 'index'])
    ->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::prefix('backend')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::post('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('user.hapus');
});
Route::prefix('roles')
    ->group(function () {

        Route::get('/', [RoleController::class, 'index'])->name('roles');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/roles/data', [RoleController::class, 'data'])->name('roles.data');
    });

Route::prefix('backend')->name('backend.')->group(function () {
    
    // Group khusus untuk Menu
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index'); // URL: /backend/menu
        Route::get('/data', [MenuController::class, 'data'])->name('data'); // URL: /backend/menu/data
        Route::get('/route-select', [MenuController::class, 'routeSelect'])->name('routeSelect');
        
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/{id}', [MenuController::class, 'show'])->name('show');
        Route::put('/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('destroy');
        
        // Urutan
        Route::post('/{id}/up', [MenuController::class, 'orderUp'])->name('up');
        Route::post('/{id}/down', [MenuController::class, 'orderDown'])->name('down');
    });

});
Route::prefix('backend')->group(function () {
    Route::get('/submenu', [SubMenuController::class, 'index'])->name('submenu');
    Route::get('submenu/data', [SubMenuController::class, 'data'])->name('submenu.data');
    Route::post('submenu', [SubMenuController::class, 'store'])->name('submenu.store');
    Route::get('submenu/{id}', [SubMenuController::class, 'show']);
    Route::put('submenu/{id}', [SubMenuController::class, 'update']);
    Route::delete('submenu/{id}', [SubMenuController::class, 'destroy']);
});
Route::prefix('backend')->middleware(['auth'])->group(function () {
    // Rute untuk menampilkan daftar meja
    Route::get('/meja', [MejaController::class, 'index'])->name('meja.index');
    
    // Rute untuk memproses tambah meja
    Route::post('/meja/store', [MejaController::class, 'store'])->name('meja.store');
    
    // Rute untuk cetak QR (Ini yang sedang error di tempat Anda)
    Route::get('/meja/print/{id}', [MejaController::class, 'downloadQr'])->name('meja.print');
});
// Rute untuk Pelanggan (ketika QR di-scan)
Route::get('/order/{uuid}', function($uuid) {
    // 1. Cari meja berdasarkan UUID
    $meja = \App\Models\Backend\Meja::where('uuid', $uuid)->firstOrFail();
    
    // 2. Simpan informasi meja ke session (agar sistem ingat pelanggan duduk di mana)
    session([
        'id_meja' => $meja->id,
        'nomor_meja' => $meja->nomor_meja
    ]);

    // 3. Sementara tampilkan teks saja dulu (Nanti ganti ke halaman menu asli)
    return "<h1>Selamat Datang di Kafe!</h1> 
            <p>Anda sedang berada di <b>Meja " . $meja->nomor_meja . "</b></p>
            <p>Halaman menu sedang dalam pengembangan.</p>";
            
})->name('pelanggan.menu'); // NAMA INI WAJIB ADA agar tidak error lagi

