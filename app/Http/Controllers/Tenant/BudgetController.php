<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Budget;
use App\Models\Tenant\Account;
use App\Models\Tenant\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));

        // Ambil akun kategori Biaya (5xxx) dan Pendapatan (4xxx) untuk di-budget-kan
        $accounts = Account::whereBetween('code', ['4000', '5999'])->orderBy('code')->get();
        
        $budgets = Budget::where('year', $year)
            ->where('month', $month)
            ->get()
            ->keyBy('account_id');

        return view('tenant.budgeting.index', compact('accounts', 'budgets', 'year', 'month'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'budgets' => 'required|array',
            'budgets.*.account_id' => 'required|exists:accounts,id',
            'budgets.*.amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['budgets'] as $data) {
                if ($data['amount'] > 0) {
                    Budget::updateOrCreate(
                        [
                            'account_id' => $data['account_id'],
                            'year' => $validated['year'],
                            'month' => $validated['month'],
                        ],
                        ['amount' => $data['amount']]
                    );
                } else {
                    Budget::where('account_id', $data['account_id'])
                        ->where('year', $validated['year'])
                        ->where('month', $validated['month'])
                        ->delete();
                }
            }
            
            // Recalculate realization after budget update
            $this->recalculateRealization($validated['year'], $validated['month']);
        });

        return redirect()->back()->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function monitoring(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));

        $budgets = Budget::with('account')
            ->where('year', $year)
            ->where('month', $month)
            ->get();

        return view('tenant.budgeting.monitoring', compact('budgets', 'year', 'month'));
    }

    protected function recalculateRealization($year, $month)
    {
        $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $budgets = Budget::where('year', $year)->where('month', $month)->get();

        foreach ($budgets as $budget) {
            /** @var \App\Models\Tenant\Budget $budget */
            $account = $budget->account;
            
            // Hitung total mutasi dari Journal Details dalam rentang waktu tersebut
            // Untuk Biaya (5xxx), kita lihat saldo Normal (Debit - Credit)
            // Untuk Pendapatan (4xxx), kita lihat (Credit - Debit)
            $query = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })->where('account_id', $budget->account_id);

            if (str_starts_with($account->code, '5')) {
                // Biaya: Debit menambah realisasi
                $realization = $query->sum('debit') - $query->sum('credit');
            } else {
                // Pendapatan: Credit menambah realisasi
                $realization = $query->sum('credit') - $query->sum('debit');
            }

            $budget->realized_amount = max(0, $realization);
            $budget->save();
        }
    }
}
