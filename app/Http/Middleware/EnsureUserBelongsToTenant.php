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
        // 1. Jika user login, pastikan dia hanya bisa akses dapornya sendiri
        if (auth()->check() && tenant()) {
            
            // Jika tenant_id di User tidak sama dengan tenant yang diakses
            if (auth()->user()->tenant_id !== tenant('id')) {
                // Jangan logout, cukup arahkan ke dapor dia yang benar
                if (auth()->user()->tenant_id) {
                    return redirect()->to("/" . auth()->user()->tenant_id . "/dashboard");
                }
                
                // Jika dia super admin, biarkan lewat atau arahkan ke super-admin
                if (auth()->user()->role === 'super-admin') {
                    return redirect()->to('/super-admin/dashboard');
                }

                \Illuminate\Support\Facades\Auth::logout();
                return redirect()->route('login')->with('error', 'Akses ditolak.');
            }
        }

        return $next($request);
    }
}
