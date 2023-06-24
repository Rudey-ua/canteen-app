<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'restaurant_id' => 'required|exists:restaurants,id',
            'seats_number' => 'required|integer',
            'wishes' => 'nullable|string',
            'requested_for_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'restaurant_id.required' => 'The restaurant field is required.',
            'restaurant_id.exists' => 'The selected restaurant is invalid.',
            'seats_number.required' => 'The seats number field is required.',
            'seats_number.integer' => 'The seats number must be an integer.',
            'wishes.string' => 'The wishes field must be a string.',
            'requested_for_date.required' => 'The requested for date field is required.',
            'requested_for_date.date' => 'The requested for date must be a valid date.',
        ];
    }
}
