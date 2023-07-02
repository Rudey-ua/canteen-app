<?php

namespace App\Http\Resources\Dish;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Restaurant\RestaurantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
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
            "name" => $this->name,
            "price" => $this->price,
            'images' => $this->images->pluck('filename'),
            "category" => new CategoryResource($this->category),
            "ingredients" => $this->ingredients,
            "recipe" => $this->recipe,
            "restaurant" => new RestaurantResource($this->restaurant)
        ];
    }
}
