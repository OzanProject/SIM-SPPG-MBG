<?php

namespace App\Observers\Tenant;

use App\Models\Tenant\JournalDetail;
use App\Models\Tenant\Budget;
use Carbon\Carbon;

class JournalDetailObserver
{
    public function saved(JournalDetail $detail)
    {
        $this->sync($detail);
    }

    public function deleted(JournalDetail $detail)
    {
        $this->sync($detail);
    }

    protected function sync(JournalDetail $detail)
    {
        if ($detail->journal) {
            $date = Carbon::parse($detail->journal->date);
            Budget::syncRealization($detail->account_id, $date->year, $date->month);
        }
    }
}
