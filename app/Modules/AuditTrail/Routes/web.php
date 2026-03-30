<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AuditTrail\Controllers\AuditLogController;

Route::middleware(['web', 'auth'])->prefix('audit-trail')->name('audit-trail.')->group(function () {
    Route::resource('logs', AuditLogController::class)->only(['index']);
});
