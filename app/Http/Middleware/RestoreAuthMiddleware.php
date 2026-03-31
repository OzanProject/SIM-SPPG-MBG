<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RestoreAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔥 GLOBAL AUTH RESTORE (Solusi Final)
        // Jika user memiliki session user_id tapi Auth::check() gagal (akibat ganti DB atau ambang batas middleware)
        if (session()->has('user_id') && !Auth::check()) {
            Log::info("AUTH_RESTORE_GLOBAL | Restoring user ID: " . session('user_id') . " | Path: " . $request->path());
            
            // Login ulang menggunakan ID dari sesi (Model User sudah dipinned ke 'central')
            Auth::loginUsingId(session('user_id'));
        }

        return $next($request);
    }
}
