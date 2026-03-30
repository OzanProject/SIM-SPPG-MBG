<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleMenu extends Model
{
    use HasFactory;

    protected $table = 'tenant_circle_menus';

    protected $fillable = [
        'target_date',
        'location_name',
        'total_portions',
        'menu_items',
        'documentation_photo',
        'status',
    ];

    protected $casts = [
        'target_date' => 'date',
        'menu_items' => 'array',
    ];

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft'      => '<span class="badge badge-secondary">Draft</span>',
            'processing' => '<span class="badge badge-warning text-dark"><i class="fas fa-spinner fa-spin mr-1"></i> Sedang Dikirim</span>',
            'completed'  => '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Selesai Dititipkan</span>',
            default      => '<span class="badge badge-light">' . $this->status . '</span>',
        };
    }
}
