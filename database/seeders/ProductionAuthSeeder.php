<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProductionAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ENSURE ROLES EXIST IN CENTRAL
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // 2. PROVISION SUPER ADMIN IN CENTRAL
        echo "Provisioning Super Admin: ardiansyahdzan@gmail.com...\n";
        $superAdmin = User::updateOrCreate(
            ['email' => 'ardiansyahdzan@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'tenant_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        // 3. PROVISION TENANT ADMIN IN CENTRAL (mbg-sukaraja)
        $tenantId = 'mbg-sukaraja';
        
        // Find Free Plan
        $freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')->first();
        
        $tenant = Tenant::updateOrCreate(
            ['id' => $tenantId],
            [
                'plan_id' => $freePlan?->id,
                'plan_slug' => 'free',
                'subscription_ends_at' => now()->addDays(9999),
                'max_users' => $freePlan?->max_users ?? 1,
                'max_members' => $freePlan?->max_users ?? 1,
            ]
        );

        // Ensure Domain exists
        if ($tenant->wasRecentlyCreated || $tenant->domains()->count() === 0) {
            $tenant->domains()->create(['domain' => 'sukaraja.localhost']);
        }
        
        echo "Provisioning Tenant Admin in Central: admin@gmail.com (Tenant: $tenantId)...\n";
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Dapur',
                'password' => Hash::make('password'),
                'tenant_id' => $tenantId,
                'email_verified_at' => now(),
            ]
        );

        // 4. PROVISION IN TENANT DATABASE
        echo "Ensuring Tenant Database exists and is migrated: $tenantId...\n";
        
        // MANUALLY TRIGGER DATABASE CREATION IF IT DOES NOT EXIST
        // This is a safety measure for CLI/Seeder contexts
        $manager = $tenant->database()->manager();
        $dbName  = $tenant->database()->getName();
        if (!$manager->databaseExists($dbName)) {
            echo "Creating database: $dbName...\n";
            $manager->createDatabase($tenant);
        }

        Artisan::call('tenants:migrate', ['--tenants' => [$tenantId], '--force' => true]);

        echo "Provisioning Tenant Admin in Tenant DB: $tenantId...\n";
        tenancy()->initialize($tenant);
        
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Dapur',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Also run the TenantSeeder inside tenant context if needed, 
        // but here we just ensure the basic user exists.
        $this->call(TenantSeeder::class);
        
        tenancy()->end();

        echo "Production Authentication Seeding Completed!\n";
    }
}
