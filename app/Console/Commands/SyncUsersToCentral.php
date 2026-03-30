<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\User;

#[Signature('app:sync-users-to-central')]
#[Description('Synchronize all tenant users to the central database for global login support.')]
class SyncUsersToCentral extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all();
        $this->info("Found {$tenants->count()} tenants. Starting synchronization...");

        // Establish RAW PDO connection to central DB to avoid any hijacking
        $dbConfig = config('database.connections.central');
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
        try {
            $pdo = new \PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->info("Raw PDO connection to central DB established: {$dbConfig['database']}");
        } catch (\Exception $e) {
            $this->error("Failed to establish Raw PDO connection: " . $e->getMessage());
            return Command::FAILURE;
        }

        foreach ($tenants as $tenant) {
            $this->comment("Processing Tenant: {$tenant->id}");
            
            try {
                tenancy()->initialize($tenant);
                $users = User::all();
                $this->line(" - Found {$users->count()} users in tenant: {$tenant->id}");

                foreach ($users as $user) {
                    $this->line("   - Syncing: {$user->email}");
                    
                    // Use Raw PDO prepared statement
                    $stmt = $pdo->prepare("
                        INSERT INTO users (name, email, password, tenant_id, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                            name = VALUES(name), 
                            password = VALUES(password), 
                            tenant_id = VALUES(tenant_id),
                            updated_at = VALUES(updated_at)
                    ");
                    
                    $stmt->execute([
                        $user->name,
                        $user->email,
                        $user->password,
                        $tenant->id,
                        $user->created_at ?? now(),
                        $user->updated_at ?? now()
                    ]);

                    $this->info("     [OK] {$user->email} synced to central via Raw PDO.");
                }
                tenancy()->end();
            } catch (\Exception $e) {
                $errorMsg = "Error in tenant {$tenant->id}: " . $e->getMessage() . "\n";
                $this->error($errorMsg);
                file_put_contents(base_path('tmp/sync_error_final.log'), $errorMsg, FILE_APPEND);
                if (tenancy()->initialized) {
                    tenancy()->end();
                }
            }
        }

        $this->info("Synchronization completed successfully!");
        return Command::SUCCESS;
    }
}
