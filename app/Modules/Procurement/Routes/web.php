<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Procurement\Controllers\PurchaseOrderController;

Route::middleware(['web', 'auth'])->prefix('procurement')->name('procurement.')->group(function () {
    Route::resource('purchase-orders', PurchaseOrderController::class);
});
