<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackgroundVerification extends Model
{
    protected $fillable = [
        'candidate_id',
        'type',
        'vendor',
        'status',
        'initiated_at',
        'completed_at',
        'report_path',
        'report_summary',
        'initiated_by',
    ];

    protected function casts(): array
    {
        return [
            'initiated_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}
