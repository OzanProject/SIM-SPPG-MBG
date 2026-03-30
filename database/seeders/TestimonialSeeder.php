<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'content' => 'MBG Akunpro mengubah cara kami mengelola 12 cabang dapur sekaligus. Efisiensi bukan lagi sekadar harapan, tapi realita.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'content' => 'Sangat membantu untuk tracking stok barang dan pengadaan. Tidak ada lagi drama stok habis saat jam operasional.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Andi Wijaya',
                'content' => 'Fitur Payroll-nya memudahkan pembagian gaji karyawan yang jumlahnya ratusan. Sangat worth it!',
                'rating' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testi) {
            Testimonial::create($testi);
        }
    }
}
