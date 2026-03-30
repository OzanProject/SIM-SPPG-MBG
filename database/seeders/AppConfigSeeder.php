<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppConfig;

class AppConfigSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AppConfig::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $configs = [
            // GROUP: general
            ['key' => 'app_name', 'value' => 'SIM-SPGG MBG PRO', 'group' => 'general', 'label' => 'Nama Aplikasi', 'type' => 'text'],
            ['key' => 'app_tagline', 'value' => 'Sistem Informasi Satuan Pelayanan Pemenuhan Gizi Makan Bergizi Gratis Pro', 'group' => 'general', 'label' => 'Tagline / Slogan', 'type' => 'text'],
            ['key' => 'app_version', 'value' => 'v1.0.0', 'group' => 'general', 'label' => 'Versi Aplikasi', 'type' => 'text'],
            ['key' => 'company_name', 'value' => 'Ozan Project', 'group' => 'general', 'label' => 'Nama Perusahaan', 'type' => 'text'],
            ['key' => 'copyright_year', 'value' => '2026', 'group' => 'general', 'label' => 'Tahun Copyright', 'type' => 'text'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'group' => 'general', 'label' => 'Zona Waktu Server', 'type' => 'select'],

            // GROUP: appearance
            ['key' => 'logo_url', 'value' => '/adminlte3/dist/img/AdminLTELogo.png', 'group' => 'appearance', 'label' => 'URL Logo Aplikasi', 'type' => 'url'],
            ['key' => 'favicon_url', 'value' => '/adminlte3/dist/img/AdminLTELogo.png', 'group' => 'appearance', 'label' => 'URL Favicon', 'type' => 'url'],
            ['key' => 'primary_color', 'value' => '#007bff', 'group' => 'appearance', 'label' => 'Warna Utama (Primary)', 'type' => 'color'],
            ['key' => 'sidebar_theme', 'value' => 'sidebar-dark-primary', 'group' => 'appearance', 'label' => 'Tema Warna Sidebar', 'type' => 'text'],

            // GROUP: contact
            ['key' => 'admin_email',        'value' => 'ardiansyahdzan@gmail.com',     'group' => 'contact', 'label' => 'Email Admin',                   'type' => 'email'],
            ['key' => 'support_email',      'value' => 'ardiansyahdzan@gmail.com',     'group' => 'contact', 'label' => 'Email Support',                 'type' => 'email'],
            ['key' => 'email',              'value' => 'hello@mbgakun.pro',            'group' => 'contact', 'label' => 'Email Publik (tampil di footer)', 'type' => 'email'],
            ['key' => 'phone',              'value' => '+62 811-0000-0000',            'group' => 'contact', 'label' => 'Nomor Telepon',                  'type' => 'text'],
            ['key' => 'address',            'value' => 'Indonesia',                    'group' => 'contact', 'label' => 'Alamat Kantor',                  'type' => 'textarea'],
            ['key' => 'footer_description', 'value' => 'Merevolusi manajemen operasional dapur dengan sistem cerdas, transparan, dan terintegrasi penuh untuk masa depan bisnis kuliner Anda.', 'group' => 'contact', 'label' => 'Deskripsi Singkat Footer', 'type' => 'textarea'],

            // GROUP: social
            ['key' => 'social_facebook',  'value' => 'https://facebook.com/',   'group' => 'social', 'label' => 'URL Facebook',  'type' => 'url'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/',  'group' => 'social', 'label' => 'URL Instagram', 'type' => 'url'],
            ['key' => 'social_youtube',   'value' => '',                         'group' => 'social', 'label' => 'URL YouTube',   'type' => 'url'],
            ['key' => 'social_twitter',   'value' => '',                         'group' => 'social', 'label' => 'URL Twitter/X', 'type' => 'url'],
            ['key' => 'social_tiktok',    'value' => '',                         'group' => 'social', 'label' => 'URL TikTok',    'type' => 'url'],
        ];

        foreach ($configs as $config) {
            AppConfig::create($config);
        }

        $this->command->info('✅ AppConfigSeeder: ' . count($configs) . ' konfigurasi aplikasi berhasil ditanam.');
    }
}
