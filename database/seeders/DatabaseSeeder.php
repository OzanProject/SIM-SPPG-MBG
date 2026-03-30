<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run Role and Permission Seeder (System Baseline)
        $this->call(RolePermissionSeeder::class);

        // 2. Run Subscription Plan Seeder (Prerequisite for Tenants)
        $this->call(SubscriptionPlanSeeder::class);

        // 3. Run Production Authentication Seeder (User-requested credentials)
        $this->call(ProductionAuthSeeder::class);

        // 4. Optional: App Configuration and other seeders
        $this->call([
            AppConfigSeeder::class,
            LandingSettingSeeder::class,
            FeatureSeeder::class,
            FaqSeeder::class,
            PromoCodeSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
