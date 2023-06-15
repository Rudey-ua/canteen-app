<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dish\StoreDishRequest;
use App\Http\Requests\Dish\UpdateDishRequest;
use App\Http\Resources\Dish as DishResource;
use App\Http\Resources\DishCollection;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;

class DishController extends Controller
{
    public function index(): JsonResponse
    {
        $dishes = Dish::all();

        return response()->json([
            "dishes" => new DishCollection($dishes)
        ]);
    }

    public function show($id): JsonResponse
    {
        $dish = Dish::findOrFail($id);

        return response()->json(new DishResource($dish));
    }

    public function store(StoreDishRequest $request): JsonResponse
    {
        $dish = Dish::create($request->validated());

        return response()->json(new DishResource($dish), 201);
    }

    public function update(UpdateDishRequest $request, $id): JsonResponse
    {
        $dish = Dish::findOrFail($id);

        $dish->update($request->validated());

        return response()->json(new DishResource($dish));
    }

    public function destroy($id): JsonResponse
    {
        Dish::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
