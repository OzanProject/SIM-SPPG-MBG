<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\AppConfig;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix APP_URL trailing slash (Sering jadi penyebab redirect loop)
        $appUrl = config('app.url');
        if (str_ends_with($appUrl, '/')) {
            config(['app.url' => rtrim($appUrl, '/')]);
        }

        // Paksa HTTPS jika APP_URL menggunakan https atau jika sedang diakses via https
        $isHttpsConfigured = str_starts_with(config('app.url'), 'https://');
        if ($isHttpsConfigured || app()->environment('production') || config('app.force_https', false) || request()->secure()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Fix Session secara dinamis jika diakses lewat domain (bukan IP localhost)
        $currentHost = request()->getHost();
        if ($currentHost !== 'localhost' && $currentHost !== '127.0.0.1' && !filter_var($currentHost, FILTER_VALIDATE_IP)) {
            // Pastikan domain session tidak null agar cookie valid di sub-path
            if (!config('session.domain')) {
                config(['session.domain' => $currentHost]);
            }
            
            // PAKSA SECURE COOKIE jika diakses via HTTPS (Sangat krusial untuk Chrome/Modern Browsers)
            if (request()->secure()) {
                config(['session.secure' => true]);
                config(['session.same_site' => 'lax']);
            }
        }


        // Bagikan konfigurasi aplikasi ke semua view secara global
        // Guard: pastikan tabel app_configs sudah ada sebelum query
        if (Schema::hasTable('app_configs')) {
            try {
                $appConfig = AppConfig::all();
                View::share('appConfig', $appConfig);

                // Terapkan timezone dari database ke runtime aplikasi
                $timezone = AppConfig::where('key', 'timezone')->value('value')
                    ?? config('app.timezone', 'Asia/Jakarta');
                if ($timezone && in_array($timezone, \DateTimeZone::listIdentifiers())) {
                    config(['app.timezone' => $timezone]);
                    date_default_timezone_set($timezone);
                }
            } catch (\Exception $e) {
                View::share('appConfig', collect());
            }
        } else {
            View::share('appConfig', collect());
        }

        // View Composer for Auto-Injecting Frontend Configs (Used by Auth views etc)
        View::composer(['frontend.layouts.app'], function ($view) {
            try {
                if (Schema::hasTable('landing_settings')) {
                    $landingSettings = \App\Models\LandingSetting::all()
                        ->groupBy('group')
                        ->map(fn ($group) => $group->pluck('value', 'key')->toArray())
                        ->toArray();
                    $view->with('landingSettings', $landingSettings);
                } else {
                    $view->with('landingSettings', []);
                }

                if (Schema::hasTable('app_configs')) {
                    $appConfigs = AppConfig::pluck('value', 'key')->toArray();
                    $view->with('appConfigs', $appConfigs);
                } else {
                    $view->with('appConfigs', []);
                }

                if (Schema::hasTable('custom_pages')) {
                    $customPages = \App\Models\CustomPage::where('is_active', true)
                                    ->where('show_in_footer', true)
                                    ->get();
                    $view->with('customPages', $customPages);
                } else {
                    $view->with('customPages', collect());
                }
            } catch (\Exception $e) {
                $view->with('landingSettings', []);
                $view->with('appConfigs', []);
                $view->with('customPages', collect());
            }
        });

        // Terapkan Konfigurasi SMTP dari LandingSettings
        if (Schema::hasTable('landing_settings')) {
            try {
                $smtpKeys = [
                    'mail_host' => 'mail.mailers.smtp.host',
                    'mail_port' => 'mail.mailers.smtp.port',
                    'mail_username' => 'mail.mailers.smtp.username',
                    'mail_password' => 'mail.mailers.smtp.password',
                    'mail_encryption' => 'mail.mailers.smtp.encryption',
                    'mail_from_address' => 'mail.from.address',
                    'mail_from_name' => 'mail.from.name'
                ];
                
                $smtpConfigs = \App\Models\LandingSetting::whereIn('key', array_keys($smtpKeys))->get();
                
                foreach ($smtpConfigs as $config) {
                    if ($config->value) {
                        config([$smtpKeys[$config->key] => $config->value]);
                    }
                }
            } catch (\Exception $e) {
                // Fail silently if table not migrated yet
            }
        }

        // Register Observers for Budgeting Realization
        \App\Models\Tenant\JournalDetail::observe(\App\Observers\Tenant\JournalDetailObserver::class);

        // Register Global Audit Log for Central Authentication
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            if (!tenant()) {
                try {
                    \App\Models\AuditLog::create([
                        'user_id' => $event->user->id,
                        'action' => 'login',
                        'model_type' => get_class($event->user),
                        'model_id' => $event->user->id,
                        'changes' => ['email' => $event->user->email],
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                } catch (\Exception $e) {}
            }
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Failed::class, function ($event) {
            if (!tenant()) {
                try {
                    \App\Models\AuditLog::create([
                        'user_id' => $event->user ? $event->user->id : null,
                        'action' => 'login_failed',
                        'changes' => ['email' => $event->credentials['email'] ?? 'unknown'],
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                } catch (\Exception $e) {}
            }
        });
    }
}

