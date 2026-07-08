<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTalentPoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $poolId = $this->route('talent_pool')?->id;

        return [
            'name' => 'required|string|max:255|unique:talent_pools,name,' . $poolId,
            'description' => 'nullable|string',
            'criteria' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}
