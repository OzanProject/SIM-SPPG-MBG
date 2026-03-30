<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CircleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CircleMenuController extends Controller
{
    public function index()
    {
        $menus = CircleMenu::orderBy('target_date', 'desc')->paginate(10);
        return view('tenant.circle-menus.index', compact('menus'));
    }

    public function create()
    {
        return view('tenant.circle-menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_date' => 'required|date',
            'location_name' => 'required|string|max:255',
            'total_portions' => 'required|integer|min:1',
            'menu_items' => 'required|string',
        ]);

        CircleMenu::create([
            'target_date' => $request->target_date,
            'location_name' => $request->location_name,
            'total_portions' => $request->total_portions,
            'menu_items' => explode("\n", str_replace("\r", "", $request->menu_items)),
            'status' => 'draft',
        ]);

        return redirect()->route('tenant.circle-menus.index', tenant('id'))
            ->with('success', 'Rencana Menu Circle berhasil diterbitkan!');
    }

    public function show($id)
    {
        $menu = CircleMenu::findOrFail($id);
        return view('tenant.circle-menus.show', compact('menu'));
    }

    public function edit($id)
    {
        $menu = CircleMenu::findOrFail($id);
        return view('tenant.circle-menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = CircleMenu::findOrFail($id);

        $request->validate([
            'target_date' => 'required|date',
            'location_name' => 'required|string|max:255',
            'total_portions' => 'required|integer|min:1',
            'menu_items' => 'required|string',
            'status' => 'required|in:draft,processing,completed',
            'documentation_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'target_date' => $request->target_date,
            'location_name' => $request->location_name,
            'total_portions' => $request->total_portions,
            'menu_items' => explode("\n", str_replace("\r", "", $request->menu_items)),
            'status' => $request->status,
        ];

        if ($request->hasFile('documentation_photo')) {
            if ($menu->documentation_photo) {
                Storage::disk('public')->delete($menu->documentation_photo);
            }
            $data['documentation_photo'] = $request->file('documentation_photo')->store('circle_menus', 'public');
            $data['status'] = 'completed'; // Auto complete when photo is uploaded if they haven't set it
        }

        $menu->update($data);

        return redirect()->route('tenant.circle-menus.index', tenant('id'))
            ->with('success', 'Data Menu Circle berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = CircleMenu::findOrFail($id);
        if ($menu->documentation_photo) {
            Storage::disk('public')->delete($menu->documentation_photo);
        }
        $menu->delete();

        return redirect()->route('tenant.circle-menus.index', tenant('id'))
            ->with('success', 'Rencana Menu berhasil dihapus.');
    }
}
