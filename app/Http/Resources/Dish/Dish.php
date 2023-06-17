<?php

namespace App\Http\Resources\Dish;

use App\Http\Resources\Category\Category;
use App\Http\Resources\Restaurant\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Dish extends JsonResource
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
            "category" => new Category($this->category),
            "ingredients" => $this->ingredients,
            "recipe" => $this->recipe,
            "restaurant" => new Restaurant($this->restaurant)
        ];
    }
}
