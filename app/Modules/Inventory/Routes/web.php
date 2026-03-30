<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Inventory\Controllers\InventoryController;

Route::middleware(['web', 'auth'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::resource('items', InventoryController::class);
});
