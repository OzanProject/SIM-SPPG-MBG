<?php

namespace App\Modules\Procurement\Services;

use App\Modules\Procurement\Models\PurchaseOrder;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcurementService
{
    public function getAllPurchaseOrders()
    {
        return PurchaseOrder::with(['supplier', 'creator', 'approver'])->orderBy('date', 'desc')->get();
    }

    public function createPurchaseOrder(array $data, array $items)
    {
        DB::beginTransaction();
        try {
            $totalAmount = collect($items)->sum('subtotal');
            $data['total_amount'] = $totalAmount;
            
            $po = PurchaseOrder::create($data);

            foreach ($items as $item) {
                $po->items()->create($item);
            }

            DB::commit();
            return $po;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
