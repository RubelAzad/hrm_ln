<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'marital_status' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string|max:50',
            'joining_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date',
            'supervisor_id' => 'nullable|exists:employees,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered by another employee.',
            'supervisor_id.exists' => 'Selected supervisor does not exist.',
        ];
    }
}
