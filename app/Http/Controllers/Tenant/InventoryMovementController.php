<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\InventoryItem;
use App\Models\Tenant\InventoryMovement;
use App\Models\Tenant\Journal;
use App\Models\Tenant\JournalDetail;
use App\Models\Tenant\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $movements = InventoryMovement::with('item')->latest()->get();
        $items = InventoryItem::orderBy('name')->get();
        return view('tenant.inventory.movements.index', compact('movements', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $item = InventoryItem::findOrFail($validated['inventory_item_id']);
            
            // Create movement record
            $movement = InventoryMovement::create([
                'inventory_item_id' => $validated['inventory_item_id'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'date' => $validated['date'],
                'notes' => $validated['notes'],
                'reference_number' => strtoupper($validated['type']) . '-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'created_by' => auth()->id(),
            ]);

            // Update item stock and average price
            if ($validated['type'] === 'in') {
                $currentTotalValue = $item->stock * $item->average_price;
                $newTotalValue = $validated['quantity'] * $validated['unit_price'];
                
                $item->stock += $validated['quantity'];
                
                // Avoid division by zero
                if ($item->stock > 0) {
                    $item->average_price = ($currentTotalValue + $newTotalValue) / $item->stock;
                } else {
                    $item->average_price = $validated['unit_price'];
                }
            } else {
                $item->stock -= $validated['quantity'];
            }
            $item->save();

            // Auto-Journaling
            $this->createAutoJournal($movement, $item);
        });

        return redirect()->route('inventory.movements.index', tenant('id'))->with('success', 'Pergerakan stok berhasil dicatat dan jurnal otomatis telah dibuat.');
    }

    protected function createAutoJournal($movement, $item)
    {
        $totalAmount = $movement->quantity * $movement->unit_price;

        // Cari akun yang dibutuhkan
        $inventoryAccount = Account::where('code', '1201')->first(); // Persediaan
        $cashAccount = Account::where('code', '1101')->first();      // Kas
        $hppAccount = Account::where('code', '5101')->first();       // HPP

        if (!$inventoryAccount || !$cashAccount || !$hppAccount) {
            return; // Skip jika akun belum siap
        }

        $journal = Journal::create([
            'reference_number' => 'JRN-' . $movement->reference_number,
            'date' => $movement->date,
            'description' => ($movement->type === 'in' ? 'Otomatis - Barang Masuk: ' : 'Otomatis - Barang Keluar: ') . $item->name,
            'total_amount' => $totalAmount,
            'source_module' => 'inventory_' . $movement->type,
            'created_by' => auth()->id(),
        ]);

        if ($movement->type === 'in') {
            // Barang Masuk: Debit Persediaan, Credit Kas
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $totalAmount,
                'credit' => 0,
                'description' => 'Persediaan Bertambah',
            ]);
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
                'description' => 'Pengeluaran Kas',
            ]);
        } else {
            // Barang Keluar: Debit HPP, Credit Persediaan
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $hppAccount->id,
                'debit' => $totalAmount,
                'credit' => 0,
                'description' => 'Beban Pokok Penjualan',
            ]);
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $inventoryAccount->id,
                'debit' => 0,
                'credit' => $totalAmount,
                'description' => 'Persediaan Berkurang',
            ]);
        }
    }
}
