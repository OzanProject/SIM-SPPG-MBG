<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $modules = ['UserManagement', 'Accounting', 'Inventory', 'Procurement', 'Budgeting', 'Reporting', 'AuditTrail', 'Tenant'];

        foreach ($modules as $module) {
            $routesPath = app_path('Modules/' . $module . '/Routes/web.php');
            $apiRoutesPath = app_path('Modules/' . $module . '/Routes/api.php');
            $viewsPath = app_path('Modules/' . $module . '/Views');

            if (file_exists($routesPath)) {
                Route::prefix('/{tenant}')->middleware([
                    'web',
                    \Stancl\Tenancy\Middleware\InitializeTenancyByPath::class,
                ])->group($routesPath);
            }
            if (file_exists($apiRoutesPath)) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($apiRoutesPath);
            }
            if (is_dir($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $module);
            }
        }
    }
}
