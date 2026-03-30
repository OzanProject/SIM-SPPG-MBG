<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promos = PromoCode::with('subscriptionPlans')->orderBy('created_at', 'desc')->get();
        return view('super-admin.promos.index', compact('promos'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('super-admin.promos.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required|string|max:50|unique:promo_codes,code',
            'type'       => 'required|in:fixed,percent',
            'value'      => 'required|numeric|min:0',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after_or_equal:starts_at',
            'max_uses'   => 'required|integer|min:1',
        ]);

        $promo = PromoCode::create([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'starts_at'  => $request->starts_at,
            'ends_at'    => $request->ends_at,
            'max_uses'   => $request->max_uses,
            'is_active'  => $request->has('is_active') ? true : false,
        ]);

        // Lampirkan ke paket tertentu (kosong = berlaku semua)
        if ($request->filled('subscription_plan_ids')) {
            $promo->subscriptionPlans()->sync($request->subscription_plan_ids);
        }

        return redirect('/super-admin/promos')->with('success', 'Kode promo berhasil diterbitkan.');
    }

    public function edit(PromoCode $promo)
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $selectedPlanIds = $promo->subscriptionPlans->pluck('id')->toArray();
        return view('super-admin.promos.edit', compact('promo', 'plans', 'selectedPlanIds'));
    }

    public function update(Request $request, PromoCode $promo)
    {
        $request->validate([
            'code'       => 'required|string|max:50|unique:promo_codes,code,' . $promo->id,
            'type'       => 'required|in:fixed,percent',
            'value'      => 'required|numeric|min:0',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after_or_equal:starts_at',
            'max_uses'   => 'required|integer|min:1',
        ]);

        $promo->update([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'starts_at'  => $request->starts_at,
            'ends_at'    => $request->ends_at,
            'max_uses'   => $request->max_uses,
            'is_active'  => $request->has('is_active') ? true : false,
        ]);

        $promo->subscriptionPlans()->sync($request->subscription_plan_ids ?? []);

        return redirect('/super-admin/promos')->with('success', 'Kode promo berhasil diperbarui.');
    }

    public function destroy(PromoCode $promo)
    {
        $promo->subscriptionPlans()->detach();
        $promo->delete();
        return redirect('/super-admin/promos')->with('success', 'Kode promo berhasil dihapus.');
    }
}
