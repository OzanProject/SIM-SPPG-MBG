<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::latest()->get();
        $totalItems = $items->count();
        $lowStockCount = $items->where('stock', '>', 0)->where('stock', '<=', 'minimum_stock')->count();
        $outOfStockCount = $items->where('stock', '<=', 0)->count();

        return view('tenant.inventory.items.index', compact('items', 'totalItems', 'lowStockCount', 'outOfStockCount'));
    }

    public function create()
    {
        return view('tenant.inventory.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:inventory_items,code',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('inventory.items.index', tenant('id'))->with('success', 'Barang "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function edit(InventoryItem $item)
    {
        return view('tenant.inventory.items.edit', compact('item'));
    }

    public function update(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'code' => 'required|unique:inventory_items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('inventory.items.index', tenant('id'))->with('success', 'Barang "' . $item->name . '" berhasil diperbarui.');
    }

    public function destroy(InventoryItem $item)
    {
        $name = $item->name;
        $item->delete();
        return redirect()->route('inventory.items.index', tenant('id'))->with('success', 'Barang "' . $name . '" berhasil dihapus.');
    }
}
