<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Reporting\Controllers\ReportController;

Route::middleware(['web', 'auth'])->prefix('reporting')->name('reporting.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
});
