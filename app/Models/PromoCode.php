<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory, \App\Traits\Auditable;
    
    protected $connection = 'central';

    protected $fillable = [
        'code',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'max_uses',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke SubscriptionPlan (Many-to-Many)
     * Kosong = berlaku untuk SEMUA paket
     */
    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'promo_code_subscription_plan');
    }

    /**
     * Sisa kuota penggunaan
     */
    public function getRemainingUsesAttribute(): int
    {
        return max(0, $this->max_uses - $this->used_count);
    }

    /**
     * Apakah promo masih bisa dipakai?
     */
    public function isValid(): bool
    {
        return $this->is_active
            && now()->between($this->starts_at, $this->ends_at)
            && $this->remaining_uses > 0;
    }
}
