<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_info' => 'required|string',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than :max characters.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address field must be a string.',
            'contact_info.required' => 'The contact info field is required.',
            'contact_info.string' => 'The contact info field must be a string.',
            'opening_time.required' => 'The opening time field is required.',
            'opening_time.date_format' => 'The opening time does not match the format H:i.',
            'closing_time.required' => 'The closing time field is required.',
            'closing_time.date_format' => 'The closing time does not match the format H:i.',
            'closing_time.after' => 'The closing time must be a time after the opening time.',
        ];
    }
}
