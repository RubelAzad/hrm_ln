<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'experience_level',
        'description',
        'requirements',
        'responsibilities',
        'salary_min',
        'salary_max',
        'currency',
        'vacancies',
        'status',
        'posted_at',
        'closing_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'vacancies' => 'integer',
            'posted_at' => 'datetime',
            'closing_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (JobPosting $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . uniqid();
            }
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pipelineStages(): HasMany
    {
        return $this->hasMany(PipelineStage::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function offerLetters(): HasMany
    {
        return $this->hasMany(OfferLetter::class);
    }

    public function communications(): HasMany
    {
        return $this->hasMany(CandidateCommunication::class);
    }

    public function aiInsights(): MorphMany
    {
        return $this->morphMany(AiInsight::class, 'insightable');
    }
}
