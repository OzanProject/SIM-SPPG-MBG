<?php

namespace App\Modules\Budgeting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Account;

class Budget extends Model
{
    protected $fillable = [
        'account_id', 'year', 'month', 'amount', 'realized_amount', 'notes'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
