<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // ─── DOWNLOAD PDF ────────────────────────────────────────
    public function download(Invoice $billing)
    {
        $billing->load(['tenant.domains', 'subscriptionPlan', 'promoCode']);
        
        $terbilang = $this->terbilang($billing->final_amount);
        $appConfig = \App\Models\AppConfig::all();
        
        $pdf = Pdf::loadView('central.billing.pdf-invoice', [
            'invoice'   => $billing,
            'terbilang' => $terbilang,
            'appConfig' => $appConfig,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $billing->invoice_number . '.pdf');
    }

    private function terbilang($number)
    {
        $number = abs($number);
        $words = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";
        if ($number < 12) { $temp = " " . $words[$number]; }
        elseif ($number < 20) { $temp = $this->terbilang($number - 10) . " Belas"; }
        elseif ($number < 100) { $temp = $this->terbilang($number / 10) . " Puluh" . $this->terbilang($number % 10); }
        elseif ($number < 200) { $temp = " Seratus" . $this->terbilang($number - 100); }
        elseif ($number < 1000) { $temp = $this->terbilang($number / 100) . " Ratus" . $this->terbilang($number % 100); }
        elseif ($number < 2000) { $temp = " Seribu" . $this->terbilang($number - 1000); }
        elseif ($number < 1000000) { $temp = $this->terbilang($number / 1000) . " Ribu" . $this->terbilang($number % 1000); }
        elseif ($number < 1000000000) { $temp = $this->terbilang($number / 1000000) . " Juta" . $this->terbilang($number % 1000000); }
        return $temp;
    }

    // ─── INDEX ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Invoice::with(['tenant', 'subscriptionPlan', 'promoCode'])
            ->orderBy('created_at', 'desc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tenant
        if ($request->filled('tenant')) {
            $query->where('tenant_id', $request->tenant);
        }

        // Filter bulan
        if ($request->filled('month')) {
            $query->whereMonth('created_at', now()->parse($request->month)->month)
                  ->whereYear('created_at', now()->parse($request->month)->year);
        }

        $invoices = $query->paginate(15)->withQueryString();

        // Statistik ringkasan
        $stats = [
            'total'     => Invoice::count(),
            'pending'   => Invoice::where('status', 'pending')->count(),
            'paid'      => Invoice::where('status', 'paid')->count(),
            'revenue'   => Invoice::where('status', 'paid')->sum('final_amount'),
            'expired'   => Invoice::where('status', 'expired')->count(),
        ];

        $tenants = Tenant::all();

        return view('super-admin.billing.index', compact('invoices', 'stats', 'tenants'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        $tenants = Tenant::with('domains')->get();
        $plans   = SubscriptionPlan::where('is_active', true)->get();
        $promos  = PromoCode::where('is_active', true)
                    ->whereDate('ends_at', '>=', now())
                    ->get();

        return view('super-admin.billing.create', compact('tenants', 'plans', 'promos'));
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id'            => 'required|string|exists:tenants,id',
            'subscription_plan_id' => 'required|integer|exists:subscription_plans,id',
            'promo_code_id'        => 'nullable|integer|exists:promo_codes,id',
            'due_date'             => 'required|date|after_or_equal:today',
            'notes'                => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);
        $baseAmount = $plan->price;
        $discount   = 0;

        // Hitung diskon dari promo
        if ($request->filled('promo_code_id')) {
            $promo = PromoCode::find($request->promo_code_id);
            if ($promo && $promo->isValid()) {
                $discount = $promo->type === 'percent'
                    ? round($baseAmount * $promo->value / 100, 2)
                    : $promo->value;
                // Naikkan used_count
                $promo->increment('used_count');
            }
        }

        Invoice::create([
            'invoice_number'       => Invoice::generateNumber(),
            'tenant_id'            => $request->tenant_id,
            'subscription_plan_id' => $request->subscription_plan_id,
            'promo_code_id'        => $request->promo_code_id ?: null,
            'base_amount'          => $baseAmount,
            'discount_amount'      => $discount,
            'final_amount'         => max(0, $baseAmount - $discount),
            'status'               => 'pending',
            'due_date'             => $request->due_date,
            'notes'                => $request->notes,
        ]);

        return redirect('/super-admin/billing')->with('success', 'Invoice berhasil diterbitkan.');
    }

    // ─── SHOW ─────────────────────────────────────────────────
    public function show(Invoice $billing)
    {
        $billing->load(['tenant', 'subscriptionPlan', 'promoCode']);
        return view('super-admin.billing.show', compact('billing'));
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Invoice $billing)
    {
        $tenants = Tenant::with('domains')->get();
        $plans   = SubscriptionPlan::where('is_active', true)->get();
        $promos  = PromoCode::where('is_active', true)->get();
        $billing->load(['tenant', 'subscriptionPlan', 'promoCode']);
        return view('super-admin.billing.edit', compact('billing', 'tenants', 'plans', 'promos'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(Request $request, Invoice $billing)
    {
        $request->validate([
            'status'           => 'required|in:pending,paid,expired,cancelled',
            'due_date'         => 'required|date',
            'payment_method'   => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
        ]);

        $data = [
            'status'           => $request->status,
            'due_date'         => $request->due_date,
            'payment_method'   => $request->payment_method,
            'notes'            => $request->notes,
        ];

        // Jika di-set paid dan belum ada paid_at
        if ($request->status === 'paid' && !$billing->paid_at) {
            $data['paid_at'] = now();
            // Sync subscription to tenant
            $this->syncTenantSubscription($billing);
        } elseif ($request->status !== 'paid') {
            $data['paid_at'] = null;
        }

        // Upload bukti bayar
        if ($request->hasFile('payment_proof') && $request->file('payment_proof')->isValid()) {
            // Hapus file lama jika ada
            if ($billing->payment_proof) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $billing->payment_proof));
            }
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            $data['payment_proof'] = '/storage/' . $path;
        }

        $billing->update($data);

        return redirect('/super-admin/billing/' . $billing->id)->with('success', 'Invoice berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Invoice $billing)
    {
        if ($billing->payment_proof) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $billing->payment_proof));
        }
        $billing->delete();
        return redirect('/super-admin/billing')->with('success', 'Invoice berhasil dihapus.');
    }

    // ─── MARK PAID (Quick Action) ─────────────────────────────
    public function markPaid(Invoice $billing)
    {
        $billing->load('subscriptionPlan');
        
        $billing->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
        
        $this->syncTenantSubscription($billing);
        
        return redirect()->back()->with('success', "Invoice #{$billing->invoice_number} telah ditandai Lunas dan Paket Berhasil Diperpanjang.");
    }

    // ─── HELPER METHOD ────────────────────────────────────────
    private function syncTenantSubscription(Invoice $invoice)
    {
        $tenant = Tenant::find($invoice->tenant_id);
        if (!$tenant) return;
        
        // Load relation jika belum
        $plan = $invoice->relationLoaded('subscriptionPlan')
            ? $invoice->subscriptionPlan
            : $invoice->subscriptionPlan()->first();

        if (!$plan) return;

        // Hitung tanggal kadaluarsa baru (perpanjangan)
        $currentEndsAt = $tenant->subscription_ends_at
            ? \Carbon\Carbon::parse($tenant->subscription_ends_at)
            : now();
        $baseDate  = $currentEndsAt->isFuture() ? $currentEndsAt : now();
        $newEndsAt = $baseDate->addDays((int) $plan->duration_in_days);

        // Update kolom tenant yang ada di tabel
        $tenant->plan_id              = $plan->id;
        $tenant->plan_slug            = $plan->slug ?? null;
        $tenant->subscription_ends_at = $newEndsAt->toDateTimeString();
        $tenant->max_members          = $plan->max_users ?? 0;
        $tenant->max_users            = $plan->max_users ?? 0;
        $tenant->trial_ends_at        = null; // Hapus trial saat berlangganan

        // Simpan ke kolom `data` JSON (Stancl Tenancy internal) jika method tersedia
        if (method_exists($tenant, 'setInternal')) {
            try {
                $tenant->setInternal('plan_id',   $plan->id);
                $tenant->setInternal('plan_slug',  $plan->slug ?? '');
            } catch (\Throwable $e) {
                // Abaikan jika setInternal tidak tersedia di konteks ini
                \Illuminate\Support\Facades\Log::warning('syncTenantSubscription: setInternal failed - ' . $e->getMessage());
            }
        }

        $tenant->save();
    }
}
