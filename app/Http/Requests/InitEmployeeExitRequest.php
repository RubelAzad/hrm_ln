<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitEmployeeExitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exit_type' => 'required|string|in:resignation,termination,retirement,end_of_contract,other',
            'notice_date' => 'required|date',
            'exit_date' => 'required|date|after_or_equal:notice_date',
            'reason' => 'nullable|string',
            'is_voluntary' => 'nullable|boolean',
        ];
    }
}
