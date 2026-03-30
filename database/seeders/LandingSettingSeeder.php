<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingSetting;

class LandingSettingSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LandingSetting::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $settings = [
            // ═══════════════════════════════════════════
            // HERO SECTION
            // ═══════════════════════════════════════════
            ['key' => 'hero_badge',          'value' => 'Manajemen Dapur Modern',          'group' => 'hero', 'label' => 'Badge Atas Hero',                  'type' => 'text'],
            ['key' => 'hero_title',          'value' => 'Kelola Dapur Tanpa Ribet.',        'group' => 'hero', 'label' => 'Judul Hero (Baris 1)',              'type' => 'text'],
            ['key' => 'hero_title_gradient', 'value' => 'Semua Serba Otomatis.',           'group' => 'hero', 'label' => 'Judul Hero (Baris 2 – Gradient)',   'type' => 'text'],
            ['key' => 'hero_subtitle',       'value' => 'Kontrol stok, penjualan, dan laporan keuangan dalam satu sistem. Hemat waktu, minim kesalahan, dan siap scale bisnis Anda.', 'group' => 'hero', 'label' => 'Sub-judul Hero', 'type' => 'textarea'],
            ['key' => 'hero_cta_text',       'value' => 'Mulai Gratis 🚀',                 'group' => 'hero', 'label' => 'Teks Tombol CTA Utama',             'type' => 'text'],
            ['key' => 'hero_cta_url',        'value' => '/register',                       'group' => 'hero', 'label' => 'URL Tombol CTA Utama',              'type' => 'url'],
            ['key' => 'hero_demo_text',      'value' => 'Lihat Demo',                      'group' => 'hero', 'label' => 'Teks Tombol Demo (Sekunder)',        'type' => 'text'],
            ['key' => 'hero_demo_url',       'value' => '/features',                       'group' => 'hero', 'label' => 'URL Tombol Demo',                   'type' => 'url'],
            ['key' => 'hero_image_url',      'value' => '',                                'group' => 'hero', 'label' => 'Gambar Hero (Upload)',               'type' => 'file'],

            // Trust Metrics (Hero)
            ['key' => 'stats_biz_val',       'value' => '500+',                            'group' => 'hero', 'label' => 'Stat: Jumlah Bisnis',               'type' => 'text'],
            ['key' => 'stats_biz_lbl',       'value' => 'Bisnis Aktif',                   'group' => 'hero', 'label' => 'Stat: Label Bisnis',                 'type' => 'text'],
            ['key' => 'stats_trx_val',       'value' => 'Rp 150M+',                       'group' => 'hero', 'label' => 'Stat: Total Transaksi',              'type' => 'text'],
            ['key' => 'stats_trx_lbl',       'value' => 'Transaksi',                      'group' => 'hero', 'label' => 'Stat: Label Transaksi',              'type' => 'text'],
            ['key' => 'stats_upt_val',       'value' => '99.9%',                          'group' => 'hero', 'label' => 'Stat: Uptime Sistem',                'type' => 'text'],
            ['key' => 'stats_upt_lbl',       'value' => 'Uptime',                         'group' => 'hero', 'label' => 'Stat: Label Uptime',                 'type' => 'text'],
            ['key' => 'stats_revenue_label', 'value' => 'Revenue',                        'group' => 'hero', 'label' => 'Floating Card: Label',               'type' => 'text'],
            ['key' => 'stats_revenue_value', 'value' => '+24.5%',                         'group' => 'hero', 'label' => 'Floating Card: Nilai',               'type' => 'text'],

            // ═══════════════════════════════════════════
            // TRUST SECTION
            // ═══════════════════════════════════════════
            ['key' => 'trust_badge',         'value' => 'Dipercaya oleh bisnis dapur modern', 'group' => 'trust', 'label' => 'Badge Trust Section',          'type' => 'text'],
            ['key' => 'trust_heading',       'value' => 'Bergabung dengan ratusan dapur yang berkembang 🚀', 'group' => 'trust', 'label' => 'Heading Trust Section', 'type' => 'text'],

            // ═══════════════════════════════════════════
            // FEATURES SECTION
            // ═══════════════════════════════════════════
            ['key' => 'features_badge',       'value' => 'Ekosistem Digital Terpadu',      'group' => 'features', 'label' => 'Badge Features',               'type' => 'text'],
            ['key' => 'features_title',       'value' => 'Seluruh Kebutuhan Dapur',        'group' => 'features', 'label' => 'Judul Features (Baris 1)',      'type' => 'text'],
            ['key' => 'features_subtitle',    'value' => 'Dalam Satu Dashboard.',          'group' => 'features', 'label' => 'Judul Features (Baris 2)',      'type' => 'text'],
            ['key' => 'features_description', 'value' => 'Sinkronisasi data real-time antara stok, penjualan, biaya, hingga gaji karyawan untuk efisiensi maksimal.', 'group' => 'features', 'label' => 'Deskripsi Features', 'type' => 'textarea'],

            // ═══════════════════════════════════════════
            // HOW IT WORKS SECTION
            // ═══════════════════════════════════════════
            ['key' => 'hiw_badge',           'value' => 'Alur Kerja Cerdas',              'group' => 'how_it_works', 'label' => 'Badge HIW',                 'type' => 'text'],
            ['key' => 'hiw_title',           'value' => 'Cara Kami Mengubah Dapur Anda.', 'group' => 'how_it_works', 'label' => 'Judul HIW',                  'type' => 'text'],

            ['key' => 'hiw_step1_icon',      'value' => '🚀',                             'group' => 'how_it_works', 'label' => 'Step 1: Icon',               'type' => 'text'],
            ['key' => 'hiw_step1_text',      'value' => 'Registrasi Cepat',               'group' => 'how_it_works', 'label' => 'Step 1: Judul',              'type' => 'text'],
            ['key' => 'hiw_step1_desc',      'value' => 'Daftarkan cabang dapur Anda dalam hitungan menit dan mulai kelola data secara digital.', 'group' => 'how_it_works', 'label' => 'Step 1: Deskripsi', 'type' => 'textarea'],

            ['key' => 'hiw_step2_icon',      'value' => '📊',                             'group' => 'how_it_works', 'label' => 'Step 2: Icon',               'type' => 'text'],
            ['key' => 'hiw_step2_text',      'value' => 'Input Transaksi',                'group' => 'how_it_works', 'label' => 'Step 2: Judul',              'type' => 'text'],
            ['key' => 'hiw_step2_desc',      'value' => 'Catat setiap pemasukan, pengeluaran, dan stok barang dengan antarmuka yang intuitif.', 'group' => 'how_it_works', 'label' => 'Step 2: Deskripsi', 'type' => 'textarea'],

            ['key' => 'hiw_step3_icon',      'value' => '🛡️',                             'group' => 'how_it_works', 'label' => 'Step 3: Icon',               'type' => 'text'],
            ['key' => 'hiw_step3_text',      'value' => 'Monitoring Real-time',           'group' => 'how_it_works', 'label' => 'Step 3: Judul',              'type' => 'text'],
            ['key' => 'hiw_step3_desc',      'value' => 'Pantau performa keuangan dan operasional dari dashboard pusat kapan saja, di mana saja.', 'group' => 'how_it_works', 'label' => 'Step 3: Deskripsi', 'type' => 'textarea'],

            ['key' => 'hiw_step4_icon',      'value' => '📈',                             'group' => 'how_it_works', 'label' => 'Step 4: Icon',               'type' => 'text'],
            ['key' => 'hiw_step4_text',      'value' => 'Laporan Otomatis',               'group' => 'how_it_works', 'label' => 'Step 4: Judul',              'type' => 'text'],
            ['key' => 'hiw_step4_desc',      'value' => 'Dapatkan laporan keuangan lengkap (Neraca, Laba Rugi) secara otomatis setiap periode.', 'group' => 'how_it_works', 'label' => 'Step 4: Deskripsi', 'type' => 'textarea'],

            // ═══════════════════════════════════════════
            // PRICING SECTION
            // ═══════════════════════════════════════════
            ['key' => 'pricing_badge',        'value' => 'Harga Fleksibel',               'group' => 'pricing', 'label' => 'Badge Pricing',                  'type' => 'text'],
            ['key' => 'pricing_title',        'value' => 'Pilih Paket Sesuai Kebutuhan Anda', 'group' => 'pricing', 'label' => 'Judul Pricing',              'type' => 'text'],
            ['key' => 'pricing_description',  'value' => 'Mulai gratis, upgrade kapan saja. Tanpa biaya tersembunyi.', 'group' => 'pricing', 'label' => 'Deskripsi Pricing', 'type' => 'textarea'],
            ['key' => 'pricing_trust_text',   'value' => '✔ Tanpa kontrak • ✔ Bisa upgrade kapan saja • ✔ Data aman & terenkripsi', 'group' => 'pricing', 'label' => 'Teks Trust di Bawah Harga', 'type' => 'text'],

            // ═══════════════════════════════════════════
            // TESTIMONIALS SECTION
            // ═══════════════════════════════════════════
            ['key' => 'testi_badge',          'value' => 'Kisah Sukses Nyata',            'group' => 'testimonials', 'label' => 'Badge Testimoni',            'type' => 'text'],
            ['key' => 'testi_title',          'value' => 'Dicintai oleh',                 'group' => 'testimonials', 'label' => 'Judul Testimoni (Baris 1)',   'type' => 'text'],
            ['key' => 'testi_title_gradient', 'value' => 'Para Kreator Dapur.',           'group' => 'testimonials', 'label' => 'Judul Testimoni (Baris gradient)', 'type' => 'text'],
            ['key' => 'testi_description',    'value' => 'Ribuan pemilik bisnis katering dan dapur katering telah bertransformasi bersama MBG Akunpro. Dengarkan kisah mereka.', 'group' => 'testimonials', 'label' => 'Deskripsi Testimoni', 'type' => 'textarea'],

            // ═══════════════════════════════════════════
            // FAQ SECTION
            // ═══════════════════════════════════════════
            ['key' => 'faq_badge',            'value' => 'FAQ',                           'group' => 'faq', 'label' => 'Badge FAQ',                           'type' => 'text'],
            ['key' => 'faq_title',            'value' => 'Masih',                         'group' => 'faq', 'label' => 'Judul FAQ (Baris 1)',                  'type' => 'text'],
            ['key' => 'faq_title_gradient',   'value' => 'Ragu?',                         'group' => 'faq', 'label' => 'Judul FAQ (Kata Gradient)',            'type' => 'text'],
            ['key' => 'faq_description',      'value' => 'Kami sudah merangkum pertanyaan yang paling sering ditanyakan sebelum mulai menggunakan sistem ini.', 'group' => 'faq', 'label' => 'Deskripsi FAQ', 'type' => 'textarea'],
            ['key' => 'faq_cta_text',         'value' => 'Masih ada pertanyaan lain?',   'group' => 'faq', 'label' => 'Teks CTA Bawah FAQ',                  'type' => 'text'],
            ['key' => 'faq_cta_btn1_text',    'value' => 'Coba Gratis Sekarang',         'group' => 'faq', 'label' => 'FAQ CTA Tombol 1',                     'type' => 'text'],
            ['key' => 'faq_cta_btn2_text',    'value' => 'Hubungi Tim Kami',             'group' => 'faq', 'label' => 'FAQ CTA Tombol 2',                     'type' => 'text'],
            ['key' => 'faq_cta_btn2_url',     'value' => '#contact',                     'group' => 'faq', 'label' => 'FAQ CTA Tombol 2 URL',                 'type' => 'url'],

            // ═══════════════════════════════════════════
            // CTA SECTION
            // ═══════════════════════════════════════════
            ['key' => 'cta_badge',            'value' => 'Siap Berevolusi?',             'group' => 'cta', 'label' => 'Badge CTA',                           'type' => 'text'],
            ['key' => 'cta_title',            'value' => 'Revolusi Dapur Anda',          'group' => 'cta', 'label' => 'Judul CTA (Baris 1)',                  'type' => 'text'],
            ['key' => 'cta_title_gradient',   'value' => 'Mulai Dari Sini.',             'group' => 'cta', 'label' => 'Judul CTA (Baris 2 – Gradient)',       'type' => 'text'],
            ['key' => 'cta_description',      'value' => 'Bergabunglah sekarang dan rasakan efisiensi operasional yang belum pernah Anda bayangkan sebelumnya.', 'group' => 'cta', 'label' => 'Deskripsi CTA', 'type' => 'textarea'],
            ['key' => 'cta_btn1_text',        'value' => 'Daftar Sekarang',             'group' => 'cta', 'label' => 'CTA Tombol 1 (Primer)',                 'type' => 'text'],
            ['key' => 'cta_btn2_text',        'value' => 'Eksplorasi Fitur',            'group' => 'cta', 'label' => 'CTA Tombol 2 (Sekunder)',               'type' => 'text'],
            ['key' => 'cta_btn2_url',         'value' => '/features',                   'group' => 'cta', 'label' => 'URL Tombol CTA Kedua',                  'type' => 'url'],

            // ═══════════════════════════════════════════
            // SEO
            // ═══════════════════════════════════════════
            ['key' => 'meta_title',       'value' => 'MBG AkunPro — Akuntansi Digital Dapur MBG',                            'group' => 'seo', 'label' => 'Meta Title',       'type' => 'text'],
            ['key' => 'meta_description', 'value' => 'Platform SaaS akuntansi untuk pengelolaan keuangan cabang Dapur MBG secara digital.', 'group' => 'seo', 'label' => 'Meta Description', 'type' => 'textarea'],
            ['key' => 'meta_keywords',    'value' => 'akuntansi, dapur MBG, SaaS, keuangan dapur',                           'group' => 'seo', 'label' => 'Meta Keywords',    'type' => 'text'],

            // ═══════════════════════════════════════════
            // CONTACT / WHATSAPP
            // ═══════════════════════════════════════════
            ['key' => 'whatsapp_number',  'value' => '6281234567890',                                     'group' => 'contact', 'label' => 'Nomor WhatsApp Admin', 'type' => 'text'],
            ['key' => 'whatsapp_message', 'value' => 'Halo, saya ingin bertanya tentang MBG AkunPro.',    'group' => 'contact', 'label' => 'Pesan WhatsApp Default', 'type' => 'textarea'],

            // ═══════════════════════════════════════════
            // SMTP
            // ═══════════════════════════════════════════
            ['key' => 'mail_host',         'value' => 'smtp.mailtrap.io',         'group' => 'smtp', 'label' => 'SMTP Host',         'type' => 'text'],
            ['key' => 'mail_port',         'value' => '2525',                     'group' => 'smtp', 'label' => 'SMTP Port',         'type' => 'text'],
            ['key' => 'mail_username',     'value' => '',                         'group' => 'smtp', 'label' => 'SMTP Username',     'type' => 'text'],
            ['key' => 'mail_password',     'value' => '',                         'group' => 'smtp', 'label' => 'SMTP Password',     'type' => 'password'],
            ['key' => 'mail_encryption',   'value' => 'tls',                      'group' => 'smtp', 'label' => 'SMTP Encryption',   'type' => 'text'],
            ['key' => 'mail_from_address', 'value' => 'admin@mbg-akunpro.com',   'group' => 'smtp', 'label' => 'Mail From Address', 'type' => 'text'],
            ['key' => 'mail_from_name',    'value' => 'MBG AkunPro',             'group' => 'smtp', 'label' => 'Mail From Name',    'type' => 'text'],

            // ═══════════════════════════════════════════
            // NAVBAR SECTION
            // ═══════════════════════════════════════════
            ['key' => 'nav_menu_features',    'value' => 'Fitur',          'group' => 'navbar', 'label' => 'Menu: Label Fitur',                              'type' => 'text'],
            ['key' => 'nav_menu_pricing',     'value' => 'Harga',          'group' => 'navbar', 'label' => 'Menu: Label Harga',                              'type' => 'text'],
            ['key' => 'nav_menu_faq',         'value' => 'FAQ',            'group' => 'navbar', 'label' => 'Menu: Label FAQ',                                'type' => 'text'],
            ['key' => 'nav_menu_about',       'value' => '',               'group' => 'navbar', 'label' => 'Menu: Label Tentang Kami (kosong = tidak tampil)', 'type' => 'text'],
            ['key' => 'nav_menu_contact',     'value' => '',               'group' => 'navbar', 'label' => 'Menu: Label Kontak (kosong = tidak tampil)',      'type' => 'text'],
            ['key' => 'nav_login_text',       'value' => 'Masuk',          'group' => 'navbar', 'label' => 'Teks Tombol Login',                              'type' => 'text'],
            ['key' => 'nav_register_text',    'value' => 'Mulai Sekarang', 'group' => 'navbar', 'label' => 'Teks Tombol Register / CTA',                    'type' => 'text'],
            ['key' => 'nav_dashboard_text',   'value' => 'Dashboard',      'group' => 'navbar', 'label' => 'Teks Tombol Dashboard (saat sudah login)',        'type' => 'text'],
            // ═══════════════════════════════════════════
            // FOOTER SECTION
            // ═══════════════════════════════════════════
            ['key' => 'footer_legal1_text',   'value' => 'Syarat & Layanan',   'group' => 'footer', 'label' => 'Teks Legalitas 1', 'type' => 'text'],
            ['key' => 'footer_legal1_url',    'value' => '/page/terms',        'group' => 'footer', 'label' => 'URL Legalitas 1',  'type' => 'url'],
            ['key' => 'footer_legal2_text',   'value' => 'Kebijakan Privasi',  'group' => 'footer', 'label' => 'Teks Legalitas 2', 'type' => 'text'],
            ['key' => 'footer_legal2_url',    'value' => '/page/privacy',      'group' => 'footer', 'label' => 'URL Legalitas 2',  'type' => 'url'],
            ['key' => 'footer_terms_content', 'value' => '<p>Konten Syarat dan Layanan akan tampil di sini...</p>', 'group' => 'footer', 'label' => 'Detail Konten Syarat & Layanan (Bisa HTML)', 'type' => 'textarea'],
            ['key' => 'footer_privacy_content', 'value' => '<p>Konten Kebijakan Privasi akan tampil di sini...</p>', 'group' => 'footer', 'label' => 'Detail Konten Kebijakan Privasi (Bisa HTML)', 'type' => 'textarea'],
        ];

        foreach ($settings as $s) {
            LandingSetting::create($s);
        }

        $this->command->info('✅ LandingSettingSeeder: ' . count($settings) . ' pengaturan berhasil ditanam.');
    }
}
