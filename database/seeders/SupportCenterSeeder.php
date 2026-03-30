<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\Faq;
use App\Models\User;
use App\Models\Tenant;

class SupportCenterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed FAQs
        $faqs = [
            [
                'question' => 'Bagaimana cara menambah admin baru?',
                'answer' => 'Anda bisa menambah admin melalui menu Manajemen User -> Admin. Pastikan Anda memiliki kuota admin yang cukup sesuai paket langganan Anda.',
                'category' => 'Akun',
                'order_priority' => 1
            ],
            [
                'question' => 'Kapan invoice langganan saya muncul?',
                'answer' => 'Invoice akan muncul otomatis 7 hari sebelum masa aktif paket Anda berakhir. Anda bisa mengeceknya di menu Billing.',
                'category' => 'Billing',
                'order_priority' => 2
            ],
            [
                'question' => 'Apakah data saya aman?',
                'answer' => 'Ya, kami menggunakan enkripsi standar industri dan sistem backup harian untuk memastikan data Anda selalu aman dan tersedia.',
                'category' => 'Umum',
                'order_priority' => 3
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // 2. Seed Sample Tickets (Only if tenants exist)
        $tenant = Tenant::first();
        $admin = User::where('email', 'admin@gmail.com')->first() ?: User::first();

        if ($tenant && $admin) {
            $ticket = SupportTicket::create([
                'ticket_number' => SupportTicket::generateNumber(),
                'tenant_id' => $tenant->id,
                'user_id' => $admin->id,
                'subject' => 'Kendala Integrasi Pembayaran',
                'message' => 'Halo, saya mencoba melakukan pembayaran tapi statusnya tidak kunjung berubah menjadi lunas. Mohon bantuannya.',
                'priority' => 'high',
                'status' => 'open',
                'last_replied_at' => now(),
            ]);

            // Add a reply from the user
            TicketReply::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => $admin->id,
                'message' => 'Saya sudah transfer via Bank BCA.',
                'is_staff' => false,
            ]);
        }
    }
}
