<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePipelineStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_posting_id' => 'required|exists:job_postings,id',
            'stage' => 'required|string|in:sourced,applied,screening,interview,offer,hired,rejected',
            'notes' => 'nullable|string',
        ];
    }
}
