<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeExitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exit_interview_date' => 'nullable|date',
            'exit_interview_notes' => 'nullable|string',
            'settlement_amount' => 'nullable|numeric|min:0',
            'clearance_status' => 'required|string|in:pending,partial,completed',
            'clearance_notes' => 'nullable|string',
            'rehire_eligible' => 'nullable|boolean',
            'status' => 'required|string|in:pending,approved,rejected,completed',
        ];
    }
}
