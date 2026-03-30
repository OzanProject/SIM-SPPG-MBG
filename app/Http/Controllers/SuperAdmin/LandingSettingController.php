<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingSettingController extends Controller
{
    public function index()
    {
        $settings  = LandingSetting::grouped();
        $appConfig = AppConfig::all(); // Untuk panel branding read-only
        return view('super-admin.landing.index', compact('settings', 'appConfig'));
    }

    public function update(Request $request)
    {
        $settings = LandingSetting::all();
        
        foreach ($settings as $setting) {
            if ($setting->type === 'file') {
                if ($request->hasFile($setting->key)) {
                    $file = $request->file($setting->key);
                    if ($file->isValid()) {
                        // Delete old file if exists
                        if ($setting->value && !filter_var($setting->value, FILTER_VALIDATE_URL)) {
                            \Storage::disk('public')->delete(str_replace('/storage/', '', $setting->value));
                        }
                        
                        $path = $file->store('landing-assets', 'public');
                        $setting->update(['value' => '/storage/' . $path]);
                        Cache::forget("landing_{$setting->key}");
                    }
                }
            } else {
                if ($request->has($setting->key)) {
                    $setting->update(['value' => $request->get($setting->key) ?? '']);
                    Cache::forget("landing_{$setting->key}");
                }
            }
        }

        return redirect('/super-admin/landing-settings')->with('success', 'Pengaturan landing page berhasil disimpan.');
    }
}

