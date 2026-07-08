<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $candidateId = $this->route('candidate')?->id;

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidateId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:70',
            'education_level' => 'nullable|string|max:100',
            'skills' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|in:active,passive,blacklisted,hired',
            'talent_pool_id' => 'nullable|exists:talent_pools,id',
        ];
    }
}
