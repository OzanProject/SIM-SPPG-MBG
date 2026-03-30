<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price', 'asc')->get();
        $tenants = \App\Models\Tenant::all();

        $plans->transform(function($plan) use ($tenants) {
            $plan->tenants_count = $tenants->where('plan_id', $plan->id)->count();
            return $plan;
        });
        
        $stats = [
            'total_plans' => $plans->count(),
            'active_plans' => $plans->where('is_active', true)->count(),
            'total_subscribers' => $plans->sum('tenants_count'),
            'total_revenue_potential' => $plans->map(function($plan) {
                return $plan->tenants_count * $plan->price;
            })->sum()
        ];

        return view('super-admin.subscriptions.index', compact('plans', 'stats'));
    }

    public function create()
    {
        return view('super-admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:0',
            'max_transactions_per_month' => 'required|integer|min:0',
            'max_items' => 'required|integer|min:0',
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'duration_in_days' => $request->duration_in_days,
            'max_users' => $request->max_users,
            'max_transactions_per_month' => $request->max_transactions_per_month,
            'max_items' => $request->max_items,
            'has_sales' => $request->has('has_sales'),
            'has_inventory' => $request->has('has_inventory'),
            'has_accounting_full' => $request->has('has_accounting_full'),
            'has_budgeting' => $request->has('has_budgeting'),
            'has_procurement' => $request->has('has_procurement'),
            'has_hr' => $request->has('has_hr'),
            'has_notifications' => $request->has('has_notifications'),
            'has_circle_menu' => $request->has('has_circle_menu'),
            'can_export' => $request->has('can_export'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect('/super-admin/subscriptions')->with('success', 'Sistem berhasil menerbitkan paket langganan baru.');
    }

    public function edit(SubscriptionPlan $subscription)
    {
        return view('super-admin.subscriptions.edit', ['plan' => $subscription]);
    }

    public function update(Request $request, SubscriptionPlan $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'max_users' => 'required|integer|min:0',
            'max_transactions_per_month' => 'required|integer|min:0',
            'max_items' => 'required|integer|min:0',
        ]);

        $subscription->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'duration_in_days' => $request->duration_in_days,
            'max_users' => $request->max_users,
            'max_transactions_per_month' => $request->max_transactions_per_month,
            'max_items' => $request->max_items,
            'has_sales' => $request->has('has_sales'),
            'has_inventory' => $request->has('has_inventory'),
            'has_accounting_full' => $request->has('has_accounting_full'),
            'has_budgeting' => $request->has('has_budgeting'),
            'has_procurement' => $request->has('has_procurement'),
            'has_hr' => $request->has('has_hr'),
            'has_notifications' => $request->has('has_notifications'),
            'has_circle_menu' => $request->has('has_circle_menu'),
            'can_export' => $request->has('can_export'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect('/super-admin/subscriptions')->with('success', 'Rincian paket langganan berhasil diperbarui.');
    }

    public function destroy(SubscriptionPlan $subscription)
    {
        $hasTenants = \App\Models\Tenant::all()->where('plan_id', $subscription->id)->count() > 0;
        
        if ($hasTenants) {
            return redirect('/super-admin/subscriptions')->with('error', 'Gagal: Paket ini masih digunakan oleh Dapur aktif.');
        }

        $subscription->delete();
        return redirect('/super-admin/subscriptions')->with('success', 'Paket langganan telah dicabut dari sistem.');
    }
}
