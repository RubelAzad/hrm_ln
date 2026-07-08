<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'The document must not be larger than 10MB.',
        ];
    }
}
