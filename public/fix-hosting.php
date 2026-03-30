<?php

/**
 * SIM-SPPG EMERGENCY FIX & DIAGNOSTIC SCRIPT (Hosting Version)
 * Use this script to fix storage links, clear cache, and test DB/Session integrity.
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

echo "<html><head><title>SIM-SPPG Hosting Fixer & Diagnostic</title>";
echo "<style>body{font-family:sans-serif; line-height:1.6; padding:20px; background:#f8fafc;} h1{color:#1e293b;} h3{margin-top:30px; border-bottom:2px solid #e2e8f0; padding-bottom:10px;} .status{padding:5px 10px; border-radius:5px; font-weight:bold;} .ok{background:#dcfce7; color:#166534;} .err{background:#fee2e2; color:#991b1b;} .warn{background:#fef9c3; color:#854d0e;} pre{background:#1e293b; color:#cbd5e1; padding:15px; border-radius:10px; overflow-x:auto;} hr{border:0; border-top:1px solid #e2e8f0; margin:20px 0;}</style>";
echo "</head><body>";
echo "<h1>SIM-SPPG Hosting Fixer & Diagnostic</h1>";

// ─────────────────────────────────────────────────────────────────────────────
// 1. Storage & Symbolic Link
// ─────────────────────────────────────────────────────────────────────────────
echo "<h3>1. Storage & Symlink Check</h3>";
$target = storage_path('app/public');
$shortcut = __DIR__.'/storage';

if (file_exists($shortcut)) {
    if (is_link($shortcut)) {
        echo "<p>Symlink 'public/storage' <span class='status ok'>EXISTS</span></p>";
    } else {
        echo "<p>Symlink 'public/storage' <span class='status err'>IS A DIRECTORY</span>. Deleting...</p>";
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
        echo "<p>Symlink created <span class='status ok'>SUCCESSFULLY</span></p>";
    } catch (\Exception $e) {
        echo "<p>Symlink creation <span class='status err'>FAILED</span>: " . $e->getMessage() . "</p>";
    }
}

// Ensure tenant_dbs directory
$tenantDbsPath = storage_path('tenant_dbs');
if (!file_exists($tenantDbsPath)) {
    mkdir($tenantDbsPath, 0775, true);
    echo "<p>Directory 'storage/tenant_dbs' <span class='status ok'>CREATED</span></p>";
} else {
    echo "<p>Directory 'storage/tenant_dbs' <span class='status ok'>EXISTS</span></p>";
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. Database Connectivity
// ─────────────────────────────────────────────────────────────────────────────
echo "<h3>2. Database Status</h3>";
try {
    $centralDb = \Illuminate\Support\Facades\DB::connection('central')->getDatabaseName();
    echo "<p>Central DB Connection (MySQL): <span class='status ok'>OK</span> ($centralDb)</p>";
} catch (\Exception $e) {
    echo "<p>Central DB Connection (MySQL): <span class='status err'>FAILED</span> - " . $e->getMessage() . "</p>";
}

$tenantFiles = glob(storage_path('tenant_dbs/*.sqlite'));
echo "<p>Tenant Databases found in storage: <strong>" . count($tenantFiles) . "</strong></p>";
foreach ($tenantFiles as $file) {
    echo "<li>" . basename($file) . " (" . round(filesize($file) / 1024, 2) . " KB)</li>";
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. Session & Permissions
// ─────────────────────────────────────────────────────────────────────────────
echo "<h3>3. Session & Permissions</h3>";
$testKey = 'hosting_diag_test_' . time();
session([$testKey => 'working']);
\Illuminate\Support\Facades\Session::save();

if (session($testKey) === 'working') {
    echo "<p>Session Write Test: <span class='status ok'>PASSED</span></p>";
} else {
    echo "<p>Session Write Test: <span class='status err'>FAILED</span> (Check if storage/framework/sessions is writable)</p>";
}

$paths = [
    'Storage Root' => storage_path(),
    'Tenant DBs'   => storage_path('tenant_dbs'),
    'Sessions'     => storage_path('framework/sessions'),
    'Logs'         => storage_path('logs'),
    'Cache'        => base_path('bootstrap/cache'),
];

echo "<ul>";
foreach ($paths as $name => $path) {
    $writable = is_writable($path);
    $status = $writable ? "<span class='status ok'>Writable</span>" : "<span class='status err'>NOT Writable</span>";
    echo "<li>$name: $status <small>($path)</small></li>";
}
echo "</ul>";

// ─────────────────────────────────────────────────────────────────────────────
// 4. Application Configuration
// ─────────────────────────────────────────────────────────────────────────────
echo "<h3>4. App Info</h3>";
echo "<ul>";
echo "<li>APP_URL: " . config('app.url') . "</li>";
echo "<li>SESSION_DRIVER: " . config('session.driver') . "</li>";
echo "<li>SESSION_CONNECTION: " . (config('session.connection') ?: '<span style="color:red;">null (May cause Logout!)</span>') . "</li>";
echo "<li>SESSION_DOMAIN: " . (config('session.domain') ?: '<i>(null)</i>') . "</li>";
echo "<li>DB_CONNECTION: " . config('database.default') . "</li>";
echo "</ul>";

// ─────────────────────────────────────────────────────────────────────────────
// 5. Clear Cache
// ─────────────────────────────────────────────────────────────────────────────
echo "<h3>5. Maintenance Action</h3>";
try {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    echo "<p>System Cache & Optimization: <span class='status ok'>CLEARED</span></p>";
} catch (\Exception $e) {
    echo "<p>Optimization Clear: <span class='status err'>FAILED</span> - " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p style='color:#64748b;'>Diagnostic complete. If session test failed, please fix folder permissions to 775 or 755.</p>";
echo "<p style='color:red; font-weight:bold;'>IMPORTANT: Delete this file (public/fix-hosting.php) after checking!</p>";
echo "</body></html>";

$kernel->terminate($request, $response);
