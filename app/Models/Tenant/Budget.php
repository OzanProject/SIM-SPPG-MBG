<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'year',
        'month',
        'amount',
        'realized_amount',
        'notes',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function syncRealization($accountId, $year, $month)
    {
        $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $budget = self::where('account_id', $accountId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($budget) {
            $account = $budget->account;
            $query = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })->where('account_id', $accountId);

            if (str_starts_with($account->code, '5')) {
                $realization = $query->sum('debit') - $query->sum('credit');
            } else {
                $realization = $query->sum('credit') - $query->sum('debit');
            }

            $budget->update(['realized_amount' => max(0, $realization)]);
        }
    }
}
