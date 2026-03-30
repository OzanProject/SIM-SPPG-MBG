<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'tenant_id',
        'subscription_plan_id',
        'promo_code_id',
        'base_amount',
        'discount_amount',
        'final_amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];

    // ── Relasi ──────────────────────────────

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id', 'id');
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    // ── Helper Attributes ────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid'      => '<span class="badge badge-success px-2">Lunas</span>',
            'pending'   => '<span class="badge badge-warning px-2">Menunggu</span>',
            'expired'   => '<span class="badge badge-danger px-2">Kadaluarsa</span>',
            'cancelled' => '<span class="badge badge-secondary px-2">Dibatalkan</span>',
            default     => '<span class="badge badge-light px-2">' . $this->status . '</span>',
        };
    }

    // Generate nomor invoice unik
    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
