<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Backend\UserLog;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class LogAktivitasUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Biarkan request diproses dulu
        $response = $next($request);

        $agent = new Agent();
        
        // Menangkap Method (GET/POST) dan URL (user/data)
        // $action = $request->method() . ' ' . $request->path();
        // VARIABEL INI YANG MENGISI KOLOM ACTION
        $method = $request->method(); // Mengambil GET, POST, dll
        $path = $request->path();     // Mengambil user/data, dll
        $actionText = $method . ' ' . $path;

        // Simpan ke database
        UserLog::create([
            'user_id'    => Auth::id(), // ID user yang login
            'action'     => $actionText,    // Contoh: "GET user/data"
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'browser'    => $agent->browser(),  // Contoh: "Chrome"
            'platform'   => $agent->platform(), // Contoh: "Windows"
        ]);

        return $response;
    }
}
