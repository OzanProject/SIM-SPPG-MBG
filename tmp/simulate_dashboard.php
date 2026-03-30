<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Tenant::all() as $t) {
    tenancy()->initialize($t);
    $plan = $t->plan;
    $planSlug = $t->is_on_trial ? 'pro' : ($plan ? $plan->slug : 'free');
    
    echo "Tenant: " . $t->id . " | Plan: " . ($plan ? $plan->name : 'NULL') . " | Slug: " . $planSlug . "\n";
    
    $anns = tenancy()->central(function () use ($planSlug) {
        return \App\Models\GlobalAnnouncement::where('is_active', 1)
            ->where(function($q) use ($planSlug) {
                $q->whereNull('target_plan')
                  ->orWhere('target_plan', $planSlug);
            })
            ->get();
    });
    
    echo "Announcements found: " . $anns->count() . "\n";
    foreach ($anns as $a) {
        echo " - ID: " . $a->id . " | Title: " . $a->title . " | Persistent: " . ($a->is_persistent ? 'YES' : 'NO') . " | Target: " . ($a->target_plan ?? 'NULL') . "\n";
    }
    echo "-------------------\n";
}
