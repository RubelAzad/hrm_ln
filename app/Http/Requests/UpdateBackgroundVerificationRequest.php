<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBackgroundVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,in_progress,cleared,flagged,failed',
            'completed_at' => 'nullable|date',
            'report_summary' => 'nullable|string',
            'report' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ];
    }
}
