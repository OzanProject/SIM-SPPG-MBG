<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with([
            'plan',
            'domains',
        ])->withCount([
            // Hitung berapa invoice pending per tenant
        ])->get()->map(function ($tenant) {
            $tenant->latest_invoice = \App\Models\Invoice::where('tenant_id', $tenant->id)
                ->latest()->first();
            $tenant->pending_invoice = \App\Models\Invoice::where('tenant_id', $tenant->id)
                ->where('status', 'pending')->first();
            $tenant->central_user = \App\Models\User::where('tenant_id', $tenant->id)->first();
            return $tenant;
        });

        return view('super-admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('super-admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|not_in:admin,api,super-admin,webhook|unique:tenants,id|regex:/^[a-zA-Z0-9\-]+$/',
        ], [
            'id.regex' => 'ID Cabang hanya boleh berisi huruf, angka, dan strip (-).'
        ]);

        $tenant = Tenant::create(['id' => $request->id]);
        
        // Auto-generate a dummy domain based on current host for compatibility
        $domainStr = $request->id . '.' . request()->getHost();
        $tenant->domains()->create(['domain' => $domainStr]);

        // Auto-assign Free Tier Plan if exists
        $freePlan = \App\Models\SubscriptionPlan::where('price', 0)->where('is_active', true)->first();
        if ($freePlan) {
            $tenant->plan_id = $freePlan->id;
            $tenant->subscription_ends_at = now()->addDays($freePlan->duration_in_days)->toDateTimeString();
            $tenant->max_members = $freePlan->max_members;
            $tenant->save();
        } else {
            // Default 30 days trial if no explicit free plan
            $tenant->subscription_ends_at = now()->addDays(30)->toDateTimeString();
            $tenant->save();
        }

        // Jalankan seeder pada tenant baru
        Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id]
        ]);

        return redirect('/super-admin/tenants')->with('success', 'Cabang Dapur (Tenant) berhasil dibuat.');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        try {
            // 1. Hapus semua data terkait di CENTRAL DB sebelum tenant dihapus
            // Ini mencegah data "orphan" yang tertinggal setelah tenant database di-drop.

            // 1a. Hapus semua Invoice milik tenant ini
            \App\Models\Invoice::where('tenant_id', $id)->delete();

            // 1b. Hapus semua Support Ticket milik tenant ini
            \App\Models\SupportTicket::where('tenant_id', $id)->delete();

            // 1c. Hapus semua User pusat (Central User) yang terhubung ke tenant ini
            \App\Models\User::where('tenant_id', $id)->delete();

            // 1d. Hapus semua Domain yang terdaftar untuk tenant ini
            $tenant->domains()->delete();

            // 2. Hapus Tenant beserta database-nya (Stancl Tenancy akan drop DB via event DeleteDatabase)
            $tenant->delete();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Tenant delete failed for [{$id}]: " . $e->getMessage());
            return redirect('/super-admin/tenants')
                ->with('error', 'Terjadi kesalahan saat menghapus dapur: ' . $e->getMessage());
        }

        return redirect('/super-admin/tenants')->with('success', 'Cabang Dapur dan seluruh data terkait berhasil dihapus.');
    }
}
