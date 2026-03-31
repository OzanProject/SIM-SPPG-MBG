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
        $tenantIdFromURL = strtolower(trim((string) tenant('id')));
        $userStatus = auth()->check() ? 'LOGGED_IN' : 'GUEST';
        $userTenantId = auth()->check() ? strtolower(trim((string) auth()->user()->tenant_id)) : 'N/A';

        // RAW LOG UNTUK DEBUGGING REDIRECT LOOP
        \Illuminate\Support\Facades\Log::info("TENANT_SCOPE_ENTRY | Path: " . $request->path() . " | Status: {$userStatus} | User_Tenant: {$userTenantId} | URL_Tenant: {$tenantIdFromURL}");

        if (auth()->check() && $tenantIdFromURL) {

            // Perbaiki perbandingan dengan normalisasi case
            if ($userTenantId !== $tenantIdFromURL) {

                // Berikan akses jika super-admin
                if (auth()->user()->role === 'super-admin' || auth()->user()->role === 'superadmin') {
                    return $next($request);
                }

                // Redirect ke tenant dia yang benar hanya jika URL saat ini tidak cocok
                $targetDashboard = "/" . $userTenantId . "/dashboard";
                
                // CEK KRITIKAL: Jangan redirect jika kita SUDAH berada di target URL yang sama (mencegah loop tak terhingga)
                if ($userTenantId && $userTenantId !== 'GUEST' && !$request->is($userTenantId . '*')) {
                    \Illuminate\Support\Facades\Log::warning("TENANT_SCOPE | Redirecting to correct tenant dashboard: {$targetDashboard}");
                    \Illuminate\Support\Facades\Session::save();
                    return redirect()->to($targetDashboard);
                }

                // Jika sudah di URL yang "mendekati" itu tapi tetap tidak cocok (mungkin subpath), 
                // tapi kita masih di sini berarti ada akses ditolak
                if (!$request->routeIs('login') && !$request->is($userTenantId . '*')) {
                    \Illuminate\Support\Facades\Log::error("TENANT_SCOPE | Access Denied | User: {$userTenantId} | URL Context: {$tenantIdFromURL} | Path: " . $request->path());
                    
                    // Alih-alih logout (yang bisa loop), arahkan ke central login atau error page yang aman
                    return redirect('/')->with('error', 'Anda tidak memiliki akses ke Dapur (Tenant) ini.');
                }
            }
        }

        return $next($request);
    }

}
