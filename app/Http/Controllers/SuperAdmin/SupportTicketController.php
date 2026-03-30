<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['tenant', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by tenant
        if ($request->has('tenant_id') && $request->tenant_id != '') {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest('last_replied_at')->latest()->paginate(15);
        $tenants = Tenant::all();

        // Statistics
        $stats = [
            'total'   => SupportTicket::count(),
            'open'    => SupportTicket::where('status', 'open')->count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'closed'  => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('super-admin.support.tickets.index', compact('tickets', 'tenants', 'stats'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['tenant', 'user', 'replies.user']);
        return view('super-admin.support.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'status'  => 'nullable|string|in:open,pending,closed',
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id'           => Auth::id(),
            'message'           => $request->message,
            'is_staff'          => true,
        ]);

        // Update ticket status and last replied time
        $updateData = ['last_replied_at' => now()];
        if ($request->status) {
            $updateData['status'] = $request->status;
        }
        $ticket->update($updateData);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|string|in:open,pending,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('support.tickets.index')->with('success', 'Tiket berhasil dihapus.');
    }
}
