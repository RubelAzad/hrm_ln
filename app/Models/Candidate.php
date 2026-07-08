<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'current_company',
        'current_position',
        'experience_years',
        'education_level',
        'skills',
        'resume_path',
        'resume_parsed_data',
        'source',
        'notes',
        'status',
        'talent_pool_id',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'resume_parsed_data' => 'array',
            'experience_years' => 'integer',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function talentPool(): BelongsTo
    {
        return $this->belongsTo(TalentPool::class);
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

    public function verifications(): HasMany
    {
        return $this->hasMany(BackgroundVerification::class);
    }

    public function communications(): HasMany
    {
        return $this->hasMany(CandidateCommunication::class);
    }

    public function aiInsights(): MorphMany
    {
        return $this->morphMany(AiInsight::class, 'insightable');
    }

    public function latestStage(): ?PipelineStage
    {
        return $this->pipelineStages()->latest('stage_order')->first();
    }
}
