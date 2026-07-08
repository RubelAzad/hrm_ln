<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceAnomaly extends Model
{
    protected $fillable = [
        'attendance_record_id', 'employee_id', 'anomaly_type', 'severity',
        'description', 'metadata', 'detected_by', 'is_resolved',
        'resolved_by', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function attendanceRecord(): BelongsTo
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
