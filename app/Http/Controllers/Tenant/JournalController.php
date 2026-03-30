<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::with('details.account')->latest()->get();
        $accounts = \App\Models\Tenant\Account::orderBy('code')->get();
        return view('tenant.accounting.journals.index', compact('journals', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
            'details.*.description' => 'nullable|string',
        ]);

        $totalDebit = collect($request->details)->sum('debit');
        $totalCredit = collect($request->details)->sum('credit');

        if ($totalDebit != $totalCredit || $totalDebit <= 0) {
            return redirect()->back()->with('error', 'Debit dan Kredit harus seimbang dan lebih dari 0.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $totalDebit) {
            $journal = Journal::create([
                'reference_number' => 'MNL-' . time() . rand(10, 99),
                'date' => $validated['date'],
                'description' => $validated['description'],
                'total_amount' => $totalDebit,
                'source_module' => 'manual',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['details'] as $detail) {
                if ($detail['debit'] > 0 || $detail['credit'] > 0) {
                    $journal->details()->create([
                        'account_id' => $detail['account_id'],
                        'debit' => $detail['debit'],
                        'credit' => $detail['credit'],
                        'description' => $detail['description'] ?? $validated['description'],
                    ]);
                }
            }
        });

        return redirect()->route('accounting.journals.index', tenant('id'))->with('success', 'Jurnal manual berhasil disimpan.');
    }
}
