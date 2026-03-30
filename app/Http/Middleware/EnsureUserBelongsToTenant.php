<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Jika user login dan konteks tenant aktif
        if (auth()->check() && tenant('id')) {
            
            // Jika tenant_id di User tidak sama dengan tenant yang sedang diakses
            if (auth()->user()->tenant_id !== tenant('id')) {
                // Berikan akses jika super-admin
                if (auth()->user()->role === 'super-admin') {
                    return $next($request);
                }

                // Redirect ke tenant dia yang benar
                if (auth()->user()->tenant_id) {
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->to("/" . auth()->user()->tenant_id . "/dashboard");
                }

                // Jika tidak punya tenant_id, logout untuk keamanan
                \Illuminate\Support\Facades\Auth::logout();
                \Illuminate\Support\Facades\Session::save();
                return redirect()->route('login')->with('error', 'Akses ditolak. Tenant tidak valid.');
            }
        }

        return $next($request);
    }
}
