<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use App\Http\Resources\Restaurant\Restaurant as RestaurantResource;
use App\Http\Resources\Restaurant\RestaurantCollection;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search');

        $restaurants = Restaurant::query()
            ->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('address', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return response()->json([
            "restaurants" => new RestaurantCollection($restaurants)
        ]);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        return response()->json(new RestaurantResource($restaurant));
    }

    public function store(UpdateRestaurantRequest $request): JsonResponse
    {
        $restaurant = Restaurant::create($request->validated());

        return response()->json(new RestaurantResource($restaurant));
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        $restaurant->update($request->validated());

        return response()->json(new RestaurantResource($restaurant));
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
