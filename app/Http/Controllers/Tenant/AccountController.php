<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('code')->get();
        return view('tenant.accounting.accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description' => 'nullable|string',
        ]);

        Account::create($validated);

        return redirect()->route('accounting.accounts.index', tenant('id'))->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code,' . $account->id,
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description' => 'nullable|string',
        ]);

        $account->update($validated);

        return redirect()->route('accounting.accounts.index', tenant('id'))->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        if ($account->journalDetails()->exists()) {
            return redirect()->back()->with('error', 'Akun tidak bisa dihapus karena sudah memiliki transaksi.');
        }
        $account->delete();
        return redirect()->route('accounting.accounts.index', tenant('id'))->with('success', 'Akun berhasil dihapus.');
    }
}
