<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'interview_type' => 'required|string|in:phone,video,in_person,technical,panel',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_email' => 'nullable|email|max:255',
            'location_or_link' => 'nullable|string|max:255',
            'meeting_platform' => 'nullable|string|max:100',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
        ];
    }
}
