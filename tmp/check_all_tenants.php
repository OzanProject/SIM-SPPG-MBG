<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenants = DB::table('tenants')->get();
foreach ($tenants as $t) {
    echo "ID: " . $t->id . " | PLAN_ID: " . ($t->plan_id ?? 'NULL') . " | PLAN_SLUG: " . ($t->plan_slug ?? 'NULL') . "\n";
}
