<?php

namespace App\Modules\Accounting\Services;

use App\Modules\Accounting\Models\Account;

class AccountService
{
    public function getAllAccounts()
    {
        return Account::orderBy('code')->get();
    }

    public function createAccount(array $data)
    {
        return Account::create($data);
    }
}
