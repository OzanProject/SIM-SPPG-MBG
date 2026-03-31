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

        // 2. Cari tenant dari central DB (eksplisit 'central' agar tidak terpengaruh state koneksi)
        $tenant = Tenant::on('central')->where('id', $slug)->first();

        if (!$tenant) {
            \Illuminate\Support\Facades\Log::error("TENANT_INIT_FAIL | Tenant not found for slug: {$slug}");
            abort(404, 'Dapur (Tenant) tidak ditemukan.');
        }

        // 3. Inisialisasi Tenancy
        if (!tenancy()->initialized || tenant('id') !== $tenant->id) {
            \Illuminate\Support\Facades\Log::info("TENANT_INIT_SWITCH | Initializing database for: {$tenant->id}");
            tenancy()->initialize($tenant);
        }

        // 4. 🔥 FIX AUTH LOOP: Sinkronisasi Sesi & Auth (Solusi dari Anda)
        // Jika user pindah ke rute tenant, pastikan ID User dipusat tetap terbaca
        if (auth()->check()) {
            session(['user_id' => auth()->id()]);
        } elseif (session()->has('user_id')) {
            // Jika auth hilang karena switch DB, login ulang menggunakan ID dari sesi (Model dipinning ke 'central')
            auth()->loginUsingId(session('user_id'));
        }

        // 5. Set instance & session untuk referensi
        app()->instance('currentTenant', $tenant);
        session(['tenant_id' => $tenant->id]);

        // 6. Hapus parameter 'tenant'
        $request->route()->forgetParameter('tenant');

        return $next($request);
    }
}
