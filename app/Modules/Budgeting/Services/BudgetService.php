<?php

namespace App\Modules\Budgeting\Services;

use App\Modules\Budgeting\Models\Budget;

class BudgetService
{
    public function getAllBudgets()
    {
        return Budget::with('account')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
    }

    public function updateRealization($accountId, $year, $month, $amount)
    {
        $budget = Budget::where('account_id', $accountId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($budget) {
            $budget->increment('realized_amount', $amount);
            
            // Check over budget
            if ($budget->realized_amount > $budget->amount) {
                // Return flag or dispatch event notification
                return true; // Indicates over budget
            }
        }
        return false;
    }
}
