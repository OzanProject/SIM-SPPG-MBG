<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$a = \App\Models\GlobalAnnouncement::where('title', 'like', '%Pembayaran%')->first();
if ($a) {
    $a->target_plan = 'free';
    $a->save();
    echo "ID: " . $a->id . " TARGET set to: free\n";
} else {
    echo "Announcement 'Pembayaran' NOT found!\n";
}
