<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'unit', 'stock', 'average_price', 'minimum_stock'
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
