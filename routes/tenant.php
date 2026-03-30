<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/{tenant}')->where(['tenant' => '^(?!login|logout|super-admin|api|up|register|password)[^/]+$'])->middleware([
    'tenant.init',
    'tenant.user_scope',
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // ── Halaman Pembayaran Pending (Akses setelah Registrasi, tidak perlu auth ketat) ──
    Route::prefix('payment')->name('tenant.payment.')->middleware('auth')->group(function () {
        Route::get('/pending', [\App\Http\Controllers\Auth\PaymentPendingController::class, 'show'])
            ->name('pending');
        Route::post('/upload-proof', [\App\Http\Controllers\Auth\PaymentPendingController::class, 'uploadProof'])
            ->name('upload-proof');
    });

    Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        // Rute Billing & Langganan (Bebas Akses meski Kadaluarsa)
        Route::prefix('billing')->name('tenant.billing.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\BillingController::class, 'index'])->name('index');
            Route::post('/checkout', [\App\Http\Controllers\Tenant\BillingController::class, 'checkout'])->name('checkout');
            Route::get('/invoices/{invoice}', [\App\Http\Controllers\Tenant\BillingController::class, 'showInvoice'])->name('invoice.show');
            Route::get('/invoices/{invoice}/download', [\App\Http\Controllers\Tenant\BillingController::class, 'downloadInvoice'])->name('invoice.download');
            Route::post('/invoices/{invoice}/upload', [\App\Http\Controllers\Tenant\BillingController::class, 'uploadProof'])->name('invoice.upload');
        });

        // Rute Inti Aplikasi (Akses Dibatasi)
        Route::middleware(['tenant.subscription', 'free_tier.restrict'])->group(function () {
            Route::get('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [\App\Http\Controllers\Tenant\ProfileController::class, 'updatePassword'])->name('profile.password.update');
            // Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

        // Inventory
        Route::prefix('inventory')->name('inventory.')->middleware('plan.feature:inventory')->group(function () {
            Route::resource('items', \App\Http\Controllers\Tenant\InventoryItemController::class);
            Route::get('movements', [\App\Http\Controllers\Tenant\InventoryMovementController::class, 'index'])->name('movements.index');
            Route::post('movements', [\App\Http\Controllers\Tenant\InventoryMovementController::class, 'store'])->name('movements.store');
        });

        // Procurement
        Route::prefix('procurement')->name('procurement.')->middleware('plan.feature:procurement')->group(function () {
            Route::resource('suppliers', \App\Http\Controllers\Tenant\SupplierController::class)->except(['create', 'edit', 'show']);
            Route::resource('pos', \App\Http\Controllers\Tenant\PurchaseOrderController::class);
            Route::post('pos/{po}/update-status', [\App\Http\Controllers\Tenant\PurchaseOrderController::class, 'updateStatus'])->name('pos.update-status');
        });

        // Accounting
        // Note: Basic accounting is usually shared, but "Accounting Full" might be gated
        Route::prefix('accounting')->name('accounting.')->middleware('plan.feature:accounting_full')->group(function () {
            Route::resource('accounts', \App\Http\Controllers\Tenant\AccountController::class);
            Route::get('journals', [\App\Http\Controllers\Tenant\JournalController::class, 'index'])->name('journals.index');
            Route::post('journals', [\App\Http\Controllers\Tenant\JournalController::class, 'store'])->name('journals.store');
            Route::get('ledger', [\App\Http\Controllers\Tenant\LedgerController::class, 'index'])->name('ledger.index');

            // Reports
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('profit-loss', [\App\Http\Controllers\Tenant\ReportController::class, 'profitLoss'])->name('profit-loss');
                Route::get('balance-sheet', [\App\Http\Controllers\Tenant\ReportController::class, 'balanceSheet'])->name('balance-sheet');
            });
        });

        // Budgeting
        Route::prefix('budgeting')->name('budgeting.')->middleware('plan.feature:budgeting')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\BudgetController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Tenant\BudgetController::class, 'store'])->name('store');
            Route::get('/monitoring', [\App\Http\Controllers\Tenant\BudgetController::class, 'monitoring'])->name('monitoring');
        });

        // Settings (Pengaturan)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::resource('users', \App\Http\Controllers\Tenant\TenantUserController::class);
            Route::get('kitchen', [\App\Http\Controllers\Tenant\TenantSettingController::class, 'index'])->name('kitchen.index');
            Route::post('kitchen', [\App\Http\Controllers\Tenant\TenantSettingController::class, 'update'])->name('kitchen.update');
        });

        // Support Center (Pusat Dukungan)
        Route::prefix('support')->name('tenant.support.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\SupportController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\SupportController::class, 'create'])->name('create');
            Route::post('/store', [\App\Http\Controllers\Tenant\SupportController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\SupportController::class, 'show'])->name('show');
            Route::post('/{id}/reply', [\App\Http\Controllers\Tenant\SupportController::class, 'reply'])->name('reply');
        });

        // Testimonials (Kesan & Pesan)
        Route::prefix('testimonials')->name('tenant.testimonials.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\TestimonialController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Tenant\TestimonialController::class, 'store'])->name('store');
        });

        // Sales (Penjualan / Menu Harian)
        Route::resource('menu', \App\Http\Controllers\Tenant\MenuController::class)->names('tenant.menu');
        Route::get('recipes', [\App\Http\Controllers\Tenant\RecipeController::class, 'indexAll'])->name('tenant.menu.recipe.indexAll');
        Route::get('menu/{menu}/recipe', [\App\Http\Controllers\Tenant\RecipeController::class, 'index'])->name('tenant.menu.recipe.index');
        Route::post('menu/{menu}/recipe', [\App\Http\Controllers\Tenant\RecipeController::class, 'store'])->name('tenant.menu.recipe.store');
        
        Route::resource('sales', \App\Http\Controllers\Tenant\SaleController::class)->names('tenant.sales')
            ->middleware('plan.feature:sales')
            ->except(['edit', 'update', 'destroy']);

        // Circle Menus (MBG)
        Route::resource('circle-menus', \App\Http\Controllers\Tenant\CircleMenuController::class)
            ->names('tenant.circle-menus')
            ->middleware('plan.feature:circle_menu');

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\Tenant\NotificationController::class, 'index'])->name('tenant.notifications.index');
        Route::get('notifications/{id}', [\App\Http\Controllers\Tenant\NotificationController::class, 'show'])->name('tenant.notifications.show');
        Route::post('notifications/mark-all-read', [\App\Http\Controllers\Tenant\NotificationController::class, 'markAllAsRead'])->name('tenant.notifications.markAllRead');

        // HR & Payroll (SDM)
        Route::prefix('hr')->middleware('plan.feature:hr')->group(function () {
            Route::resource('employees', \App\Http\Controllers\Tenant\EmployeeController::class)->names('tenant.hr.employee');
            Route::resource('payrolls', \App\Http\Controllers\Tenant\PayrollController::class)->names('tenant.hr.payroll')->except(['edit', 'update', 'destroy']);
            Route::post('payrolls/{payroll}/pay', [\App\Http\Controllers\Tenant\PayrollController::class, 'processPayment'])->name('tenant.hr.payroll.pay');
        });
        }); // End of tenant.subscription middleware
    });

    // Muat Rute Autentikasi untuk Tenant dengan Name Prefix untuk menghindari duplikasi saat caching
    Route::name('tenant.')->group(function () {
        require base_path('routes/auth.php');
    });
});
