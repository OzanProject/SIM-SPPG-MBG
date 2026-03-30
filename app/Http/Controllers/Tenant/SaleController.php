<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Account;
use App\Models\Tenant\Journal;
use App\Models\Tenant\JournalDetail;
use App\Models\Tenant\Menu;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleDetail;
use App\Models\Tenant\InventoryItem;
use App\Models\Tenant\InventoryMovement;
use App\Notifications\LowStockAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')->latest()->get();
        return view('tenant.sales.index', compact('sales'));
    }

    public function create()
    {
        $menus = Menu::where('is_available', true)->get();
        return view('tenant.sales.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'payment_method' => 'required|in:cash,transfer,qris,lainnya',
            'items'          => 'required|array|min:1',
            'items.*.menu_id'=> 'required|exists:menus,id',
            'items.*.qty'    => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $itemsData = [];

            // 1. Hitung total dan siapkan data detail
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $subtotal = $menu->price * $item['qty'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'menu_id'   => $menu->id,
                    'menu_name' => $menu->name,
                    'quantity'  => $item['qty'],
                    'price'     => $menu->price,
                    'subtotal'  => $subtotal,
                ];
            }

            // 2. Simpan Header Penjualan
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'date'           => $request->date,
                'customer_name'  => $request->customer_name,
                'payment_method' => $request->payment_method,
                'subtotal'       => $totalAmount,
                'total_amount'   => $totalAmount,
                'user_id'        => Auth::id(),
            ]);

            // 3. Simpan Detail Penjualan
            foreach ($itemsData as $detail) {
                $sale->details()->create($detail);
            }

            // 4. AUTO-DEDUCT INVENTORY & Calculate HPP
            $this->processInventoryDeductions($sale);

            // 5. AUTO-JOURNAL (Integrasi Akuntansi)
            $this->createJournalEntry($sale);

            DB::commit();

            return redirect()->route('tenant.sales.index', tenant('id'))
                ->with('success', 'Penjualan #' . $sale->invoice_number . ' berhasil dicatat dan disinkronkan ke Akuntansi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['details', 'user']);
        return view('tenant.sales.show', compact('sale'));
    }

    /**
     * Logika Auto-Journal untuk Penjualan
     */
    private function createJournalEntry(Sale $sale)
    {
        // Cari Akun Kas/Bank (Asset)
        $assetAccount = Account::where('type', 'asset')
            ->where('name', 'like', '%Kas%')
            ->first();

        // Cari Akun Pendapatan Penjualan (Revenue)
        $revenueAccount = Account::where('type', 'revenue')
            ->where('name', 'like', '%Penjualan%')
            ->first();

        if (!$assetAccount || !$revenueAccount) {
            // Jika akun belum ada, buat atau lemparkan exception
            // Untuk keamanan, kita coba cari default jika pencarian di atas gagal
            $assetAccount = Account::where('type', 'asset')->first();
            $revenueAccount = Account::where('type', 'revenue')->first();
            
            if (!$assetAccount || !$revenueAccount) {
                throw new \Exception("Akun Kas atau Pendapatan belum dikonfigurasi di Chart of Accounts.");
            }
        }

        $journal = Journal::create([
            'reference_number' => 'JRN-SALE-' . $sale->invoice_number,
            'date'             => $sale->date,
            'description'      => 'Penjualan #' . $sale->invoice_number . ' (' . ($sale->customer_name ?? 'Pelanggan Umum') . ')',
            'total_amount'     => $sale->total_amount,
            'source_module'    => 'Sales',
            'created_by'       => Auth::id(),
        ]);

        // Debit: Kas/Bank (Asset bertambah)
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $assetAccount->id,
            'debit'      => $sale->total_amount,
            'credit'     => 0,
            'description'=> 'Penerimaan pembayaran ' . $sale->payment_method,
        ]);

        // Credit: Pendapatan Penjualan (Revenue bertambah)
        JournalDetail::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'debit'      => 0,
            'credit'     => $sale->total_amount,
            'description'=> 'Penjualan menu harian',
        ]);

        // Simpan ID jurnal ke record penjualan
        $sale->update(['journal_id' => $journal->id]);

        // --- TAMBAHAN BAGIAN HPP (COGS) & PERSEDIAAN ---
        // Jika ada HPP yang tercatat di penjualan ini, buat jurnal tambahannya
        if ($sale->total_hpp > 0) {
            // Cari Akun HPP (Expense)
            $hppAccount = Account::where('type', 'expense')
                ->where('name', 'like', '%HPP%')
                ->first() ?: Account::where('type', 'expense')->first();

            // Cari Akun Persediaan (Asset)
            $inventoryAccount = Account::where('type', 'asset')
                ->where('name', 'like', '%Persediaan%')
                ->first() ?: Account::where('type', 'asset')->first();

            if ($hppAccount && $inventoryAccount) {
                // Tambahkan detail jurnal untuk HPP
                // Debit: HPP (Beban bertambah)
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $hppAccount->id,
                    'debit'      => $sale->total_hpp,
                    'credit'     => 0,
                    'description'=> 'HPP atas penjualan #' . $sale->invoice_number,
                ]);

                // Credit: Persediaan (Asset berkurang)
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $inventoryAccount->id,
                    'debit'      => 0,
                    'credit'     => $sale->total_hpp,
                    'description'=> 'Pengurangan stok bahan otomatis',
                ]);
            }
        }
    }

    /**
     * Kurangi stok inventaris berdasarkan resep menu yang dijual
     */
    private function processInventoryDeductions(Sale $sale)
    {
        $totalHpp = 0;

        foreach ($sale->details as $detail) {
            $menu = $detail->menu;
            if (!$menu) continue;

            // Ambil semua resep/bahan baku untuk menu ini
            $recipes = $menu->recipes()->with('inventoryItem')->get();

            foreach ($recipes as $recipe) {
                $item = $recipe->inventoryItem;
                if (!$item) continue;

                $quantityToDeduct = $recipe->quantity * $detail->quantity;
                $costPerRecipe = $quantityToDeduct * ($item->average_price ?? 0);
                $totalHpp += $costPerRecipe;

                // 1. Kurangi stok fisik
                $item->decrement('stock', $quantityToDeduct);

                // Check Low Stock Notification
                if ($item->stock <= $item->minimum_stock) {
                    // Beritahu user yang sedang login (tenant admin/staff)
                    Auth::user()->notify(new LowStockAlert($item));
                }

                // 2. Catat pergerakan stok (Movement)
                InventoryMovement::create([
                    'inventory_item_id' => $item->id,
                    'type'              => 'out',
                    'quantity'          => $quantityToDeduct,
                    'unit_price'        => $item->average_price ?? 0,
                    'date'              => $sale->date,
                    'reference_number'  => 'SALE-' . $sale->invoice_number,
                    'notes'             => 'Deduksi otomatis penjualan: ' . $menu->name,
                    'created_by'        => Auth::id(),
                ]);
            }
        }

        // Simpan total HPP ke record penjualan (opsional namun baik untuk audit)
        // Kita gunakan kolom attributes jika tidak ada kolom db-nya, tapi sebaiknya di simpan
        // Karena migration sale tidak punya total_hpp, kita lewati pembaruan baris ini
        // Namun kita kirim totalHpp ke jurnal.
        $sale->total_hpp = $totalHpp; 
    }
}
