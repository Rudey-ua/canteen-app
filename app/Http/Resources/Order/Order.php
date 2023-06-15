<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Restaurant\Restaurant;
use App\Http\Resources\Table\Table;
use App\Http\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            "user" => new User($this->user),
            "table" => new Table($this->table),
        ];
    }
}
