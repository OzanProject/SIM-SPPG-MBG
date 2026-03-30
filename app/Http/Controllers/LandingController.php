<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Models\Feature;
use App\Models\LandingSetting;
use App\Models\SubscriptionPlan;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page dinamis — 100% data dari database.
     */
    public function index()
    {
        // 1. Landing Settings — grouped by section
        $landingSettings = LandingSetting::all()
            ->groupBy('group')
            ->map(fn ($group) => $group->pluck('value', 'key')->toArray())
            ->toArray();

        // 2. App Configs — flat key-value map
        $appConfigs = AppConfig::pluck('value', 'key')->toArray();

        // 3. Subscription Plans — active, ordered by price asc
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // 4. Active Promo Codes — valid date range & quota remaining
        $promos = \App\Models\PromoCode::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where('used_count', '<', \DB::raw('max_uses'))
            ->get();

        // 5. Tenant logos (Trust Section)
        $tenants = \App\Models\Tenant::limit(8)->get();

        // 6. Testimonials — active, newest first
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // 7. FAQs — active, ordered by category & priority
        $faqs = \App\Models\Faq::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order_priority')
            ->get();

        // 8. Features — active landing features, ordered by priority
        $features = Feature::active()->get();

        // 9. Custom Pages (Footer links)
        $customPages = \App\Models\CustomPage::where('is_active', true)
                            ->where('show_in_footer', true)
                            ->get();

        return view('welcome', compact(
            'landingSettings',
            'appConfigs',
            'plans',
            'promos',
            'tenants',
            'testimonials',
            'faqs',
            'features',
            'customPages'
        ));
    }

    /**
     * Tampilkan halaman statis dinamis (Legalitas, Privacy, FAQ, dll)
     */
    public function page($slug)
    {
        $landingSettings = LandingSetting::all()
            ->groupBy('group')
            ->map(fn ($group) => $group->pluck('value', 'key')->toArray())
            ->toArray();

        $appConfigs = AppConfig::pluck('value', 'key')->toArray();

        $page = \App\Models\CustomPage::where('slug', $slug)
                    ->where('is_active', true)
                    ->firstOrFail();

        $title = $page->title;
        $content = $page->content;

        // Custom Pages for footer linking
        $customPages = \App\Models\CustomPage::where('is_active', true)
                            ->where('show_in_footer', true)
                            ->get();

        return view('frontend.page', compact('title', 'content', 'landingSettings', 'appConfigs', 'customPages'));
    }
}
