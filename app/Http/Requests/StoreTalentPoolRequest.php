<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTalentPoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:talent_pools,name',
            'description' => 'nullable|string',
            'criteria' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}
