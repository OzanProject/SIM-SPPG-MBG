<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Accounting\Controllers\AccountController;

// [DISABLED] - Rute ini dinonaktifkan karena menyebabkan konflik nama rute dengan routes/tenant.php
// Sistem sekarang menggunakan /app/Http/Controllers/Tenant/AccountController.php

/*
Route::middleware(['web', 'auth'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('journals', \App\Modules\Accounting\Controllers\JournalController::class);
});
*/
