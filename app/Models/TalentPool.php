<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentPool extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'criteria',
        'status',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }
}
