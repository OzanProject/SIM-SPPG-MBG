<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalAnnouncement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        GlobalAnnouncement::create([
            'title'       => 'Maintenance Sistem Terjadwal',
            'body'        => 'Kami akan melakukan pemeliharaan sistem pada hari Minggu, 29 Maret 2026 pukul 00:00 - 04:00 WIB. Selama waktu tersebut, aplikasi mungkin tidak dapat diakses.',
            'type'        => 'warning',
            'is_active'   => true,
            'show_modal'  => true,
            'is_persistent' => false,
        ]);

        GlobalAnnouncement::create([
            'title'       => 'Fitur Baru: Modul Payroll',
            'body'        => 'Kini Anda dapat mengelola penggajian karyawan dengan lebih mudah melalui modul Payroll yang baru saja kami rilis. Silakan cek menu HR > Payroll.',
            'type'        => 'success',
            'is_active'   => true,
            'show_modal'  => false,
            'is_persistent' => false,
        ]);

        GlobalAnnouncement::create([
            'title'       => 'Pemberitahuan Pembayaran',
            'body'        => 'Jangan lupa untuk melakukan pembayaran langganan tepat waktu agar layanan Anda tetap aktif.',
            'type'        => 'info',
            'is_active'   => true,
            'show_modal'  => true,
            'is_persistent' => true,
            'target_plan' => 'free',
        ]);
    }

    /**
     * Run methods.
     */
    public function run(): void
    {
        // Menghapus data lama agar tidak double
        \App\Models\GlobalAnnouncement::truncate();
        
        $this->up();
    }
}
