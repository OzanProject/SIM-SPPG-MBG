<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi (Inbox)
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('tenant.notifications.index', compact('notifications'));
    }

    /**
     * Tandai notifikasi sebagai dibaca dan arahkan ke detail/link terkait
     */
    public function show($tenantId, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        // Tentukan redirect berdasarkan tipe notifikasi
        if (isset($notification->data['type']) && $notification->data['type'] === 'low_stock') {
            return redirect()->route('inventory.items.index', [tenant('id'), 'search' => $notification->data['item_name']]);
        }

        if (isset($notification->data['type']) && $notification->data['type'] === 'subscription_alert') {
            return redirect()->route('tenant.billing.index', tenant('id'));
        }

        return redirect()->back();
    }

    /**
     * Tandai semua sebagai dibaca
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
