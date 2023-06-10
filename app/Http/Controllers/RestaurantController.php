<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantCollection;
use App\Models\Restaurant;
use App\Http\Resources\Restaurant as RestaurantResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RestaurantController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $searchTerm = $request->input('search');

        $restaurants = Restaurant::query()
            ->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return response()->json([
            "status" => true,
            "restaurants" => new RestaurantCollection($restaurants)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $restaurant = Restaurant::find($id);

        if(!$restaurant) return response()->json([
            "status" => false,
            "message" => "Product not found!"
        ], 404)->setStatusCode(404, 'Product not found!');

        return response()->json([
            "status" => true,
            "restaurant" => new RestaurantResource($restaurant)
        ], 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'working_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422)->setStatusCode(422, 'Validation error');
        }

        $restaurant = Restaurant::create([
            "name" => $request->name,
            "address" => $request->address,
            "contact_info" => $request->contact_info,
            "working_hours" => $request->working_hours,
        ]);

        return response()->json([
            "status" => true,
            "restaurant" => new RestaurantResource($restaurant)
        ], 201)->setStatusCode(201, 'Restaurant created successfully!');
    }

    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'contact_info' => 'sometimes|string|max:255',
            'working_hours' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422)->setStatusCode(422, 'Validation error');
        }

        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                "status" => false,
                "message" => "Restaurant not found!"
            ], 404)->setStatusCode(404, 'Restaurant not found!');
        }

        if ($request->has('name')) {
            $restaurant->name = $request->name;
        }

        if ($request->has('address')) {
            $restaurant->address = $request->address;
        }

        if ($request->has('contact_info')) {
            $restaurant->contact_info = $request->contact_info;
        }

        if ($request->has('working_hours')) {
            $restaurant->working_hours = $request->working_hours;
        }

        $restaurant->save();

        return response()->json([
            "status" => true,
            "restaurant" => $restaurant
        ], 200)->setStatusCode(200, 'Restaurant data is updated!');
    }


    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
