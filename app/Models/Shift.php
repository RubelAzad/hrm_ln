<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'start_time', 'end_time', 'grace_minutes', 'late_threshold_minutes',
        'early_leave_threshold_minutes', 'break_duration_minutes', 'work_hours',
        'overtime_allowed', 'overtime_rate', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'grace_minutes' => 'integer',
            'late_threshold_minutes' => 'integer',
            'early_leave_threshold_minutes' => 'integer',
            'break_duration_minutes' => 'integer',
            'work_hours' => 'decimal:1',
            'overtime_allowed' => 'boolean',
            'overtime_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
