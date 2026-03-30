<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppConfigController extends Controller
{
    public function index()
    {
        $configs = AppConfig::grouped();
        return view('super-admin.config.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'logo_upload', 'favicon_upload']);

        // Handle upload Logo
        if ($request->hasFile('logo_upload') && $request->file('logo_upload')->isValid()) {
            $logoPath = $request->file('logo_upload')->store('app-assets', 'public');
            $data['logo_url'] = '/storage/' . $logoPath;
        }

        // Handle upload Favicon
        if ($request->hasFile('favicon_upload') && $request->file('favicon_upload')->isValid()) {
            $faviconPath = $request->file('favicon_upload')->store('app-assets', 'public');
            $data['favicon_url'] = '/storage/' . $faviconPath;
        }

        foreach ($data as $key => $value) {
            AppConfig::set($key, $value);
        }

        // Clear all app config cache
        Cache::flush();

        return redirect('/super-admin/config')->with('success', 'Konfigurasi aplikasi berhasil disimpan. Semua tampilan sudah diperbarui secara realtime.');
    }
}
