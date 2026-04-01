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
        $currentTenant = tenant();
        
        // 1. Lewati jika bukan konteks tenant (folder dapur)
        if (!$currentTenant) {
            return $next($request);
        }

        // 2. Jika SUDAH login di Guard Tenant (SQLite), biarkan lewat
        if (auth('tenant')->check()) {
            return $next($request);
        }

        // 3. Jika BELUM login di Tenant, tapi login di Guard Web (Pusat MySQL)
        if (auth('web')->check()) {
            $userCentral = auth('web')->user();
            
            // Cek apakah user pusat ini memang pemilik atau admin tenant ini
            if ($userCentral->tenant_id === $currentTenant->id || in_array($userCentral->role, ['super-admin', 'superadmin'])) {
                
                // BRIDGE: Login otomatis ke Guard Tenant (Database SQLite Dapur)
                $userTenant = \App\Models\Tenant\User::where('email', $userCentral->email)->first();
                
                if (!$userTenant && $userCentral->tenant_id === $currentTenant->id) {
                    // AUTO PROVISIONING: Jika tidak ada di Tenant DB (mungkin karena error sebelumnya), 
                    // buatin otomatis agar nggak error 'akun salah'
                    $userTenant = \App\Models\Tenant\User::create([
                        'name'     => $userCentral->name,
                        'email'    => $userCentral->email,
                        'password' => $userCentral->password, // Gunakan hash password yang sama
                        'whatsapp' => $userCentral->whatsapp,
                        'role'     => 'admin',
                    ]);
                    \Illuminate\Support\Facades\Log::info("AUTH_BRIDGE | Auto-Provisioned Tenant User: " . $userCentral->email);
                }

                if ($userTenant) {
                    \Illuminate\Support\Facades\Log::info("AUTH_BRIDGE | Auto-login to Tenant: " . $currentTenant->id . " | User: " . $userTenant->email);
                    auth('tenant')->login($userTenant);
                    return $next($request);
                } else {
                    \Illuminate\Support\Facades\Log::error("AUTH_BRIDGE | Auto-provisioning failed for: " . $userCentral->email);
                }
            }
        }

        // 4. Jika semua gagal, biarkan middleware 'auth:tenant' di route menangani (redirect ke login dapur)
        return $next($request);
    }
}
