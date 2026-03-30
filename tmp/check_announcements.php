<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$anns = DB::table('global_announcements')->get();
foreach ($anns as $a) {
    echo "ID: " . $a->id . " | TARGET: ";
    var_dump($a->target_plan);
    echo " | ACTIVE: " . $a->is_active . "\n";
}
