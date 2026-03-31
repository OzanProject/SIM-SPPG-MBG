<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // 1. Identifikasi konteks login (Dapur vs Super Admin)
        $currentTenant = tenant();
        $user = null;

        if ($currentTenant) {
            // LOGIN VIA DAPUR (Koneksi DB sudah terpindah ke Tenant oleh Middleware)
            // Namun user tetap dicari di database PUSAT (karena protected $connection = 'central' di Model User)
            $user = \App\Models\User::where('email', $request->email)
                ->where('tenant_id', $currentTenant->id)
                ->first();
            
            \Illuminate\Support\Facades\Log::info("LOGIN_DEBUG | Context: Tenant | Slug: {$currentTenant->id} | User Found: " . ($user ? 'YES' : 'NO'));
        } else {
            // LOGIN GLOBAL / SUPER ADMIN
            $user = \App\Models\User::where('email', $request->email)->first();
            \Illuminate\Support\Facades\Log::info("LOGIN_DEBUG | Context: Global | User Found: " . ($user ? 'YES' : 'NO'));
        }

        // 2. Validasi Kredensial
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // 3. Eksekusi Login (Guard Web)
        \Illuminate\Support\Facades\Auth::login($user, $request->boolean('remember'));
        
        // Simpan User ID secara eksplisit untuk Handover Sesi di Middleware Tenant
        session([
            'user_id'      => $user->id,
            'is_logged_in' => true,
        ]);
        
        $request->session()->regenerate();

        // 4. Set Session Tenant untuk Session Binding Middleware
        if ($user->tenant_id) {
            session(['tenant_id' => $user->tenant_id]);
            \Illuminate\Support\Facades\Session::save(); // Force save session before redirect
            
            // Gunakan slug aktif (sppg-cek) jika sedang dalam konteks tenant, agar URL tidak berubah
            $redirectSlug = $currentTenant ? $currentTenant->id : $user->tenant_id;
            
            \Illuminate\Support\Facades\Log::info("LOGIN_DEBUG | Redirecting to Tenant: {$redirectSlug} | User ID: {$user->id}");
            \Illuminate\Support\Facades\Session::save();
            return redirect("/{$redirectSlug}/dashboard");
        }

        // 5. Redirect Super Admin (Eksplisit)
        \Illuminate\Support\Facades\Session::save();
        \Illuminate\Support\Facades\Log::info("LOGIN_DEBUG | Redirecting to Super Admin | User ID: {$user->id}");
        return redirect('/super-admin/dashboard');


    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
