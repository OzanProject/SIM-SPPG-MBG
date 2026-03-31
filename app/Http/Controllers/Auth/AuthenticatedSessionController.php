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
        
        // Pilih Guard & Model berdasarkan konteks
        $guard = $currentTenant ? 'tenant' : 'web';
        $model = $currentTenant ? \App\Models\Tenant\User::class : \App\Models\Central\User::class;

        // 2. Cari User
        $userQuery = $model::where('email', $request->email);
        
        // Jika login via dapur, pastikan user tsb memang milik tenant ini
        if ($currentTenant) {
            $userQuery->where('tenant_id', $currentTenant->id);
        }

        $user = $userQuery->first();

        // 3. Validasi Kredensial
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // 4. Eksekusi Login dengan Guard yang tepat
        Auth::guard($guard)->login($user, $request->boolean('remember'));
        
        $request->session()->regenerate();

        // 5. Redirect ke Dashboard yang sesuai
        if ($guard === 'tenant') {
            return redirect("/" . $currentTenant->id . "/dashboard");
        }

        return redirect('/super-admin/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $guard = tenant() ? 'tenant' : 'web';
        
        Auth::guard($guard)->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
