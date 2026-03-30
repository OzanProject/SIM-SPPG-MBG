<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$a = DB::table('global_announcements')->find(9);
echo "ID 9 target_plan: ";
var_dump($a->target_plan);

$t = DB::table('tenants')->where('id', 'mbg-sukaraja')->first();
$plan = DB::table('subscription_plans')->find($t->plan_id);
echo "Tenant plan slug: ";
var_dump($plan->slug);

$planSlug = $plan->slug;
$anns = DB::table('global_announcements')
    ->where('is_active', 1)
    ->where(function($q) use ($planSlug) {
        $q->whereNull('target_plan')
          ->orWhere('target_plan', $planSlug);
    })
    ->get();

echo "Count: " . $anns->count() . "\n";
foreach($anns as $ann) {
    echo "Match ID: " . $ann->id . " | Title: " . $ann->title . "\n";
}
