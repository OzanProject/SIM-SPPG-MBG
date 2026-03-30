<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function subscriptions()
    {
        return view('super-admin.placeholder', ['title' => 'Manajemen Paket Langganan', 'icon' => 'fa-tags', 'desc' => 'Kelola paket pricing, limit data cabang, dan subscription plans.']);
    }

    public function billing()
    {
        return view('super-admin.placeholder', ['title' => 'Billing & Riwayat Tagihan', 'icon' => 'fa-file-invoice-dollar', 'desc' => 'Daftar invoice, payment gateway, dan status pembayaran bulanan tenant.']);
    }

    public function monitoring()
    {
        return view('super-admin.placeholder', ['title' => 'Monitoring Server & Antrean', 'icon' => 'fa-server', 'desc' => 'Pantau penggunaan RAM, CPU, Redis queue, dan performa database cabang.']);
    }

    public function auditLogs()
    {
        return view('super-admin.placeholder', ['title' => 'Global Audit Logs', 'icon' => 'fa-shield-alt', 'desc' => 'Jejak aktivitas semua super admin dan pergerakan kritis lintas sistem.']);
    }

    public function notifications()
    {
        return view('super-admin.placeholder', ['title' => 'Notifikasi & Broadcast', 'icon' => 'fa-bell', 'desc' => 'Kirim email broadcast atau notifikasi in-app ke seluruh Admin Cabang.']);
    }

    public function backups()
    {
        return view('super-admin.placeholder', ['title' => 'Backup & Restore Center', 'icon' => 'fa-database', 'desc' => 'Download atau jadwalkan backup database central dan tenant secara berkala.']);
    }
}
