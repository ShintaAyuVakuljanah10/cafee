<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogUserAgent;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Daftarkan alias agar 'log.agent' bisa dikenali
        $middleware->alias([
            'log.agent' => LogUserAgent::class,
        ]);

        // 2. Masukkan ke grup 'web' agar otomatis jalan di semua halaman web
        $middleware->web(append: [
            LogUserAgent::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
