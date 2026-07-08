<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'offer_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:offer_date',
            'offer_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'offer_letter_content' => 'nullable|string',
            'terms' => 'nullable|string',
            'status' => 'nullable|string|in:draft,sent,accepted,rejected,withdrawn,expired',
            'notes' => 'nullable|string',
        ];
    }
}
