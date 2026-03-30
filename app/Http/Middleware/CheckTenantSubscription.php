<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenant()) {
            $endsAt = tenant('subscription_ends_at');
            
            // Allow access if active or if accessing the billing page itself to prevent redirect loop
            if ($endsAt && now()->isAfter($endsAt)) {
                if (!$request->routeIs('tenant.billing.*')) {
                    return redirect()->route('tenant.billing.index', tenant('id'))->with('error', 'Masa aktif langganan Anda telah habis. Silakan perbarui paket Anda.');
                }
            }
        }

        return $next($request);
    }
}
