<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnEmployeeAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_date' => 'required|date',
            'condition_at_return' => 'required|string|in:new,good,average,fair,damaged',
            'status' => 'required|string|in:returned,lost,damaged',
            'notes' => 'nullable|string',
        ];
    }
}
