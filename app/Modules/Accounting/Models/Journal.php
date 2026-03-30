<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Journal extends Model
{
    protected $fillable = [
        'reference_number', 'date', 'description', 'total_amount', 'source_module', 'created_by'
    ];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
