<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;

$tenants = Tenant::all();
foreach ($tenants as $t) {
    echo "ID: " . $t->id . "\n";
    echo "Data: " . json_encode($t->data) . "\n";
    echo "-------------------\n";
}
echo "Database Connection: " . config('database.default') . "\n";
