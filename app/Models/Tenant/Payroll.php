<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reference_number',
        'month',
        'year',
        'payment_date',
        'basic_salary',
        'allowance',
        'deduction',
        'net_salary',
        'status',
        'journal_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->reference_number = 'PYR-' . date('Ym') . '-' . strtoupper(str()->random(5));
        });
    }

    public function getFormattedNetSalaryAttribute()
    {
        return 'Rp ' . number_format((float)$this->net_salary, 0, ',', '.');
    }
}
