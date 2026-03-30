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
        $tenantId = (string) tenant('id');
        $userTenantId = auth()->check() ? (string) auth()->user()->tenant_id : null;

        // DEBUG LOGGING UNTUK HOSTING
        if (auth()->check() && $tenantId) {
            \Illuminate\Support\Facades\Log::debug("TENANT_SCOPE | User: {$userTenantId} | URL_Tenant: {$tenantId} | Match: " . ($userTenantId === $tenantId ? 'YES' : 'NO'));
            
            // Perbaiki perbandingan agar lebih aman dengan casting ke string
            if ($userTenantId !== $tenantId) {
                // Berikan akses jika super-admin
                if (auth()->user()->role === 'super-admin') {
                    return $next($request);
                }

                // Redirect ke tenant dia yang benar hanya jika URL saat ini tidak cocok
                $targetDashboard = "/" . $userTenantId . "/dashboard";
                if ($userTenantId && !$request->is($userTenantId . '*')) {
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->to($targetDashboard);
                }

                // Jika sudah di URL yang "mendekati" itu tapi tetap tidak cocok, logout untuk stop loop
                if (!$request->routeIs('login')) {
                    \Illuminate\Support\Facades\Auth::logout();
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->route('login')->with('error', 'Akses ditolak. Konteks tenant tidak valid.');
                }
            }
        }

        return $next($request);
    }
}
