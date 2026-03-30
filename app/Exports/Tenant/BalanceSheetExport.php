<?php

namespace App\Exports\Tenant;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BalanceSheetExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $assetAccounts;
    protected $liabilityAccounts;
    protected $equityAccounts;
    protected $currentEarnings;
    protected $date;

    public function __construct($assetAccounts, $liabilityAccounts, $equityAccounts, $currentEarnings, $date)
    {
        $this->assetAccounts = $assetAccounts;
        $this->liabilityAccounts = $liabilityAccounts;
        $this->equityAccounts = $equityAccounts;
        $this->currentEarnings = $currentEarnings;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('tenant.accounting.reports.excel.balance_sheet', [
            'assetAccounts'     => $this->assetAccounts,
            'liabilityAccounts' => $this->liabilityAccounts,
            'equityAccounts'    => $this->equityAccounts,
            'currentEarnings'   => $this->currentEarnings,
            'date'              => $this->date,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Neraca';
    }
}
