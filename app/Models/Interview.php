<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Interview extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_posting_id',
        'interview_type',
        'title',
        'scheduled_at',
        'duration_minutes',
        'interviewer_name',
        'interviewer_email',
        'location_or_link',
        'meeting_platform',
        'meeting_link',
        'notes',
        'status',
        'feedback',
        'rating',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'duration_minutes' => 'integer',
            'rating' => 'integer',
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

    public function aiInsights(): MorphMany
    {
        return $this->morphMany(AiInsight::class, 'insightable');
    }
}
