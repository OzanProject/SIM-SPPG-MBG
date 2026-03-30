<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apa itu MBG Akunpro?',
                'answer' => 'MBG Akunpro adalah platform manajemen bisnis katering terdepan yang dirancang khusus untuk mempermudah operasional harian, mulai dari manajemen menu, stok, hingga laporan keuangan otomatis bagi pemilik dapur.',
                'category' => 'Umum',
                'is_active' => true,
                'order_priority' => 1,
            ],
            [
                'question' => 'Bagaimana cara mendaftar sebagai tenant?',
                'answer' => 'Anda dapat mengeklik tombol "Daftar Sekarang" di halaman utama kemudian memilih paket langganan yang sesuai dengan kebutuhan katering Anda. Sistem kami akan membimbing langkah aktivasi akun Anda secara otomatis.',
                'category' => 'Pendaftaran',
                'is_active' => true,
                'order_priority' => 2,
            ],
            [
                'question' => 'Apakah data katering saya aman?',
                'answer' => 'Keamanan data adalah prioritas utama kami. MBG Akunpro menggunakan enkripsi tingkat lanjut dan database tenant yang terisolasi untuk memastikan informasi bisnis Anda tetap privat dan terlindungi.',
                'category' => 'Keamanan',
                'is_active' => true,
                'order_priority' => 3,
            ],
            [
                'question' => 'Dapatkah saya mengelola stok bahan baku?',
                'answer' => 'Tentu! Kami memiliki fitur inventaris cerdas yang terhubung langsung dengan resep (BOM). Stok bahan baku akan otomatis berkurang setiap kali ada penjualan menu yang telah di-mapping resepnya.',
                'category' => 'Fitur',
                'is_active' => true,
                'order_priority' => 4,
            ],
            [
                'question' => 'Apa ada dukungan teknis jika saya butuh bantuan?',
                'answer' => 'Tim dukungan kami siap membantu Anda melalui Pusat Dukungan di dalam platform maupun melalui tombol WhatsApp bantuan yang tersedia 24/7 bagi seluruh tenant aktif.',
                'category' => 'Dukungan',
                'is_active' => true,
                'order_priority' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
