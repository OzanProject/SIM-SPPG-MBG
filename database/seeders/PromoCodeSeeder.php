<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoCode;
use App\Models\SubscriptionPlan;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan dengan aman menggunakan disable FK constraint
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('promo_code_subscription_plan')->delete();
        PromoCode::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID paket untuk digunakan di relasi
        $freeTier   = SubscriptionPlan::where('name', 'Free Tier')->first();
        $premium    = SubscriptionPlan::where('name', 'Premium')->first();
        $enterprise = SubscriptionPlan::where('name', 'Enterprise')->first();
        $sovereign  = SubscriptionPlan::where('name', 'SOVEREIGN')->first();

        $promos = [
            [
                'code'        => 'BARU2026',
                'type'        => 'fixed',
                'value'       => 15000,
                'starts_at'   => '2026-01-13',
                'ends_at'     => '2026-12-31',
                'max_uses'    => 5,
                'used_count'  => 1,
                'is_active'   => true,
                'plans'       => [$premium?->id, $enterprise?->id], // Berlaku untuk Premium & Enterprise
            ],
            [
                'code'        => 'THN2026',
                'type'        => 'fixed',
                'value'       => 20000,
                'starts_at'   => '2026-01-13',
                'ends_at'     => '2026-12-31',
                'max_uses'    => 5,
                'used_count'  => 1,
                'is_active'   => true,
                'plans'       => [], // Berlaku untuk semua paket
            ],
            [
                'code'        => 'GRATIS30',
                'type'        => 'percent',
                'value'       => 30,
                'starts_at'   => '2026-03-01',
                'ends_at'     => '2026-04-30',
                'max_uses'    => 10,
                'used_count'  => 0,
                'is_active'   => true,
                'plans'       => [$premium?->id], // Khusus paket Premium
            ],
            [
                'code'        => 'SOVEREIGN50',
                'type'        => 'percent',
                'value'       => 50,
                'starts_at'   => '2026-03-01',
                'ends_at'     => '2026-06-30',
                'max_uses'    => 3,
                'used_count'  => 0,
                'is_active'   => true,
                'plans'       => [$sovereign?->id], // Khusus paket SOVEREIGN
            ],
            [
                'code'        => 'RAMADAN2026',
                'type'        => 'fixed',
                'value'       => 50000,
                'starts_at'   => '2026-03-15',
                'ends_at'     => '2026-04-15',
                'max_uses'    => 20,
                'used_count'  => 0,
                'is_active'   => false, // Belum diaktifkan
                'plans'       => [],
            ],
        ];

        foreach ($promos as $data) {
            $planIds = array_filter($data['plans']); // Hilangkan null jika ada paket tidak ditemukan
            unset($data['plans']);

            $promo = PromoCode::create($data);

            if (!empty($planIds)) {
                $promo->subscriptionPlans()->sync($planIds);
            }
        }

        $this->command->info('✅ PromoCodeSeeder: ' . count($promos) . ' kode promo berhasil ditanam.');
    }
}
