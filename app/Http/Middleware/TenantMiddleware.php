<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * ARSITEKTUR:
     * 1. Cek apakah ada {tenant} slug di URL.
     * 2. Identifikasi tenant dari central DB menggunakan koneksi 'central'.
     * 3. Inisialisasi tenancy untuk berpindah koneksi database.
     * 4. Set session dan app instance untuk konteks tenant.
     * 5. SubstituteBindings di-handle oleh middleware priority di bootstrap/app.php.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil slug tenant dari route parameter /{tenant}
        $slug = $request->route('tenant');

        if (!$slug) {
            return $next($request);
        }

        // 2. Cari tenant dari central DB (eksplisit 'central' agar tidak terpengaruh state koneksi)
        $tenant = Tenant::on('central')->where('id', $slug)->first();

        if (!$tenant) {
            abort(404, 'Dapur (Tenant) tidak ditemukan.');
        }

        // 3. Inisialisasi Tenancy agar koneksi DB berpindah ke database tenant
        if (!tenant() || tenant('id') !== $tenant->id) {
            tenancy()->initialize($tenant);
        }

        // 4. Set instance & session untuk referensi di controller & middleware lain
        app()->instance('currentTenant', $tenant);
        session(['tenant_id' => $tenant->id]);

        // 5. Hapus parameter 'tenant' dari route agar controller tidak perlu tangani manual
        $request->route()->forgetParameter('tenant');

        return $next($request);
    }
}
