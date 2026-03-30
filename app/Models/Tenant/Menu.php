<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'price',
        'description',
        'is_available',
    ];

    /**
     * Dapatkan format harga Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Hitung total HPP (Harga Pokok Penjualan) dari resep
     */
    public function calculateHpp()
    {
        $total = 0;
        foreach ($this->recipes as $recipe) {
            $total += $recipe->quantity * ($recipe->inventoryItem->average_price ?? 0);
        }
        return $total;
    }
}
