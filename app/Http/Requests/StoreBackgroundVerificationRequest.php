<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBackgroundVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:education,employment,identity,criminal,reference,drug_test,credit_check',
            'vendor' => 'nullable|string|max:255',
        ];
    }
}
