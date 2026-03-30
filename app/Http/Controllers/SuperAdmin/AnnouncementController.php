<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GlobalAnnouncement;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = GlobalAnnouncement::orderBy('created_at', 'desc')->paginate(10);
        return view('super-admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.announcements.create_edit', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:info,success,warning,danger',
            'target_plan' => 'nullable|string',
            'is_active'   => 'required|boolean',
            'show_modal'  => 'required|boolean',
            'is_persistent' => 'required|boolean',
            'expires_at'  => 'nullable|date',
        ]);

        GlobalAnnouncement::create($data);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(GlobalAnnouncement $announcement)
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.announcements.create_edit', compact('announcement', 'plans'));
    }

    public function update(Request $request, GlobalAnnouncement $announcement)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:info,success,warning,danger',
            'target_plan' => 'nullable|string',
            'is_active'   => 'required|boolean',
            'show_modal'  => 'required|boolean',
            'is_persistent' => 'required|boolean',
            'expires_at'  => 'nullable|date',
        ]);

        $announcement->update($data);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(GlobalAnnouncement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
