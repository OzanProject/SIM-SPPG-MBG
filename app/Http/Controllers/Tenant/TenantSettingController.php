<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantSettingController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        return view('tenant.settings.kitchen.index', compact('tenant'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tenant = tenant();
        $tenant->name = $request->name;
        $tenant->address = $request->address;
        $tenant->phone = $request->phone;

        if ($request->hasFile('logo')) {
            // Gunakan disk 'central' untuk bypass isolasi filesystem tenant
            $path = $request->file('logo')->store('logos', 'central');
            $tenant->logo_url = $path;
        }

        $tenant->save();

        return redirect()->back()->with('success', 'Pengaturan dapur berhasil diperbarui.');
    }
}
