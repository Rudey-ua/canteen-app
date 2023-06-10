<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantCollection;
use App\Models\Restaurant;
use App\Http\Resources\Restaurant as RestaurantResource;

class RestaurantController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $restaurant = Restaurant::all();
        return response()->json([
            "status" => true,
            "restaurants" => new RestaurantCollection($restaurant)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show($id)
    {
        $restaurant = Restaurant::find($id);

        if(!$restaurant) return response()->json([
            "status" => false,
            "message" => "Product not found!"
        ], 404)->setStatusCode(404, 'Product not found!');

        return response()->json([
            "status" => true,
            "product" => new RestaurantResource($restaurant)
        ], 200);
    }
}
