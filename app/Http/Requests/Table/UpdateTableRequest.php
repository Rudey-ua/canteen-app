<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'sometimes|integer|unique:tables,number',
            'capacity' => 'sometimes|integer',
            'status' => 'sometimes|in:free,reserved',
            'restaurant_id' => 'sometimes|exists:restaurants,id'
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'The status field must be either "free" or "reserved".',
        ];
    }
}
