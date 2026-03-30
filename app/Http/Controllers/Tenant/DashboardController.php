<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\InventoryItem;
use App\Models\Tenant\Journal;
use App\Models\Tenant\Budget;
use App\Models\Tenant\PurchaseOrder;
use App\Models\Tenant\Sale;
use App\Models\SubscriptionPlan;
use App\Models\Tenant as TenantModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan operasional (tenant DB)
        $totalStock       = InventoryItem::sum('stock');
        $todayTransactions = Journal::where('date', '=', Carbon::today()->toDateString())->count();
        $totalExpense     = Journal::where('source_module', 'inventory_out')->sum('total_amount');
        
        // Data Penjualan (New Sales Module)
        $todaySales = Sale::where('date', '=', Carbon::today()->toDateString())->sum('total_amount');
        $monthlySales = Sale::whereMonth('date', Carbon::now()->month)
                            ->whereYear('date', Carbon::now()->year)
                            ->sum('total_amount');

        // Data Payroll (New HR Module)
        $monthlyPayroll = \App\Models\Tenant\Payroll::whereMonth('payment_date', Carbon::now()->month)
                            ->whereYear('payment_date', Carbon::now()->year)
                            ->where('status', 'paid')
                            ->sum('net_salary');

        // Sisa anggaran dari modul budgeting
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;
        $budgets      = Budget::where('month', $currentMonth)->where('year', $currentYear)->get();
        $totalBudget  = $budgets->sum('amount');
        $totalJournalDebit = Journal::whereMonth('date', $currentMonth)->whereYear('date', $currentYear)->sum('total_amount');
        $remainingBudget  = max(0, $totalBudget - $totalJournalDebit);

        // Stok rendah
        $lowStockItems = InventoryItem::where('stock', '<=', 10)->orderBy('stock')->get();

        // Transaksi jurnal terakhir (5)
        $recentJournals = Journal::with('details')->orderBy('date', 'desc')->limit(5)->get();

        // PO pending
        $pendingPOs = PurchaseOrder::where('status', 'pending')->count();

        // Data paket langganan dari Tenant Model (Central DB linked)
        $tenant = tenant();
        $plan = $tenant->plan;
        
        $planName = $plan ? $plan->name : 'FREE';
        $isFreePlan = $plan ? ($plan->price <= 0) : true;
        $maxMembers = $plan ? $plan->max_users : 1;
        $planBadgeClass = $isFreePlan ? 'badge-secondary' : ($tenant->is_on_trial ? 'badge-warning' : 'badge-success');
        
        if ($tenant->is_on_trial) {
            $planName = "Masa Percobaan Pro (7 Hari)";
            $isFreePlan = false; // Treat as Pro for UI stats
        }
        
        // Base plan info for the tooltip or footer
        $basePlanName = $plan ? $plan->name : 'FREE';

        $subscriptionEndsAt = $tenant->trial_ends_at ? Carbon::parse($tenant->trial_ends_at) : null;
        if ($tenant->subscription_ends_at) {
            $subscriptionEndsAt = Carbon::parse($tenant->subscription_ends_at);
        }
        
        $daysRemaining = $tenant->is_on_trial ? $tenant->trial_days_left : null;
        if (!$tenant->is_on_trial && $subscriptionEndsAt) {
            $daysRemaining = (int) Carbon::now()->diffInDays($subscriptionEndsAt, false);
        }

        // --- GLOBAL ANNOUNCEMENTS (Popup) ---
        $planSlug = $tenant->is_on_trial ? 'pro' : ($plan ? $plan->slug : 'free');
        $announcements = tenancy()->central(function () use ($planSlug) {
            $slug = strtolower($planSlug ?? 'free');
            return \App\Models\GlobalAnnouncement::where('is_active', 1)
                ->where(function($q) use ($slug) {
                    $q->whereNull('target_plan')
                      ->orWhereRaw('LOWER(target_plan) = ?', [$slug]);
                })
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->latest()
                ->get()
                ->values();
        });

        return view('tenant.dashboard', compact(
            'totalStock',
            'todayTransactions',
            'totalExpense',
            'remainingBudget',
            'lowStockItems',
            'recentJournals',
            'pendingPOs',
            'planName',
            'planBadgeClass',
            'isFreePlan',
            'maxMembers',
            'subscriptionEndsAt',
            'daysRemaining',
            'todaySales',
            'monthlySales',
            'monthlyPayroll',
            'announcements'
        ));
    }
}
