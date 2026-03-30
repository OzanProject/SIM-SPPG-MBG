<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Testimonial;
use App\Models\Tenant;

$tenant = Tenant::first();

if ($tenant) {
    echo "Initializing Tenancy for: " . $tenant->id . "\n";
    tenancy()->initialize($tenant);
    
    echo "Current Connection: " . DB::connection()->getDatabaseName() . "\n";
    echo "Testimonial Connection: " . (new Testimonial)->getConnectionName() . "\n";
    
    try {
        $count = Testimonial::count();
        echo "SUCCESS: Found $count testimonials from central database.\n";
    } catch (\Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
} else {
    echo "No tenants found.\n";
}
