<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'tenant_id',
        'user_id',
        'subject',
        'message',
        'priority',
        'status',
        'last_replied_at',
    ];

    protected $casts = [
        'last_replied_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public static function generateNumber(): string
    {
        $prefix = 'TKT-' . date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 5));
        return $prefix . '-' . $random;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open'    => '<span class="badge badge-success">Terbuka</span>',
            'pending' => '<span class="badge badge-warning">Diproses</span>',
            'closed'  => '<span class="badge badge-secondary">Selesai</span>',
            default   => '<span class="badge badge-info">' . $this->status . '</span>',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low'    => '<span class="badge badge-info">Rendah</span>',
            'medium' => '<span class="badge badge-primary">Sedang</span>',
            'high'   => '<span class="badge badge-warning">Tinggi</span>',
            'urgent' => '<span class="badge badge-danger">Mendesak</span>',
            default  => '<span class="badge badge-dark">' . $this->priority . '</span>',
        };
    }
}
