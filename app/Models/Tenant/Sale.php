<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'date',
        'customer_name',
        'payment_method',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'user_id',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    /**
     * Generate Nomor Invoice otomatis (Format: INV-YMD-XXXX)
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $lastSale = self::where('invoice_number', 'like', $prefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();

        if (!$lastSale) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($lastSale->invoice_number, -4);
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Dapatkan format harga Rupiah untuk total amount
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}
