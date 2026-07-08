<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferLetter extends Model
{
    protected $fillable = [
        'candidate_id',
        'job_posting_id',
        'offer_date',
        'expiry_date',
        'offer_amount',
        'currency',
        'offer_letter_content',
        'offer_letter_path',
        'terms',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'offer_date' => 'date',
            'expiry_date' => 'date',
            'offer_amount' => 'decimal:2',
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
