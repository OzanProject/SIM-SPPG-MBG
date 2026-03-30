<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PurchaseOrder;
use App\Models\Tenant\PurchaseOrderItem;
use App\Models\Tenant\Supplier;
use App\Models\Tenant\InventoryItem;
use App\Models\Tenant\Account;
use App\Models\Tenant\Journal;
use App\Models\Tenant\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $pos = PurchaseOrder::with('supplier')->latest()->get();
        return view('tenant.procurement.pos.index', compact('pos'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $items = InventoryItem::orderBy('name')->get();
        return view('tenant.procurement.pos.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'supplier_id' => $validated['supplier_id'],
                'date' => $validated['date'],
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'notes' => $validated['notes'],
                'status' => 'pending_approval',
                'created_by' => auth()->id(),
            ]);

            $totalAmount = 0;
            foreach ($validated['items'] as $itemData) {
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                $totalAmount += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'inventory_item_id' => $itemData['inventory_item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $po->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('procurement.pos.index', tenant('id'))
            ->with('success', 'Purchase Order berhasil dibuat dan menunggu persetujuan.');
    }

    public function show(PurchaseOrder $po)
    {
        $po->load(['supplier', 'items.inventoryItem']);
        return view('tenant.procurement.pos.show', compact('po'));
    }

    public function updateStatus(Request $request, PurchaseOrder $po)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,received,cancelled',
        ]);

        DB::transaction(function () use ($validated, $po) {
            $oldStatus = $po->status;
            $newStatus = $validated['status'];

            if ($newStatus === 'received' && $oldStatus !== 'received') {
                $this->handleReceiving($po);
            }

            $po->update([
                'status' => $newStatus,
                'approved_by' => in_array($newStatus, ['approved', 'received']) ? auth()->id() : $po->approved_by,
            ]);
        });

        return redirect()->back()->with('success', 'Status PO berhasil diperbarui.');
    }

    protected function handleReceiving(PurchaseOrder $po)
    {
        // 1. Update Inventory Stock & Average Price
        foreach ($po->items as $item) {
            $inventoryItem = $item->inventoryItem;
            
            $currentTotalValue = $inventoryItem->stock * $inventoryItem->average_price;
            $receivedValue = $item->quantity * $item->unit_price;
            
            $inventoryItem->stock += $item->quantity;
            if ($inventoryItem->stock > 0) {
                $inventoryItem->average_price = ($currentTotalValue + $receivedValue) / $inventoryItem->stock;
            }
            $inventoryItem->save();

            // Mark items as received
            $item->update(['received_quantity' => $item->quantity]);
        }

        // 2. Create Auto-Journal (Persediaan D vs Hutang K)
        $inventoryAccount = Account::where('code', '1201')->first(); // Persediaan
        $payablesAccount = Account::where('code', '2101')->first();  // Hutang Dagang

        if ($inventoryAccount && $payablesAccount) {
            $journal = Journal::create([
                'reference_number' => 'JRN-' . $po->po_number,
                'date' => now()->format('Y-m-d'),
                'description' => 'Penerimaan Barang PO: ' . $po->po_number,
                'total_amount' => $po->total_amount,
                'source_module' => 'procurement_received',
                'created_by' => auth()->id(),
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $po->total_amount,
                'credit' => 0,
                'description' => 'Penerimaan Persediaan dari PO ' . $po->po_number,
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $payablesAccount->id,
                'debit' => 0,
                'credit' => $po->total_amount,
                'description' => 'Timbul Hutang Dagang PO ' . $po->po_number,
            ]);
        }
    }
}
