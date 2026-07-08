<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PipelineStage extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_posting_id',
        'stage',
        'stage_order',
        'notes',
        'stage_changed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'stage_order' => 'integer',
            'stage_changed_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
