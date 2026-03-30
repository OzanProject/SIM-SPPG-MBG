<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\SubscriptionPlan;
use App\Models\PromoCode;

/**
 * InvoiceService — Layanan generasi invoice otomatis.
 *
 * Cara pakai di controller mana saja:
 *
 *   use App\Services\InvoiceService;
 *
 *   // Saat tenant beli paket:
 *   $invoice = InvoiceService::createForTenant(
 *       tenantId: $tenant->id,
 *       planId: $request->plan_id,
 *       promoCode: $request->promo_code,   // opsional
 *       dueDays: 3,                         // opsional, default 7 hari
 *       notes: 'Pembelian dari dashboard tenant'
 *   );
 */
class InvoiceService
{
    /**
     * Buat invoice secara otomatis untuk tenant yang membeli/perpanjang paket.
     *
     * @param  string       $tenantId   ID tenant (string, sesuai stancl/tenancy)
     * @param  int          $planId     ID subscription plan
     * @param  string|null  $promoCode  Kode promo (opsional)
     * @param  int          $dueDays    Jumlah hari sampai jatuh tempo (default: 7)
     * @param  string|null  $notes      Catatan tambahan
     * @return Invoice
     */
    public static function createForTenant(
        string $tenantId,
        int    $planId,
        ?string $promoCode = null,
        int    $dueDays = 7,
        ?string $notes = null
    ): Invoice {
        $plan = SubscriptionPlan::findOrFail($planId);

        $baseAmount   = $plan->price;
        $discount     = 0;
        $promoCodeId  = null;

        // Hitung diskon jika ada kode promo
        if ($promoCode) {
            $promo = PromoCode::where('code', strtoupper($promoCode))
                ->where('is_active', true)
                ->whereDate('starts_at', '<=', now())
                ->whereDate('ends_at', '>=', now())
                ->first();

            if ($promo && $promo->isValid()) {
                // Cek apakah promo berlaku untuk paket ini
                $applicableToThisPlan = $promo->subscriptionPlans->isEmpty()
                    || $promo->subscriptionPlans->contains('id', $planId);

                if ($applicableToThisPlan) {
                    $discount = $promo->type === 'percent'
                        ? round($baseAmount * $promo->value / 100, 2)
                        : (float) $promo->value;

                    $promo->increment('used_count');
                    $promoCodeId = $promo->id;
                }
            }
        }

        $invoice = Invoice::create([
            'invoice_number'       => Invoice::generateNumber(),
            'tenant_id'            => $tenantId,
            'subscription_plan_id' => $planId,
            'promo_code_id'        => $promoCodeId,
            'base_amount'          => $baseAmount,
            'discount_amount'      => $discount,
            'final_amount'         => max(0, $baseAmount - $discount),
            'status'               => 'pending',
            'due_date'             => now()->addDays($dueDays),
            'notes'                => $notes ?? "Pembelian paket {$plan->name} oleh tenant {$tenantId}",
        ]);

        return $invoice;
    }

    /**
     * Tandai invoice sebagai Lunas sekaligus.
     * Cocok jika sistem pembayaran sudah terhubung ke payment gateway.
     *
     * @param  Invoice      $invoice
     * @param  string|null  $paymentMethod
     * @return Invoice
     */
    public static function markAsPaid(Invoice $invoice, ?string $paymentMethod = null): Invoice
    {
        $invoice->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_method' => $paymentMethod,
        ]);

        return $invoice;
    }

    /**
     * Expire semua invoice pending yang sudah lewat jatuh tempo.
     * Cocok dipanggil dari Scheduler (artisan schedule:run).
     *
     * Tambahkan di app/Console/Kernel.php:
     *   $schedule->call(fn() => InvoiceService::expireOverdue())->daily();
     *
     * @return int Jumlah invoice yang di-expire
     */
    public static function expireOverdue(): int
    {
        return Invoice::where('status', 'pending')
            ->whereDate('due_date', '<', now())
            ->update(['status' => 'expired']);
    }
}
