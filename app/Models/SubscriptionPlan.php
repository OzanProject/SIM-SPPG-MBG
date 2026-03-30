<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory, \App\Traits\Auditable;

    /**
     * Pastikan model ini selalu menggunakan koneksi pusat.
     */
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection', 'mysql');
    }
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_in_days',
        'is_active',
        'max_users',
        'max_transactions_per_month',
        'max_items',
        'has_sales',
        'has_inventory',
        'has_accounting_full',
        'has_budgeting',
        'has_procurement',
        'has_hr',
        'has_notifications',
        'has_circle_menu',
        'can_export',
        'badge_label',
        'is_highlighted',
    ];

    /**
     * Relasi ke model Tenant.
     * Satu paket langganan bisa digunakan oleh banyak tenant.
     */
    public function tenants()
    {
        return $this->hasMany(\App\Models\Tenant::class, 'plan_id', 'id');
    }
}
