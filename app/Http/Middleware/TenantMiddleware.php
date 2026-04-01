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
        
        \Illuminate\Support\Facades\Log::info("TENANT_INIT_START | Slug: " . ($slug ?? 'NULL') . " | Path: " . $request->path());

        if (!$slug) {
            return $next($request);
        }

        // 2. Cari tenant dari central DB
        $tenant = Tenant::on('central')->where('id', $slug)->first();

        if (!$tenant) {
            // Bukan tenant yang valid, langsung abort 404 daripada meneruskan rute
            // yang akan berakhir pada fatal error tanpa inisialisasi tenancy.
            abort(404, 'Dapur tidak ditemukan.');
        }

        // 4. Inisialisasi Tenancy
        if (!tenancy()->initialized || tenant('id') !== $tenant->id) {
            \Illuminate\Support\Facades\Log::info("TENANT_INIT_SWITCH | Initializing database for: {$tenant->id}");
            tenancy()->initialize($tenant);
        }

        // 5. Set instance & session untuk referensi
        app()->instance('currentTenant', $tenant);
        session(['tenant_id' => $tenant->id]);

        // 6. Hapus parameter 'tenant'
        $request->route()->forgetParameter('tenant');

        return $next($request);
    }
}
