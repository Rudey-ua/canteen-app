<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Restaurant\RestaurantResource;
use App\Http\Resources\Table\TableResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_date" => $this->order_date,
            "status" => $this->status,
        ];
    }
}
