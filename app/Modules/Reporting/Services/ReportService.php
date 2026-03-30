<?php

namespace App\Modules\Reporting\Services;

use App\Modules\Accounting\Models\Account;
use App\Modules\Accounting\Models\JournalDetail;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getNeracaToko($startDate, $endDate)
    {
        // Simple logic for Neraca (Assets = Liabilities + Equity)
        // In real app, this will calculate ending balances per account
        return [
            'assets' => Account::where('type', 'asset')->get(),
            'liabilities' => Account::where('type', 'liability')->get(),
            'equity' => Account::where('type', 'equity')->get()
        ];
    }

    public function getLabaRugi($startDate, $endDate)
    {
        // Revenue - Expenses
        return [
            'revenues' => Account::where('type', 'revenue')->get(),
            'expenses' => Account::where('type', 'expense')->get()
        ];
    }
}
