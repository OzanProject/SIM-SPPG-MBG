<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\InventoryItem;
use App\Models\Tenant\Menu;
use App\Models\Tenant\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Tampilkan semua menu dan status resepnya
     */
    public function indexAll()
    {
        $menus = Menu::withCount('recipes')->with('recipes.inventoryItem')->orderBy('name')->get();
        return view('tenant.sales.menu.recipe_all', compact('menus'));
    }

    /**
     * Tampilkan halaman kelola resep untuk sebuah menu
     */
    public function index(Menu $menu)
    {
        $menu->load(['recipes.inventoryItem']);
        $inventoryItems = InventoryItem::orderBy('name')->get();
        
        return view('tenant.sales.menu.recipe', compact('menu', 'inventoryItems'));
    }

    /**
     * Tambah/Update bahan baku dalam resep
     */
    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'ingredients.*.quantity'          => 'required|numeric|min:0',
        ]);

        // Hapus resep lama dan ganti dengan yang baru (sync)
        $menu->recipes()->delete();

        foreach ($request->ingredients as $ingredient) {
            if ($ingredient['quantity'] > 0) {
                $menu->recipes()->create([
                    'inventory_item_id' => $ingredient['inventory_item_id'],
                    'quantity'          => $ingredient['quantity'],
                    'note'              => $ingredient['note'] ?? null,
                ]);
            }
        }

        return redirect()->route('tenant.menu.recipe.index', [tenant('id'), $menu->id])
            ->with('success', 'Resep bahan baku berhasil diperbarui.');
    }
}
