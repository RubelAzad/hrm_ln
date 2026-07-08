<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExit extends Model
{
    protected $fillable = [
        'employee_id',
        'exit_type',
        'notice_date',
        'exit_date',
        'reason',
        'is_voluntary',
        'exit_interview_date',
        'exit_interview_notes',
        'settlement_amount',
        'clearance_status',
        'clearance_notes',
        'rehire_eligible',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_voluntary' => 'boolean',
            'rehire_eligible' => 'boolean',
            'notice_date' => 'date',
            'exit_date' => 'date',
            'exit_interview_date' => 'date',
            'settlement_amount' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
