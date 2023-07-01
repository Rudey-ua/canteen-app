<?php

namespace App\Http\Controllers;

use App\Http\Filters\Dish\ByCategory;
use App\Http\Filters\Dish\ByName;
use App\Http\Requests\Dish\StoreDishRequest;
use App\Http\Requests\Dish\UpdateDishRequest;
use App\Http\Resources\Dish\DishResource;
use App\Http\Resources\Dish\DishCollection;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Pipeline;

class DishController extends Controller
{
    public function index()
    {
        $pipelines = [
            ByName::class, ByCategory::class
        ];

        $dishes = Pipeline::send(Dish::query())
            ->through($pipelines)
            ->thenReturn()
            ->get();

        return response()->json([
            "dishes" => new DishCollection($dishes)
        ]);
    }

    public function show(Dish $dish): JsonResponse
    {
        return response()->json(new DishResource($dish));
    }

    public function store(StoreDishRequest $request): JsonResponse
    {
        $dish = Dish::create($request->validated());

        return response()->json(new DishResource($dish), 201);
    }

    public function update(UpdateDishRequest $request, Dish $dish): JsonResponse
    {
        $dish->update($request->validated());

        return response()->json(new DishResource($dish));
    }

    public function destroy(Dish $dish): JsonResponse
    {
        $dish->delete();

        return response()->json(null, 204);
    }

    public function getRestaurantDishes($restaurant_id): JsonResponse
    {
        $dish = Dish::where('restaurant_id', $restaurant_id)->get();

        return response()->json([
            'dishes' => new DishCollection($dish),
        ]);
    }
}
