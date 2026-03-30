<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

$oldId = 'MBG-SUKARAJA';
$newId = 'mbg-sukaraja';

$tenant = Tenant::find($oldId);

if (!$tenant) {
    echo "Tenant $oldId not found.\n";
    exit(1);
}

try {
    DB::beginTransaction();
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // 1. Update ID in central 'tenants' table
    DB::table('tenants')->where('id', $oldId)->update(['id' => $newId]);
    
    // 2. Update 'domains' table
    DB::table('domains')->where('tenant_id', $oldId)->update(['tenant_id' => $newId]);

    // 3. Update 'invoices' table (Central)
    DB::table('invoices')->where('tenant_id', $oldId)->update(['tenant_id' => $newId]);

    DB::commit();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "Successfully updated tenant ID from $oldId to $newId in Central DB.\n";
    echo "NOTE: On Windows/Laragon, the database files are case-insensitive, so the link should remain intact.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
