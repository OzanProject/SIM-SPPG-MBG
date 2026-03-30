<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Budgeting\Controllers\BudgetController;

Route::middleware(['web', 'auth'])->prefix('budgeting')->name('budgeting.')->group(function () {
    Route::resource('budgets', BudgetController::class);
});
