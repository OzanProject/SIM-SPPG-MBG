<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Account;
use App\Models\Tenant\JournalDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\Tenant\ProfitLossExport;
use App\Exports\Tenant\BalanceSheetExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $revenueAccounts = Account::where('type', 'revenue')
            ->with(['journalDetails' => function($q) use ($startDate, $endDate) {
                $q->whereHas('journal', function($jq) use ($startDate, $endDate) {
                    $jq->whereBetween('date', [$startDate, $endDate]);
                });
            }])->get();

        $expenseAccounts = Account::where('type', 'expense')
            ->with(['journalDetails' => function($q) use ($startDate, $endDate) {
                $q->whereHas('journal', function($jq) use ($startDate, $endDate) {
                    $jq->whereBetween('date', [$startDate, $endDate]);
                });
            }])->get();

        // Handle Exports
        if ($request->get('export') === 'excel') {
            return Excel::download(new ProfitLossExport($revenueAccounts, $expenseAccounts, $startDate, $endDate), 'Laporan_Laba_Rugi_'.$startDate.'_to_'.$endDate.'.xlsx');
        }

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('tenant.accounting.reports.pdf.profit_loss', compact(
                'revenueAccounts', 'expenseAccounts', 'startDate', 'endDate'
            ));
            return $pdf->stream('Laporan_Laba_Rugi_'.$startDate.'_to_'.$endDate.'.pdf');
        }

        return view('tenant.accounting.reports.profit_loss', compact(
            'revenueAccounts',
            'expenseAccounts',
            'startDate',
            'endDate'
        ));
    }

    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());

        // Assets
        $assetAccounts = Account::where('type', 'asset')
            ->with(['journalDetails' => function($q) use ($date) {
                $q->whereHas('journal', function($jq) use ($date) {
                    $jq->where('date', '<=', $date);
                });
            }])->get();

        // Liabilities
        $liabilityAccounts = Account::where('type', 'liability')
            ->with(['journalDetails' => function($q) use ($date) {
                $q->whereHas('journal', function($jq) use ($date) {
                    $jq->where('date', '<=', $date);
                });
            }])->get();

        // Equity
        $equityAccounts = Account::where('type', 'equity')
            ->with(['journalDetails' => function($q) use ($date) {
                $q->whereHas('journal', function($jq) use ($date) {
                    $jq->where('date', '<=', $date);
                });
            }])->get();

        // Calculate YTD Profit for Balance Sheet (From beginning of time until $date)
        $ytdRevenue = JournalDetail::whereHas('account', function($q) { $q->where('type', 'revenue'); })
            ->whereHas('journal', function($q) use ($date) { $q->where('date', '<=', $date); })
            ->selectRaw('SUM(credit) - SUM(debit) as total')->value('total') ?? 0;

        $ytdExpense = JournalDetail::whereHas('account', function($q) { $q->where('type', 'expense'); })
            ->whereHas('journal', function($q) use ($date) { $q->where('date', '<=', $date); })
            ->selectRaw('SUM(debit) - SUM(credit) as total')->value('total') ?? 0;

        $currentEarnings = $ytdRevenue - $ytdExpense;

        // Handle Exports
        if ($request->get('export') === 'excel') {
            return Excel::download(new BalanceSheetExport($assetAccounts, $liabilityAccounts, $equityAccounts, $currentEarnings, $date), 'Laporan_Neraca_'.$date.'.xlsx');
        }

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('tenant.accounting.reports.pdf.balance_sheet', compact(
                'assetAccounts', 'liabilityAccounts', 'equityAccounts', 'currentEarnings', 'date'
            ));
            return $pdf->stream('Laporan_Neraca_'.$date.'.pdf');
        }

        return view('tenant.accounting.reports.balance_sheet', compact(
            'assetAccounts',
            'liabilityAccounts',
            'equityAccounts',
            'currentEarnings',
            'date'
        ));
    }
}
