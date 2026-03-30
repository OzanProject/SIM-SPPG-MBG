<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // 1. Ambil User Central (Murni Super Admin / Tanpa Ikatan Cabang)
        $centralUsers = User::whereNull('tenant_id')->orderBy('created_at', 'desc')->get();

        // 2. Ambil User cabang (Karyawan Tenant)
        $tenantUsers = User::whereNotNull('tenant_id')->orderBy('tenant_id', 'asc')->orderBy('created_at', 'desc')->get()->map(function($user) {
            return (object) [
                'tenant_id' => $user->tenant_id,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at_formatted' => $user->created_at ? $user->created_at->format('d M Y') : '-'
            ];
        });

        return view('super-admin.users.index', compact('centralUsers', 'tenantUsers'));
    }

    public function create()
    {
        return redirect()->back()->with('error', 'Fitur tambah admin global belum diimplementasi.');
    }

    public function edit(Request $request, $id)
    {
        if ($request->has('tenant_id')) {
            $tenant = Tenant::findOrFail($request->tenant_id);
            $user = User::where('tenant_id', $tenant->id)->findOrFail($id);
            $isTenant = true;
            return view('super-admin.users.edit', compact('user', 'isTenant', 'tenant'));
        } else {
            $user = User::whereNull('tenant_id')->findOrFail($id);
            $isTenant = false;
            return view('super-admin.users.edit', compact('user', 'isTenant'));
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        if ($request->has('tenant_id')) {
            $tenant = Tenant::findOrFail($request->tenant_id);
            $user = User::where('tenant_id', $tenant->id)->findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return redirect('/super-admin/users')->with('success', 'Data karyawan cabang berhasil diperbarui.');
        } else {
            $user = User::whereNull('tenant_id')->findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return redirect('/super-admin/users')->with('success', 'Data Super Admin berhasil diperbarui.');
        }
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->id() == $id) {
            return redirect('/super-admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri seketika sedang login.');
        }

        if ($request->has('tenant_id')) {
            $tenant = Tenant::findOrFail($request->tenant_id);
            $user = User::where('tenant_id', $tenant->id)->findOrFail($id);
            $user->delete();
            return redirect('/super-admin/users')->with('success', 'Karyawan cabang berhasil dihapus.');
        } else {
            $user = User::whereNull('tenant_id')->findOrFail($id);
            $user->delete();
            return redirect('/super-admin/users')->with('success', 'Akun Super Admin berhasil dihapus.');
        }
    }
}
