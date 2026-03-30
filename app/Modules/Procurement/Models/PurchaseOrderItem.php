<?php

namespace App\Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Inventory\Models\InventoryItem;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'inventory_item_id', 'quantity', 'received_quantity', 'unit_price', 'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
