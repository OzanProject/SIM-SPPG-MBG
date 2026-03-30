<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Feature::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $features = [
            // 1. LARGE CARD — Inventory (hero feature)
            [
                'icon'           => 'inventory_2',
                'icon_type'      => 'symbol',
                'color_class'    => 'indigo',
                'title'          => 'Inventaris Otomatis & Real-Time.',
                'description'    => 'Pantau stok bahan baku secara akurat di setiap cabang. Sistem memberi peringatan proaktif sebelum stok mencapai titik kritis.',
                'size'           => 'large',
                'badge_text'     => null,
                'is_active'      => true,
                'order_priority' => 1,
            ],
            // 2. MEDIUM CARD — Accounting
            [
                'icon'           => 'account_balance_wallet',
                'icon_type'      => 'symbol',
                'color_class'    => 'purple',
                'title'          => 'Akuntansi Otomatis.',
                'description'    => 'Laporan Laba Rugi dan Neraca tersedia instan tanpa perlu input manual. Sesuai standar SAK EMKM.',
                'size'           => 'medium',
                'badge_text'     => 'Ready to Audit',
                'is_active'      => true,
                'order_priority' => 2,
            ],
            // 3. MEDIUM CARD — HR & Payroll
            [
                'icon'           => 'user_attributes',
                'icon_type'      => 'symbol',
                'color_class'    => 'blue',
                'title'          => 'HR & Payroll Karyawan.',
                'description'    => 'Kelola database karyawan dan hitung gaji otomatis hanya dengan satu klik setiap bulannya.',
                'size'           => 'medium',
                'badge_text'     => null,
                'is_active'      => true,
                'order_priority' => 3,
            ],
            // 4. MEDIUM CARD — Budgeting
            [
                'icon'           => 'monitoring',
                'icon_type'      => 'symbol',
                'color_class'    => 'emerald',
                'title'          => 'Monitoring Anggaran.',
                'description'    => 'Kontrol pengeluaran operasional agar tetap sesuai target dan cegah pemborosan anggaran.',
                'size'           => 'medium',
                'badge_text'     => null,
                'is_active'      => true,
                'order_priority' => 4,
            ],
            // 5. SMALL CARD — Sales Analysis
            [
                'icon'           => 'bar_chart',
                'icon_type'      => 'symbol',
                'color_class'    => 'amber',
                'title'          => 'Sales Analysis',
                'description'    => 'Visualisasi performa harian yang tajam untuk keputusan bisnis cepat.',
                'size'           => 'small',
                'badge_text'     => null,
                'is_active'      => true,
                'order_priority' => 5,
            ],
            // 6. SMALL CARD — Procurement PO
            [
                'icon'           => 'local_shipping',
                'icon_type'      => 'symbol',
                'color_class'    => 'rose',
                'title'          => 'Procurement PO',
                'description'    => 'Kelola pesanan supplier secara otomatis berdasarkan kebutuhan stok.',
                'size'           => 'small',
                'badge_text'     => null,
                'is_active'      => true,
                'order_priority' => 6,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }

        $this->command->info('✅ FeatureSeeder: ' . count($features) . ' fitur berhasil ditanam.');
    }
}
