<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;

class RestrictFreeTierActions
{
    /**
     * Pembatasan cerdas: User paket gratis tidak bisa melakukan aksi Create/Edit/Delete
     * pada grup Akuntansi, Inventory, Pengadaan, Anggaran, dan Manajemen User.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName() ?? '';

        // Mapping Modul ke Kolom Database 'subscription_plans'
        $moduleMapping = [
            'inventory.'   => 'has_inventory',
            'procurement.' => 'has_procurement',
            'accounting.'  => 'has_accounting_full',
            'budgeting.'   => 'has_budgeting',
            'settings.users.' => 'has_hr', // Manajemen User diasosiasikan dengan HR (atau buat kolom baru jika perlu)
        ];
        
        $activeFeature = null;
        foreach ($moduleMapping as $prefix => $column) {
            if (Str::startsWith($routeName, $prefix)) {
                $activeFeature = $column;
                break;
            }
        }

        // Jika rute BUKAN bagian dari modul yang dibatasi, izinkan.
        if (!$activeFeature) {
            return $next($request);
        }

        // Cek apakah request ini adalah upaya TAMBAH/UBAH/HAPUS atau mengakses halaman form
        $isWriteAction = in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
        $isFormPage = Str::endsWith($routeName, ['.create', '.edit']);

        if (!$isWriteAction && !$isFormPage) {
            // Izinkan metode GET biasa (index, show) untuk navigasi dasar
            return $next($request);
        }

        // ─── PENGECEKAN CERDAS DI DATABASE PUSAT ───────────────────
        $hasAccess = tenancy()->central(function () use ($activeFeature) {
            $tenantId = tenant('id');
            if (!$tenantId) return false;
            
            $tenant = Tenant::find($tenantId);
            if (!$tenant || !$tenant->plan_id) return false;
            
            $plan = SubscriptionPlan::find($tenant->plan_id);
            if (!$plan) return false;

            // Jika admin pusat (Super Admin) sedang login atau trial aktif, beri akses.
            // Namun di sini kita fokus pada 'plan feature'
            return (bool) ($plan->$activeFeature ?? false);
        });

        // Jika Fitur TIDAK AKTIF di paket langganan saat ini
        if (!$hasAccess) {
            $planName = tenancy()->central(fn() => Tenant::find(tenant('id'))?->plan_slug ?? 'Free');
            $message = "Informasi: Modul ini merupakan fitur eksklusif Paket Premium. Dapur Anda saat ini menggunakan Paket " . ucfirst($planName) . ". Silakan tingkatkan paket langganan Anda untuk membuka akses penuh dan fitur profesional lainnya.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $message], 403);
            }

            return redirect()->route('tenant.billing.index', tenant('id'))->with('info', $message);
        }

        return $next($request);
    }
}
