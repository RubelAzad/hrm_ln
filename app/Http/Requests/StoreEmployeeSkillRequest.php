<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => 'required|string|max:255',
            'category' => 'required|string|in:skill,certification',
            'proficiency' => 'nullable|string|in:beginner,intermediate,advanced,expert',
            'issued_by' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issued_date',
            'description' => 'nullable|string',
        ];
    }
}
