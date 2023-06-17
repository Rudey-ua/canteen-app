<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'is_paid' => $this->is_paid,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
        ];
    }
}
