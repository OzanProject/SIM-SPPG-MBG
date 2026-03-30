<?php

namespace App\Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'date', 'expected_delivery_date', 'status', 'total_amount', 'notes', 'created_by', 'approved_by'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
