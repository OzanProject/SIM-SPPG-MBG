<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'type',
        'target_plan',
        'is_active',
        'show_modal',
        'is_persistent',
        'expires_at',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'show_modal'    => 'boolean',
        'is_persistent' => 'boolean',
        'expires_at'    => 'datetime',
    ];

    /**
     * Scope untuk pengumuman aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    /**
     * Badge HTML untuk tipe pengumuman.
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'success' => '<span class="badge badge-success">Success</span>',
            'warning' => '<span class="badge badge-warning">Warning</span>',
            'danger'  => '<span class="badge badge-danger">Danger</span>',
            default   => '<span class="badge badge-info">Info</span>',
        };
    }
}
