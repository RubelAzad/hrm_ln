<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateCommunicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'type' => 'required|string|in:email,phone,sms,in_person',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'direction' => 'nullable|string|in:outbound,inbound',
        ];
    }
}
