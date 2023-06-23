<?php

namespace App\Http\Requests\Order;

use App\Rules\DishesBelongToSameRestaurant;
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
            'table_id' => 'required|exists:tables,id',
            'dishes' => ['required', 'array', new DishesBelongToSameRestaurant],
            'payment_method' => 'required|string',
            'dishes.*.id' => 'required',
            'dishes.*.quantity' => 'required|integer|min:1'
        ];
    }
}
