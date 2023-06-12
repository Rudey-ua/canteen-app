<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDishRequest;
use App\Http\Resources\DishCollection;
use App\Models\Dish;
use App\Http\Resources\Dish as DishResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $dishes = Dish::all();

        return response()->json([
            "dishes" => new DishCollection($dishes)
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $dishes = Dish::findOrFail($id);

        return response()->json(new DishResource($dishes));
    }

    public function store(StoreDishRequest $request): \Illuminate\Http\JsonResponse
    {
        $dishes = Dish::create([
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "category_id" => $request->category_id,
            "ingredients" => $request->ingredients,
            "special_requirements" => $request->special_requirements,
            "recipe" => $request->recipe,
            "restaurant_id" => $request->restaurant_id,
        ]);

        return response()->json(new DishResource($dishes), 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'ingredients' => 'sometimes|string',
            'special_requirements' => 'nullable|string',
            'recipe' => 'nullable|string',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422)->setStatusCode(422, 'Validation error');
        }

        $dish = Dish::findOrFail($id);

        $fieldsToUpdate = ['name', 'description', 'price', 'category_id', 'ingredients', 'special_requirements', 'recipe', 'restaurant_id'];

        foreach ($fieldsToUpdate as $field) {
            if ($request->has($field)) {
                $dish->$field = $request->$field;
            }
        }
        $dish->save();

        return response()->json(new DishResource($dish));
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Dish::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
