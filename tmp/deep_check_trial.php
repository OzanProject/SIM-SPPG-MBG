<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = App\Models\Tenant::find('mbg-sukaraja');
echo "ID: " . $t->id . "\n";
echo "TRIAL ENDS AT: " . ($t->trial_ends_at ?? 'NULL') . "\n";
echo "NOW: " . now() . "\n";
echo "IS ON TRIAL: " . ($t->is_on_trial ? 'YES' : 'NO') . "\n";
$plan = $t->plan;
echo "PLAN NAME: " . ($plan ? $plan->name : 'NULL') . "\n";
echo "PLAN SLUG: " . ($plan ? $plan->slug : 'NULL') . "\n";

$planSlug = $t->is_on_trial ? 'pro' : ($plan ? $plan->slug : 'free');
echo "FINAL RESOLVED SLUG: " . $planSlug . "\n";

$anns = tenancy()->central(function () use ($planSlug) {
    $slug = strtolower($planSlug);
    return \App\Models\GlobalAnnouncement::where('is_active', 1)
        ->where(function($q) use ($slug) {
            $q->whereNull('target_plan')
              ->orWhereRaw('LOWER(target_plan) = ?', [$slug]);
        })
        ->get();
});

echo "MATCHING ANNOUNCEMENTS found: " . $anns->count() . "\n";
foreach($anns as $a) {
    echo " - ID: " . $a->id . " | TITLE: " . $a->title . " | TARGET: [" . ($a->target_plan ?? 'NULL') . "]\n";
}
