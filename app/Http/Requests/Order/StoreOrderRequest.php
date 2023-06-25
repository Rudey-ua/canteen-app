<?php

namespace App\Http\Requests\Order;

use App\Rules\DishesBelongToSameRestaurant;
use App\Rules\MutuallyExclusiveFields;
use App\Rules\TableIsNotReserved;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'total_amount' => 'nullable|numeric',
            'table_id' => ['required_without:reservation_id', 'exists:tables,id', new TableIsNotReserved, new MutuallyExclusiveFields('reservation_id')],
            'reservation_id' => ['required_without:table_id', 'exists:reservations,id', new MutuallyExclusiveFields('table_id')],
            'dishes' => ['required', 'array', new DishesBelongToSameRestaurant],
            'payment_method' => 'required|string',
            'dishes.*.id' => 'required',
            'dishes.*.quantity' => 'required|integer|min:1'
        ];
    }
}
