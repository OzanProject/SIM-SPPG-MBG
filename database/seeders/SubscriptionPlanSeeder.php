<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'slug' => 'free',
                'name' => 'FREE',
                'description' => 'Demo & Uji Coba Sistem (Lead Gen)',
                'price' => 0,
                'duration_in_days' => 9999,
                'max_users' => 1,
                'max_transactions_per_month' => 100,
                'max_items' => 50,
                'has_sales' => false,
                'has_inventory' => true,
                'has_accounting_full' => false,
                'is_active' => true,
                'badge_label' => 'Trial / Demo',
            ],
            [
                'slug' => 'basic',
                'name' => 'BASIC',
                'description' => 'Entry level untuk usaha kecil & menengah.',
                'price' => 59000,
                'duration_in_days' => 30,
                'max_users' => 2,
                'max_transactions_per_month' => 500,
                'max_items' => 200,
                'has_sales' => true,
                'has_inventory' => true,
                'has_accounting_full' => false,
                'is_active' => true,
                'badge_label' => 'Entry Level',
            ],
            [
                'slug' => 'pro',
                'name' => 'PRO',
                'description' => 'Populer untuk bisnis berkembang & operasional penuh.',
                'price' => 129000,
                'duration_in_days' => 30,
                'max_users' => 5,
                'max_transactions_per_month' => 2000,
                'max_items' => 1000,
                'has_sales' => true,
                'has_inventory' => true,
                'has_accounting_full' => true,
                'has_hr' => true,
                'is_active' => true,
                'badge_label' => 'MOST POPULAR ⭐',
            ],
            [
                'slug' => 'enterprise',
                'name' => 'ENTERPRISE',
                'description' => 'Solusi penuh tanpa limitasi untuk skala besar.',
                'price' => 249000,
                'duration_in_days' => 30,
                'max_users' => 30,
                'max_transactions_per_month' => 0, // Unlimited
                'max_items' => 0, // Unlimited
                'has_sales' => true,
                'has_inventory' => true,
                'has_accounting_full' => true,
                'has_hr' => true,
                'is_active' => true,
                'badge_label' => 'BISNIS SERIUS 💼',
            ],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
