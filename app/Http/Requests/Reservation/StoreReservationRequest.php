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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'seats_number' => 'required|integer',
            'wishes' => 'nullable|string|max:255',
            'requested_for_date' => 'required|date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'phone_number.required' => 'A phone number is required',
            'restaurant_id.required' => 'A restaurant id is required',
            'seats_number.required' => 'The number of seats is required',
            'status.required' => 'A status is required',
            'requested_for_date.required' => 'The date is required',
            'requested_for_date.after_or_equal' => 'The date must be today or later',
        ];
    }
}
