<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiInsight extends Model
{
    protected $fillable = [
        'insightable_type',
        'insightable_id',
        'type',
        'data',
        'score',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'score' => 'decimal:2',
        ];
    }

    public function insightable(): MorphTo
    {
        return $this->morphTo();
    }
}
