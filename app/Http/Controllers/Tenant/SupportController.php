<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Daftar tiket milik tenant ini (dari Central DB)
     */
    public function index()
    {
        $tenantId = tenant('id');
        $tickets = tenancy()->central(function () use ($tenantId) {
            return SupportTicket::where('tenant_id', $tenantId)
                ->withCount('replies')
                ->latest('last_replied_at')
                ->latest()
                ->get();
        });

        $stats = tenancy()->central(function () use ($tenantId) {
            return [
                'total'   => SupportTicket::where('tenant_id', $tenantId)->count(),
                'open'    => SupportTicket::where('tenant_id', $tenantId)->where('status', 'open')->count(),
                'pending' => SupportTicket::where('tenant_id', $tenantId)->where('status', 'pending')->count(),
                'closed'  => SupportTicket::where('tenant_id', $tenantId)->where('status', 'closed')->count(),
            ];
        });

        return view('tenant.support.index', compact('tickets', 'stats'));
    }

    /**
     * Form buat tiket baru
     */
    public function create()
    {
        return view('tenant.support.create');
    }

    /**
     * Simpan tiket baru ke Central DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $tenantId = tenant('id');
        $userId   = Auth::id();

        // Simpan nama user di tenant DB sebelum memasuki central context
        $userName = Auth::user()->name;

        $result = tenancy()->central(function () use ($request, $tenantId, $userId, $userName) {
            // user_id disimpan, meski relasi user mungkin di tenant DB
            $ticket = SupportTicket::create([
                'ticket_number' => SupportTicket::generateNumber(),
                'tenant_id'     => $tenantId,
                'user_id'       => $userId,
                'subject'       => $request->subject,
                'message'       => $request->message,
                'priority'      => $request->priority,
                'status'        => 'open',
                'last_replied_at' => now(),
            ]);
            return $ticket->id;
        });

        return redirect()->route('tenant.support.show', $result)
            ->with('success', 'Tiket dukungan berhasil dibuat. Tim kami akan merespons segera.');
    }

    /**
     * Detail tiket + riwayat balasan
     */
    public function show($id)
    {
        $tenantId = tenant('id');
        $ticket = tenancy()->central(function () use ($id, $tenantId) {
            return SupportTicket::with(['replies'])
                ->where('id', $id)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
        });

        return view('tenant.support.show', compact('ticket'));
    }

    /**
     * Kirim balasan dari tenant
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $tenantId = tenant('id');
        $userId   = Auth::id();

        tenancy()->central(function () use ($request, $id, $tenantId, $userId) {
            $ticket = SupportTicket::where('id', $id)->where('tenant_id', $tenantId)->firstOrFail();

            TicketReply::create([
                'support_ticket_id' => $ticket->id,
                'user_id'           => $userId,
                'message'           => $request->message,
                'is_staff'          => false, // Bukan staff, ini dari tenant
            ]);

            $ticket->update([
                'status'          => 'open', // Buka kembali jika closed
                'last_replied_at' => now(),
            ]);
        });

        return back()->with('success', 'Balasan Anda berhasil terkirim kepada tim dukungan.');
    }
}
