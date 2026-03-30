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
        $isLoggedIn = \Illuminate\Support\Facades\Auth::check();
        $userTenantId = $isLoggedIn ? (string) auth()->user()->tenant_id : 'GUEST';

        // LOGGING INFORMASI UNTUK SEMUA REQUEST KE TENANT PATH
        \Illuminate\Support\Facades\Log::info("TENANT_SCOPE | Path: " . $request->path() . " | User: {$userTenantId} | URL_Tenant: {$tenantId}");

        if ($isLoggedIn && $tenantId) {
            // Perbaiki perbandingan agar lebih aman dengan casting ke string
            if ($userTenantId !== $tenantId) {
                // Berikan akses jika super-admin
                if (auth()->user()->role === 'super-admin') {
                    return $next($request);
                }

                // Redirect ke tenant dia yang benar hanya jika URL saat ini tidak cocok
                $targetDashboard = "/" . $userTenantId . "/dashboard";
                if ($userTenantId && $userTenantId !== 'GUEST' && !$request->is($userTenantId . '*')) {
                    \Illuminate\Support\Facades\Log::warning("TENANT_SCOPE | Redirecting to correct tenant dashboard: {$targetDashboard}");
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->to($targetDashboard);
                }

                // Jika sudah di URL yang "mendekati" itu tapi tetap tidak cocok, logout untuk stop loop
                if (!$request->routeIs('login')) {
                    \Illuminate\Support\Facades\Log::error("TENANT_SCOPE | Potential Redirect Loop or Access Denied | User: {$userTenantId} | Path: " . $request->path());
                    \Illuminate\Support\Facades\Auth::logout();
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->route('login')->with('error', 'Akses ditolak. Konteks tenant tidak valid.');
                }
            }
        }

        return $next($request);
    }

}
