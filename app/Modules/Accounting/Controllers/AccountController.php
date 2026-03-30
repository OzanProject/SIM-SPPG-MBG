<?php

namespace App\Modules\Accounting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Accounting\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = $this->accountService->getAllAccounts();
        return view('Accounting::accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('Accounting::accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description' => 'nullable|string',
        ]);

        $this->accountService->createAccount($data);

        return redirect()->route('accounting.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }
}
