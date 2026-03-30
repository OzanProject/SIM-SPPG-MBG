<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\Models\Journal;
use Illuminate\Support\Facades\DB;
use Exception;

class JournalService
{
    public function getAllJournals()
    {
        return Journal::with(['details.account', 'creator'])->orderBy('date', 'desc')->get();
    }

    public function createJournal(array $data, array $details)
    {
        DB::beginTransaction();
        try {
            // Validate balance
            $totalDebit = collect($details)->sum('debit');
            $totalCredit = collect($details)->sum('credit');

            if ($totalDebit !== $totalCredit) {
                throw new Exception("Jurnal tidak balance (Debit: $totalDebit, Credit: $totalCredit)");
            }

            $data['total_amount'] = $totalDebit;
            
            $journal = Journal::create($data);

            foreach ($details as $detail) {
                $journal->details()->create($detail);
            }

            DB::commit();
            return $journal;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
