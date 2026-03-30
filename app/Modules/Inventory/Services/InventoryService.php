<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Accounting\Services\JournalService;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    protected $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function getAllItems()
    {
        return InventoryItem::orderBy('name')->get();
    }

    public function receiveItem($itemId, $quantity, $price, $reference)
    {
        DB::beginTransaction();
        try {
            $item = InventoryItem::findOrFail($itemId);
            
            // Calculate new average price
            $totalValue = ($item->stock * $item->average_price) + ($quantity * $price);
            $newStock = $item->stock + $quantity;
            $newAvgPrice = $newStock > 0 ? $totalValue / $newStock : 0;

            $item->update([
                'stock' => $newStock,
                'average_price' => $newAvgPrice
            ]);

            $item->movements()->create([
                'type' => 'in',
                'quantity' => $quantity,
                'unit_price' => $price,
                'date' => now(),
                'reference_number' => $reference,
                'created_by' => auth()->id()
            ]);

            // Auto Journal (Barang Masuk)
            $this->journalService->recordAutoJournal(
                'barang_masuk',
                $quantity * $price,
                $reference,
                "Penerimaan Barang: {$item->name} ($quantity $item->unit)",
                auth()->id()
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
