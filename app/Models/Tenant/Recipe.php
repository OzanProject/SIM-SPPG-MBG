<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'inventory_item_id',
        'quantity',
        'note',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /**
     * Hitung estimasi biaya bahan baku untuk baris recipe ini
     */
    public function getEstimatedCostAttribute()
    {
        // Menggunakan average_price dari inventory_item jika tersedia
        return $this->quantity * ($this->inventoryItem->average_price ?? 0);
    }
}
