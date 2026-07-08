<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
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
            'talent_pool_id' => 'nullable|exists:talent_pools,id',
        ];
    }

    public function messages(): array
    {
        return [
            'resume.max' => 'Resume must not exceed 5MB.',
            'resume.mimes' => 'Resume must be a PDF, DOC, DOCX, or TXT file.',
        ];
    }
}
