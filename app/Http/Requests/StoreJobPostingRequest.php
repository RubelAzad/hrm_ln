<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|in:full-time,part-time,contract,temporary,internship',
            'experience_level' => 'nullable|string|in:entry,mid,senior,lead,executive',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0|lte:salary_max',
            'salary_max' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'vacancies' => 'nullable|integer|min:1',
            'status' => 'nullable|string|in:draft,published,closed,filled',
            'closing_at' => 'nullable|date|after_or_equal:today',
        ];
    }
}
