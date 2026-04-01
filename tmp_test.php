<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $request = \Illuminate\Http\Request::create('/register', 'POST', [
        'name' => 'Tester',
        'email' => 'test' . time() . '@test.com',
        'whatsapp' => '08123456789',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'plan_id' => 1,
        'dapur_name' => 'Dapur Test ' . time(),
    ]);

    $controller = app()->make(\App\Http\Controllers\Auth\RegisteredUserController::class);
    $response = $controller->store($request);
    
    echo "SUCCESS\n";
    print_r($response);
} catch (\Exception $e) {
    echo "EXCEPTION CAUGHT:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . " on line " . $e->getLine() . "\n";
    // echo $e->getTraceAsString();
}
