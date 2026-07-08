<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRecord extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'shift_id', 'overtime_hours', 'overtime_type',
        'rate_multiplier', 'is_approved', 'approved_by', 'approved_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'overtime_hours' => 'decimal:2',
            'rate_multiplier' => 'decimal:2',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
