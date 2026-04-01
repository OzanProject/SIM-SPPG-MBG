<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SubscriptionPlan;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    public function downloadInvoice($id)
    {
        $tenantId = tenant('id');
        $data = tenancy()->central(function () use ($id, $tenantId) {
            $inv = Invoice::with(['tenant.domains', 'subscriptionPlan', 'promoCode'])
                ->where('id', $id)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
            
            $appConfig = \App\Models\AppConfig::all();

            return [
                'invoice'   => $inv,
                'terbilang' => $this->terbilang($inv->final_amount),
                'appConfig' => $appConfig,
            ];
        });

        $pdf = Pdf::loadView('central.billing.pdf-invoice', $data)->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $data['invoice']->invoice_number . '.pdf');
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

    public function index()
    {
        $tenant = tenant();
        $plan = $tenant->plan;
        $activePlanName = $plan ? $plan->name : 'Free Trial / Expired';
        
        if ($tenant->is_on_trial) {
            $activePlanName = "FREE TRIAL PRO (Active)";
        }

        // Get available plans from Central DB
        $plans = tenancy()->central(function () {
            return SubscriptionPlan::where('is_active', true)->get();
        });

        // Get past invoices for this tenant from Central DB
        $tenantId = tenant('id');
        $invoices = tenancy()->central(function () use ($tenantId) {
            return Invoice::where('tenant_id', $tenantId)->orderBy('created_at', 'desc')->get();
        });

        return view('tenant.billing.index', compact('plans', 'invoices', 'activePlanName', 'tenant'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'promo_code' => 'nullable|string'
        ]);

        $tenantId = tenant('id');
        $result = tenancy()->central(function () use ($request, $tenantId) {
            $plan = SubscriptionPlan::findOrFail($request->plan_id);
            $baseAmount = $plan->price;
            $discountAmount = 0;
            $promoId = null;

            if ($request->filled('promo_code')) {
                $promo = PromoCode::where('code', $request->promo_code)
                    ->where('is_active', true)
                    ->first();

                // If promo has valid_until column (might not be defined), skip it or check if it exists:
                // Assuming `is_active` means valid.
                if ($promo) {
                    $promoId = $promo->id;
                    if ($promo->type == 'percentage') {
                        $discountAmount = ($baseAmount * $promo->value) / 100;
                    } else {
                        $discountAmount = $promo->value;
                    }
                } else {
                    return ['error' => 'Kode promo tidak valid atau kadaluarsa.'];
                }
            }

            $finalAmount = max(0, $baseAmount - $discountAmount);

            // Generate Invoice Number INV-YYYYMMDD-XXXX
            $invNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $status = 'pending';
            $paidAt = null;

            // Jika harga akhir 0 (misal: Paket Gratis / Diskon 100%)
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($finalAmount <= 0 && $tenant) {
                $status = 'paid';
                $paidAt = now();
                
                // Langsung perpanjang / aktivasi paket tanpa menunggu pembayaran
                $currentEndsAt = $tenant->subscription_ends_at ? \Carbon\Carbon::parse($tenant->subscription_ends_at) : now();
                $baseDate = $currentEndsAt->isFuture() ? $currentEndsAt : now();
                $newEndsAt = $baseDate->addDays($plan->duration_in_days);

                $tenant->plan_id = $plan->id;
                $tenant->plan_slug = $plan->slug;
                $tenant->subscription_ends_at = $newEndsAt->toDateTimeString();
                $tenant->trial_ends_at = null; // Trial ends once they pay for a plan
                $tenant->max_members = $plan->max_users;
                $tenant->save();
            }

            $invoice = Invoice::create([
                'invoice_number' => $invNumber,
                'tenant_id' => $tenantId,
                'subscription_plan_id' => $plan->id,
                'promo_code_id' => $promoId,
                'base_amount' => $baseAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => $status,
                'paid_at' => $paidAt,
                'due_date' => now()->addDays(2),
            ]);

            return ['invoice_id' => $invoice->id, 'status' => $status];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        if ($result['status'] === 'paid') {
            return redirect()->route('tenant.billing.index')->with('success', 'Paket berhasil diaktifkan secara otomatis (Gratis)!');
        }

        return redirect()->route('tenant.billing.invoice.show', $result['invoice_id'])->with('success', 'Tagihan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function showInvoice($id)
    {
        $tenantId = tenant('id');
        $data = tenancy()->central(function () use ($id, $tenantId) {
            $inv = Invoice::with(['subscriptionPlan', 'promoCode'])
                ->where('id', $id)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
            
            // Fetch dynamic payment methods (Bank accounts)
            $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();
            
            return [
                'invoice' => $inv,
                'paymentMethods' => $paymentMethods
            ];
        });

        $invoice = $data['invoice'];
        $paymentMethods = $data['paymentMethods'];

        return view('tenant.billing.show', compact('invoice', 'paymentMethods'));
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048'
        ]);

        $tenantId = tenant('id');
        $result = tenancy()->central(function () use ($request, $id, $tenantId) {
            $invoice = Invoice::where('id', $id)->where('tenant_id', $tenantId)->firstOrFail();
            
            if ($request->hasFile('payment_proof')) {
                // Ensure config has 'central' disk
                $path = $request->file('payment_proof')->store('payment-proofs', 'central');
                $invoice->update(['payment_proof' => $path]);
                return ['success' => 'Bukti bayar berhasil diunggah. Menunggu konfirmasi admin pusat.'];
            }

            return ['error' => 'Gagal mengunggah bukti.'];
        });

        if (isset($result['success'])) {
            return back()->with('success', $result['success']);
        }

        return back()->with('error', $result['error']);
    }
}
