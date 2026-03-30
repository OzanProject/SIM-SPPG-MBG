<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'landing_features';

    protected $fillable = [
        'icon',
        'icon_type',
        'color_class',
        'title',
        'description',
        'size',
        'badge_text',
        'is_active',
        'order_priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_priority' => 'integer',
    ];

    /**
     * Scope: only active features, ordered.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_priority');
    }

    /**
     * Return Tailwind color config based on color_class.
     */
    public function getColorConfigAttribute(): array
    {
        return match($this->color_class) {
            'purple'  => ['bg' => 'bg-purple-500/10',  'text' => 'text-purple-400',  'border' => 'border-purple-500/10'],
            'blue'    => ['bg' => 'bg-blue-500/10',    'text' => 'text-blue-400',    'border' => 'border-blue-500/10'],
            'emerald' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/10'],
            'amber'   => ['bg' => 'bg-amber-500/10',   'text' => 'text-amber-400',   'border' => 'border-amber-500/10'],
            'rose'    => ['bg' => 'bg-rose-500/10',    'text' => 'text-rose-400',    'border' => 'border-rose-500/10'],
            default   => ['bg' => 'bg-indigo-500/10',  'text' => 'text-indigo-400',  'border' => 'border-indigo-500/10'],
        };
    }
}
