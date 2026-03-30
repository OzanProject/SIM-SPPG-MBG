<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$conn = DB::connection('central');

echo "Repairing Central Database Schema (Adding Permissions)...\n";

try {
    $conn->statement("SET FOREIGN_KEY_CHECKS = 0;");
    
    // Tables to create
    $tables = [
        'permissions' => "
            CREATE TABLE permissions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                guard_name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE (name, guard_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
        'roles' => "
            CREATE TABLE roles (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                guard_name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE (name, guard_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
        'model_has_permissions' => "
            CREATE TABLE model_has_permissions (
                permission_id BIGINT UNSIGNED NOT NULL,
                model_type VARCHAR(255) NOT NULL,
                model_id BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY (permission_id, model_id, model_type),
                INDEX (model_id, model_type),
                FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
        'model_has_roles' => "
            CREATE TABLE model_has_roles (
                role_id BIGINT UNSIGNED NOT NULL,
                model_type VARCHAR(255) NOT NULL,
                model_id BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY (role_id, model_id, model_type),
                INDEX (model_id, model_type),
                FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
        'role_has_permissions' => "
            CREATE TABLE role_has_permissions (
                permission_id BIGINT UNSIGNED NOT NULL,
                role_id BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY (permission_id, role_id),
                FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
                FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        "
    ];

    foreach ($tables as $name => $sql) {
        $conn->statement("DROP TABLE IF EXISTS `$name`;");
        $conn->statement($sql);
        echo " - Table '$name' created.\n";
    }

    $conn->statement("SET FOREIGN_KEY_CHECKS = 1;");

    // Seed Super Admin Role
    echo "Seeding Super Admin Role...\n";
    $conn->table('roles')->insert([
        'name' => 'Super Admin',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    $roleId = $conn->getPdo()->lastInsertId();

    // Re-create Super Admin User
    $email = 'ardiansyahdzan@gmail.com';
    echo "Restoring Super Admin User: $email...\n";
    $conn->table('users')->insertOrIgnore([
        'name' => 'Super Admin',
        'email' => $email,
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    $user = $conn->table('users')->where('email', $email)->first();
    if ($user) {
        $conn->table('model_has_roles')->insertOrIgnore([
            'role_id' => $roleId,
            'model_type' => 'App\Models\User',
            'model_id' => $user->id
        ]);
        echo "SUCCESS: Super Admin '$email' restored and assigned role.\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
