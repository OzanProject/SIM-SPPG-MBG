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
            // Periksa apakah user benar-benar berhak di tenant ini
            if ($userTenantId !== $tenantIdFromURL) {

                // Berikan akses jika super-admin
                if (auth()->user()->role === 'super-admin' || auth()->user()->role === 'superadmin') {
                    return $next($request);
                }

                \Illuminate\Support\Facades\Log::error("TENANT_SCOPE | ACCESS_DENIED | User_Tenant: {$userTenantId} | URL_Tenant: {$tenantIdFromURL} | Path: " . $request->path());
                
                // STOP LOOP: Jika tidak berhak, jangan redirect (yang bisa loop), langsung Abort 403.
                abort(403, 'Anda tidak memiliki akses ke Dapur (Tenant) ini. Silakan kembali ke Dashboard Anda yang sah.');
            }
        }

        return $next($request);
    }
}
