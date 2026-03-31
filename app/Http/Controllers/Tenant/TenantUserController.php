<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        // Jangan tampilkan role Super Admin di level Tenant
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('tenant.settings.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $plan = tenant()->plan;
        $maxUsers = $plan->max_users ?? 0;
        if ($maxUsers > 0 && User::count() >= $maxUsers) {
            return redirect()->back()->with('error', "Batas maksimal user tercapai ({$maxUsers} akun) untuk paket " . strtoupper($plan->name ?? 'Free') . ". Silakan upgrade paket langganan Anda.");
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name', 'not_in:Super Admin'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => tenant('id'),
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,name', 'not_in:Super Admin'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->role);

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth('tenant')->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
