<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_type' => 'required|string|in:laptop,phone,id_card,sim_card,access_card,other',
            'asset_name' => 'required|string|max:255',
            'asset_serial' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'specification' => 'nullable|json',
            'assigned_date' => 'required|date',
            'condition_at_assignment' => 'required|string|in:new,good,average,fair',
            'notes' => 'nullable|string',
        ];
    }
}
