<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        if (!tenant()) {
            return $next($request);
        }

        $tenant = tenant();
        $plan = $tenant->plan; // Uses the refactored belongsTo relationship

        if (!$plan) {
            return $next($request);
        }

        if ($feature) {
            $col = "has_{$feature}";
            if (Schema::hasColumn('subscription_plans', $col) && !$plan->$col) {
                return redirect()->route('dashboard', tenant('id'))->with('error', "Fitur ini tidak tersedia di paket " . strtoupper($plan->slug) . ". Silakan upgrade paket Anda.");
            }
        }

        if ($request->isMethod('post')) {
            // 1. Limit Item Inventory
            if ($request->routeIs('inventory.items.store')) {
                $itemCount = \App\Models\Tenant\InventoryItem::count();
                if ($plan->max_items > 0 && $itemCount >= $plan->max_items) {
                    return redirect()->back()->with('error', "Limit item inventory (" . $plan->max_items . ") tercapai.");
                }
            }

            // 1b. Limit User / Staff Settings
            if ($request->routeIs('settings.users.store')) {
                $userCount = \App\Models\User::count();
                if ($plan->max_users > 0 && $userCount >= $plan->max_users) {
                    return redirect()->back()->with('error', "Limit pengguna (" . $plan->max_users . " orang) untuk paket " . strtoupper($plan->name) . " telah tercapai. Hapus user lama atau silakan upgrade paket Anda.");
                }
            }
            
            // 2. Limit Transaksi (Sales & Jurnal)
            if ($request->routeIs('tenant.sales.store') || $request->routeIs('accounting.journals.store')) {
                // Total gabungan transaksi penjualan dan jurnal manual
                $monthSalesCount = \App\Models\Tenant\Sale::whereMonth('created_at', Carbon::now()->month)->count();
                $monthJournalCount = \App\Models\Tenant\Journal::whereMonth('created_at', Carbon::now()->month)
                                    ->whereNull('source_module') // Hanya jurnal manual, agar tidak double count dari Sales
                                    ->count();
                
                $totalTransactions = $monthSalesCount + $monthJournalCount;

                if ($plan->max_transactions_per_month > 0 && $totalTransactions >= $plan->max_transactions_per_month) {
                    return redirect()->back()->with('error', "Limit transaksi bulanan (" . $plan->max_transactions_per_month . ") tercapai. Silakan upgrade paket.");
                }
            }
        }

        return $next($request);
    }
}
