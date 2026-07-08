<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interview_type' => 'sometimes|string|in:phone,video,in_person,technical,panel',
            'title' => 'sometimes|string|max:255',
            'scheduled_at' => 'sometimes|date',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_email' => 'nullable|email|max:255',
            'location_or_link' => 'nullable|string|max:255',
            'meeting_platform' => 'nullable|string|max:100',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'sometimes|string|in:scheduled,completed,cancelled,rescheduled',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }
}
