<?php

namespace App\Http\Resources\Table;

use App\Http\Resources\Restaurant\RestaurantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
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
            "number" => $this->number,
            "capacity" => $this->capacity,
            "restaurant" => new RestaurantResource($this->restaurant),
            "status" => $this->status ?? "free",
        ];
    }
}
