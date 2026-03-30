<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('tenant.sales.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('tenant.sales.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|unique:menus,code',
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:makanan,minuman,snack,lainnya',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Menu::create($request->all());

        return redirect()->route('tenant.menu.index', tenant('id'))
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('tenant.sales.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'code'        => 'required|unique:menus,code,' . $menu->id,
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:makanan,minuman,snack,lainnya',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_available'=> 'required|boolean',
        ]);

        $menu->update($request->all());

        return redirect()->route('tenant.menu.index', tenant('id'))
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('tenant.menu.index', tenant('id'))
            ->with('success', 'Menu berhasil dihapus.');
    }
}
