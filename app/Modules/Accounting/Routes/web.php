<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Accounting\Controllers\AccountController;

// Define accounting routes
Route::middleware(['web', 'auth'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('journals', \App\Modules\Accounting\Controllers\JournalController::class);
});
