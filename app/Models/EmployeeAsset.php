<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAsset extends Model
{
    protected $fillable = [
        'employee_id',
        'asset_type',
        'asset_name',
        'asset_serial',
        'brand',
        'model',
        'color',
        'specification',
        'assigned_date',
        'return_date',
        'condition_at_assignment',
        'condition_at_return',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'specification' => 'array',
            'assigned_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
