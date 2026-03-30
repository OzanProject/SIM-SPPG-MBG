<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use App\Models\SubscriptionPlan;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, \App\Traits\Auditable;
    
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    /**
     * Internal blocklisted slugs to prevent route collisions.
     */
    protected static array $internalBlocklistedSlugs = [
        'admin', 'super-admin', 'central', 'login', 'register', 'dashboard', 'billing', 'profile', 'api', 'assets'
    ];

    public static function booted()
    {
        static::creating(function ($tenant) {
            // Ensure ID (slug) is always lowercase
            if (isset($tenant->id)) {
                $tenant->id = strtolower($tenant->id);
            }

            // Prevent creation with blocklisted slugs
            if (in_array($tenant->id, static::$internalBlocklistedSlugs)) {
                throw new \Exception("ID / Slug '{$tenant->id}' tidak diperbolehkan karena merupakan reservasi sistem.");
            }
        });

        static::updating(function ($tenant) {
            if ($tenant->isDirty('id')) {
                $tenant->id = strtolower($tenant->id);
                if (in_array($tenant->id, static::$internalBlocklistedSlugs)) {
                    throw new \Exception("The slug '{$tenant->id}' is reserved and cannot be used.");
                }
            }
        });
    }

    /**
     * Get the active plan slug (considering trial).
     */
    public function getActivePlanSlugAttribute(): string
    {
        if ($this->is_on_trial) {
            return 'pro';
        }
        return $this->plan_slug ?? 'free';
    }

    /**
     * Check if tenant is on trial.
     */
    public function getIsOnTrialAttribute(): bool
    {
        return $this->trial_ends_at && \Illuminate\Support\Carbon::now()->lessThan($this->trial_ends_at);
    }

    /**
     * Get days left in trial.
     */
    public function getTrialDaysLeftAttribute(): int
    {
        if (!$this->is_on_trial) return 0;
        return (int) \Illuminate\Support\Carbon::now()->diffInDays($this->trial_ends_at, false);
    }

    /**
     * Get the subscription plan for this tenant.
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Check if a specific feature is enabled for this tenant.
     */
    public function isFeatureEnabled(string $feature): bool
    {
        $plan = $this->plan;
        if (!$plan) return false;

        $column = "has_{$feature}";
        return (bool) ($plan->$column ?? false);
    }
}
