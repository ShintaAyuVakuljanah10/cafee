<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Backend\UserLog;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class LogUserAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agent = new Agent();

        // Tambahkan logika menangkap action di sini
        $method = $request->method();
        $path = $request->path();
        $actionText = $method . ' ' . $path;

        \App\Models\Backend\UserLog::create([
            'user_id'    => Auth::id(),
            'action'     => $actionText, // SEKARANG SUDAH ADA ACTIONNYA
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
        ]);

        return $next($request);
    }
}
