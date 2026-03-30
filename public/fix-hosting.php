<?php

/**
 * SIM-SPPG EMERGENCY FIX SCRIPT (Hosting Version)
 * Use this script to fix storage links and clear cache without terminal access.
 * Delete this file after use for security!
 */

define('LARAVEL_START', microtime(true));

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>SIM-SPPG Hosting Fixer</h1>";
echo "<hr>";

// 1. Fix Storage Link
echo "<h3>1. Fixing Storage Link...</h3>";
$target = storage_path('app/public');
$shortcut = __DIR__.'/storage';

if (file_exists($shortcut)) {
    if (is_link($shortcut)) {
        echo "<p style='color:orange;'>✔ Storage link already exists.</p>";
    } else {
        echo "<p style='color:red;'>✘ 'public/storage' exists but is a DIRECTORY. Deleting it to make way for symlink...</p>";
        @rmdir($shortcut);
    }
}

if (!file_exists($shortcut)) {
    try {
        if (PHP_OS_FAMILY === 'Windows') {
            exec("mklink /J \"$shortcut\" \"$target\"");
        } else {
            symlink($target, $shortcut);
        }
        echo "<p style='color:green;'>✔ Storage link created successfully!</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red;'>✘ Failed to create storage link: " . $e->getMessage() . "</p>";
        echo "<p>Try manual fix: Create a folder named 'storage' in public_html and move files manually, or contact hosting support.</p>";
    }
}

// 2. Clear Cache
echo "<h3>2. Clearing All Caches...</h3>";
try {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "<p>✔ Cache cleared.</p>";
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "<p>✔ Config cleared.</p>";
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "<p>✔ View cleared.</p>";
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "<p>✔ Route cleared.</p>";
} catch (\Exception $e) {
    echo "<p style='color:red;'>✘ Error clearing cache: " . $e->getMessage() . "</p>";
}

// 3. Check Permissions
echo "<h3>3. Checking Permissions...</h3>";
$paths = [
    storage_path(),
    storage_path('app/public'),
    storage_path('framework/sessions'),
    storage_path('framework/views'),
    storage_path('logs'),
    base_path('bootstrap/cache'),
];

foreach ($paths as $path) {
    $isWritable = is_writable($path);
    $status = $isWritable ? "<span style='color:green;'>Writable</span>" : "<span style='color:red;'>NOT Writable</span>";
    echo "<p>" . basename($path) . ": $status</p>";
}

echo "<hr>";
echo "<p><strong>Selesai!</strong> Silakan coba login kembali dan upload logo.</p>";
echo "<p style='color:red;'>PENTING: Hapus file 'public/fix-hosting.php' setelah selesai demi keamanan.</p>";

$kernel->terminate($request, $response);
