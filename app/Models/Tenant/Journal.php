<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'date',
        'description',
        'total_amount',
        'source_module',
        'created_by',
    ];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
