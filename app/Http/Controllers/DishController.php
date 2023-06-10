<?php

namespace App\Http\Controllers;

use App\Http\Resources\DishCollection;
use App\Models\Dish;
use App\Http\Resources\Dish as DishResource;

class DishController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $dishes = Dish::all();

        return response()->json([
            "status" => true,
            "dishes" => new DishCollection($dishes)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show($id)
    {
        $dishes = Dish::find($id);

        if(!$dishes) return response()->json([
            "status" => false,
            "message" => "Dishes not found!"
        ], 404)->setStatusCode(404, 'Dishes not found!');

        return response()->json([
            "status" => true,
            "dish" => new DishResource($dishes)
        ], 200);
    }
}
