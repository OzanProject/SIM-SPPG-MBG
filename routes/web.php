<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\AppConfigController;
use App\Http\Controllers\SuperAdmin\InvoiceController;
use App\Http\Controllers\SuperAdmin\LandingSettingController;
use App\Http\Controllers\SuperAdmin\SupportTicketController;
use App\Http\Controllers\SuperAdmin\FaqController;
use App\Http\Controllers\SuperAdmin\BackupController;
use App\Http\Controllers\SuperAdmin\FeatureController;
use App\Http\Controllers\SuperAdmin\TestimonialController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/{section?}', [\App\Http\Controllers\LandingController::class, 'index'])
            ->name('welcome')
            ->where('section', 'features|pricing|how-it-works|testimonials|faq');

        Route::get('/page/{slug}', [\App\Http\Controllers\LandingController::class, 'page'])
            ->name('front.page');
        Route::middleware(['auth', 'role:Super Admin'])->group(function () {
            Route::get('/super-admin/dashboard', [DashboardController::class, 'index'])->name('super-admin.dashboard');
            
            Route::get('/super-admin/profile', [SuperAdminProfileController::class, 'edit'])->name('super-admin.profile.edit');
            Route::patch('/super-admin/profile', [SuperAdminProfileController::class, 'update'])->name('super-admin.profile.update');
            Route::put('/super-admin/profile/password', [SuperAdminProfileController::class, 'updatePassword'])->name('super-admin.profile.password');
            Route::delete('/super-admin/profile', [SuperAdminProfileController::class, 'destroy'])->name('super-admin.profile.destroy');
            
            Route::get('/super-admin/profile-breeze', [ProfileController::class, 'edit'])->name('central.profile.edit');
            
            // Route CRUD Cabang Dapur
            Route::resource('/super-admin/tenants', TenantController::class)->except(['show', 'edit', 'update']);

            // Route Global Users (Full Resource)
            Route::resource('/super-admin/users', \App\Http\Controllers\SuperAdmin\UserController::class);

            // Route Paket Langganan
            Route::resource('/super-admin/subscriptions', \App\Http\Controllers\SuperAdmin\SubscriptionPlanController::class);
            // Route Kode Promo
            Route::resource('/super-admin/promos', \App\Http\Controllers\SuperAdmin\PromoCodeController::class);
            // Route Pengumuman Global
            Route::resource('/super-admin/announcements', \App\Http\Controllers\SuperAdmin\AnnouncementController::class);
            // Route Config Aplikasi
            Route::get('/super-admin/config', [AppConfigController::class, 'index'])->name('super-admin.config');
            Route::post('/super-admin/config', [AppConfigController::class, 'update'])->name('super-admin.config.update');
            // Route Billing / Invoice
            Route::resource('/super-admin/billing', InvoiceController::class);
            Route::get('/super-admin/billing/{billing}/download', [InvoiceController::class, 'download'])->name('billing.download');
            Route::post('/super-admin/billing/{billing}/mark-paid', [InvoiceController::class, 'markPaid'])->name('billing.markPaid');
            // Route Landing Page Settings
            Route::get('/super-admin/landing-settings', [LandingSettingController::class, 'index'])->name('landing.settings');
            Route::post('/super-admin/landing-settings', [LandingSettingController::class, 'update'])->name('landing.settings.update');
            Route::resource('/super-admin/testimonials', TestimonialController::class);
            Route::resource('/super-admin/custom-pages', \App\Http\Controllers\SuperAdmin\CustomPageController::class);
            Route::resource('/super-admin/payment-methods', \App\Http\Controllers\SuperAdmin\PaymentMethodController::class)->names([
                'index'   => 'super-admin.payment-methods.index',
                'create'  => 'super-admin.payment-methods.create',
                'store'   => 'super-admin.payment-methods.store',
                'edit'    => 'super-admin.payment-methods.edit',
                'update'  => 'super-admin.payment-methods.update',
                'destroy' => 'super-admin.payment-methods.destroy',
            ]);

            // Route Support Center
            Route::get('/super-admin/support/tickets', [SupportTicketController::class, 'index'])->name('support.tickets.index');
            Route::get('/super-admin/support/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('support.tickets.show');
            Route::post('/super-admin/support/tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.tickets.reply');
            Route::patch('/super-admin/support/tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('support.tickets.status');
            Route::delete('/super-admin/support/tickets/{ticket}', [SupportTicketController::class, 'destroy'])->name('support.tickets.destroy');

            Route::resource('/super-admin/support/faq', FaqController::class)->names([
                'index'   => 'support.faq.index',
                'create'  => 'support.faq.create',
                'store'   => 'support.faq.store',
                'edit'    => 'support.faq.edit',
                'update'  => 'support.faq.update',
                'destroy' => 'support.faq.destroy',
            ]);

            Route::get('/super-admin/monitoring', [FeatureController::class, 'monitoring']);
            Route::get('/super-admin/audit-logs', [\App\Http\Controllers\SuperAdmin\AuditLogController::class, 'index'])->name('super-admin.audit-logs.index');
            Route::get('/super-admin/notifications', [FeatureController::class, 'notifications']);
            
            // Route Database Backup
            Route::get('/super-admin/backups', [BackupController::class, 'index'])->name('backups.index');
            Route::post('/super-admin/backups/create', [BackupController::class, 'create'])->name('backups.create');
            Route::get('/super-admin/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
            Route::delete('/super-admin/backups/delete/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
            Route::post('/super-admin/backups/restore/{filename}', [BackupController::class, 'restore'])->name('backups.restore');
        });


        // Rute autentikasi sentral (Untuk manajemen SaaS)
        require __DIR__.'/auth.php';

        // [PRO-GUIDELINE] Muat Rute Tenant di dalam grup domain sentral agar dapat diakses via localhost
        require __DIR__.'/tenant.php';
    });
}
