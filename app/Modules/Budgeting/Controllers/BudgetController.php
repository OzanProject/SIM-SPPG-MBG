<?php

namespace App\Modules\Budgeting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Budgeting\Services\BudgetService;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    protected $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    public function index()
    {
        $budgets = $this->budgetService->getAllBudgets();
        return view('Budgeting::budgets.index', compact('budgets'));
    }
}
