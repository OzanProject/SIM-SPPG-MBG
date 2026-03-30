<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Account;
use App\Models\Tenant\JournalDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::orderBy('code')->get();
        $selectedAccountId = $request->get('account_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $ledgerData = [];
        $openingBalance = 0;

        if ($selectedAccountId) {
            $account = Account::findOrFail($selectedAccountId);

            // Calculate opening balance before start date
            $openingDebit = JournalDetail::where('account_id', $selectedAccountId)
                ->whereHas('journal', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })
                ->sum('debit');

            $openingCredit = JournalDetail::where('account_id', $selectedAccountId)
                ->whereHas('journal', function($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })
                ->sum('credit');

            // Opening balance depends on account type (Asset/Expense: D-C, Liability/Equity/Revenue: C-D)
            // For general purposes, we show absolute running balance or just D-C.
            // Let's use D-C (Debit - Credit) as standard increasing for Assets.
            $openingBalance = $openingDebit - $openingCredit;

            $ledgerData = JournalDetail::with('journal')
                ->where('account_id', $selectedAccountId)
                ->whereHas('journal', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                })
                ->get()
                ->sortBy(function($detail) {
                    return $detail->journal->date . $detail->journal->id;
                });
        }

        return view('tenant.accounting.ledger.index', compact(
            'accounts', 
            'ledgerData', 
            'openingBalance', 
            'selectedAccountId', 
            'startDate', 
            'endDate'
        ));
    }
}
