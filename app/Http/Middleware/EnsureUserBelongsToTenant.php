<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserBelongsToTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user terautentikasi dan kita sedang dalam konteks tenant
        if (auth()->check() && tenant()) {
            
            // Ambil tenant_id dari sesi
            // [PRO-GUIDELINE] Simple Session Binding Check
            if (auth()->user()->tenant_id !== session('tenant_id')) {
                \Illuminate\Support\Facades\Log::warning("EnsureUserBelongsToTenant: SESSION BINDING FAILED -> LOGOUT", [
                    'user' => auth()->user()->email,
                    'user_tenant' => auth()->user()->tenant_id,
                    'session_tenant' => session('tenant_id')
                ]);
                
                \Illuminate\Support\Facades\Auth::logout();
                $request->session()->invalidate();
                return redirect()->route('login')->with('error', 'Keamanan: Sesi Anda tidak valid untuk dapur ini. Silakan masuk kembali.');
            }
        }

        return $next($request);
    }
}
