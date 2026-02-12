<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SubMenuController;
use App\Http\Controllers\Backend\MejaController;
use App\Http\Controllers\Backend\AplikasiController;
use App\Http\Controllers\Backend\FileManagerController;
use App\Http\Controllers\Backend\CategoryController;

// --- GUEST / PUBLIC ROUTES ---
Route::get('/', function () { return view('cek'); });
Route::get('/backend', function () { return view('auth.login'); });
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- CUSTOMER SCAN QR (Public) ---
Route::get('/order/{uuid}', function($uuid) {
    $meja = \App\Models\Backend\Meja::where('uuid', $uuid)->firstOrFail();
    session(['id_meja' => $meja->id, 'nomor_meja' => $meja->nomor_meja]);
    return "<h1>Selamat Datang!</h1><p>Meja " . $meja->nomor_meja . "</p>";
})->name('pelanggan.menu');

// --- BACKEND ROUTES (Authenticated) ---
Route::middleware(['auth'])->prefix('backend')->name('backend.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // User Management
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::post('/tambah', [UserController::class, 'tambah'])->name('tambah');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('hapus');
    });

    // Roles Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'data'])->name('data');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Menu Management
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/data', [MenuController::class, 'data'])->name('data');
        Route::get('/parent', [MenuController::class, 'getParentData'])->name('parent');
        Route::get('/route-select', [MenuController::class, 'routeSelect'])->name('routeSelect');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/{id}', [MenuController::class, 'show'])->name('show');
        Route::put('/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/up', [MenuController::class, 'orderUp'])->name('up');
        Route::post('/{id}/down', [MenuController::class, 'orderDown'])->name('down');
    });

    // SubMenu Management
    Route::prefix('submenu')->name('submenu.')->group(function () {
        Route::get('/', [SubMenuController::class, 'index'])->name('index');
        Route::get('/data', [SubMenuController::class, 'data'])->name('data');
        Route::post('/', [SubMenuController::class, 'store'])->name('store');
        Route::get('/{id}', [SubMenuController::class, 'show'])->name('show');
        Route::put('/{id}', [SubMenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubMenuController::class, 'destroy'])->name('destroy');
    
    });

    // Meja / Table Management
    Route::prefix('meja')->name('meja.')->group(function () {
        Route::get('/', [MejaController::class, 'index'])->name('index');
        Route::post('/store', [MejaController::class, 'store'])->name('store');
        Route::get('/print/{id}', [MejaController::class, 'downloadQr'])->name('print');
    });

    // Settings / Aplikasi
    Route::prefix('settings')->name('aplikasi.')->group(function () {
        Route::get('/aplikasi', [AplikasiController::class, 'index'])->name('index');
        Route::post('/aplikasi/update', [AplikasiController::class, 'update'])->name('update');
    });

    // File Manager
    Route::prefix('fileManager')->name('fileManager.')->group(function () {
        Route::get('/', [FileManagerController::class, 'index'])->name('index');
        Route::get('/data', [FileManagerController::class, 'data'])->name('data');
        Route::post('/', [FileManagerController::class, 'store'])->name('store');
        Route::delete('/{id}', [FileManagerController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/data', [CategoryController::class, 'data'])->name('data');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});