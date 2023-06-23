<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:15',
            'restaurant_id' => 'sometimes|integer|exists:restaurants,id',
            'seats_number' => 'sometimes|integer',
            'wishes' => 'sometimes|string|max:255',
            'requested_for_date' => 'sometimes|date|after_or_equal:today',
        ];
    }
}
