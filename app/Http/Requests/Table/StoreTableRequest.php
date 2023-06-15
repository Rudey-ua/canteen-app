<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
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
            'number' => 'required|integer|unique:tables,number',
            'capacity' => 'required|integer',
            'restaurant_id' => 'required|exists:restaurants,id'
        ];
    }

    public function messages()
    {
        return [
            'number.required' => 'The table number is required.',
            'number.integer' => 'The table number must be an integer.',
            'number.unique' => 'The table number must be unique.',
            'capacity.required' => 'The table capacity is required.',
            'capacity.integer' => 'The table capacity must be an integer.',
            'status.in' => 'The table status must be either "reserved" or "free".',
            'restaurant_id.required' => 'The restaurant ID is required.',
            'restaurant_id.exists' => 'The selected restaurant does not exist.'
        ];
    }
}
