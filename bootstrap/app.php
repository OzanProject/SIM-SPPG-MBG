<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant.subscription' => \App\Http\Middleware\CheckTenantSubscription::class,
            'free_tier.restrict'  => \App\Http\Middleware\RestrictFreeTierActions::class,
            'plan.feature'        => \App\Http\Middleware\SubscriptionMiddleware::class,
            'tenant.user_scope'   => \App\Http\Middleware\EnsureUserBelongsToTenant::class,
            'role'                => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'tenant.init'         => \App\Http\Middleware\TenantMiddleware::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SecureHeaders::class,
        ]);

        // Wajib untuk Hosting Shared agar protokol HTTPS terbaca benar
        $middleware->trustProxies(at: '*');

        // Guest users redirect ke /login (untuk central) atau /{tenant}/login (untuk tenant)
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            if ($user = $request->user()) {
                if ($user->tenant_id) {
                    return "/{$user->tenant_id}/dashboard";
                }
                return '/super-admin/dashboard';
            }
            return '/';
        });



        /**
         * MIDDLEWARE PRIORITY - Urutan eksekusi yang benar:
         * 
         * 1. StartSession      - Sesi HARUS aktif sebelum apapun (tenant_id disimpan di sesi)
         * 2. TenantMiddleware  - Identifikasi & inisialisasi database tenant
         * 3. SubstituteBindings - Resolve route model bindings (Account, Item, dll) 
         *                        SETELAH koneksi DB sudah berpindah ke tenant
         * 4. Authenticate      - Cek login user
         */
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\TenantMiddleware::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByPathException $e, $request) {
            return response()->view('errors.tenant-not-found', [
                'tenant_id' => $request->segment(1)
            ], 404);
        });
    })->create();
