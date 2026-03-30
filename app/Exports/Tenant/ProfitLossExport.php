<?php

namespace App\Exports\Tenant;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProfitLossExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $revenueAccounts;
    protected $expenseAccounts;
    protected $startDate;
    protected $endDate;

    public function __construct($revenueAccounts, $expenseAccounts, $startDate, $endDate)
    {
        $this->revenueAccounts = $revenueAccounts;
        $this->expenseAccounts = $expenseAccounts;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('tenant.accounting.reports.excel.profit_loss', [
            'revenueAccounts' => $this->revenueAccounts,
            'expenseAccounts' => $this->expenseAccounts,
            'startDate'       => $this->startDate,
            'endDate'         => $this->endDate,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Laba Rugi';
    }
}
