<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = DB::table('tenants')->where('id', 'mbg-sukaraja')->first();
echo "ID: " . $t->id . "\n";
echo "TRIAL ENDS: " . ($t->trial_ends_at ?? 'NULL') . "\n";
echo "CURRENT TIME: " . now() . "\n";
$isOnTrial = (isset($t->trial_ends_at) && $t->trial_ends_at && \Illuminate\Support\Carbon::parse($t->trial_ends_at)->isFuture());
echo "IS ON TRIAL: " . ($isOnTrial ? 'YES' : 'NO') . "\n";
