<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Counts
        $totalTenants = Tenant::count();
        $totalUsers = User::whereNotNull('tenant_id')->count(); // Total Staff Dapur
        $totalAdmins = User::whereNull('tenant_id')->count(); // Total Super Admins
        $newTenantsThisMonth = Tenant::whereMonth('created_at', now()->month)->count();
        
        // 2. Revenue & Billing
        $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('final_amount');
        $monthlyRevenue = \App\Models\Invoice::where('status', 'paid')
                            ->whereMonth('paid_at', now()->month)
                            ->whereYear('paid_at', now()->year)
                            ->sum('final_amount');
        
        $pendingInvoices = \App\Models\Invoice::where('status', 'pending')->count();
        
        // 3. Support Tickets
        $openTickets = \App\Models\SupportTicket::where('status', 'open')->count();
        
        // 4. Recent Lists
        $recentTenants = Tenant::with('domains')->orderBy('created_at', 'desc')->take(5)->get();
        $recentInvoices = \App\Models\Invoice::with('tenant')->orderBy('created_at', 'desc')->take(5)->get();
        
        // 5. Chart Data (Last 6 Months)
        $monthsArr = [];
        $tenantGrowthArr = [];
        $revenueTrendArr = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthsArr[] = $month->format('M');
            
            $tenantGrowthArr[] = Tenant::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count();
            $revenueTrendArr[] = (float) (\App\Models\Invoice::where('status', 'paid')
                                ->whereMonth('paid_at', $month->month)
                                ->whereYear('paid_at', $month->year)
                                ->sum('final_amount') / 1000000);
        }

        $appVersion = \App\Models\AppConfig::get('app_version', 'v1.0.0-PRO');

        return view('super-admin.dashboard', compact(
            'totalTenants', 
            'totalUsers', 
            'totalAdmins',
            'newTenantsThisMonth',
            'totalRevenue',
            'monthlyRevenue',
            'pendingInvoices',
            'openTickets',
            'recentTenants',
            'recentInvoices',
            'monthsArr',
            'tenantGrowthArr',
            'revenueTrendArr',
            'appVersion'
        ));
    }
}
