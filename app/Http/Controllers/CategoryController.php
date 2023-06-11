<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            "status" => true,
            "categories" => new CategoryCollection($categories)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $categories = Category::find($id);

        if(!$categories) return response()->json([
            "status" => false,
            "message" => "Categories not found!"
        ], 404)->setStatusCode(404, 'Categories not found!');

        return response()->json([
            "status" => true,
            "category" => new CategoryResource($categories)
        ], 200);
    }

    public function store(StoreCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $categories = Category::create([
            "name" => $request->name,
        ]);

        return response()->json([
            "status" => true,
            "categories" => new CategoryResource($categories)
        ], 201)->setStatusCode(201, 'Categories created successfully!');
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422)->setStatusCode(422, 'Validation error');
        }

        $categories = Category::find($id);

        if (!$categories) {
            return response()->json([
                "status" => false,
                "message" => "Categories not found!"
            ], 404)->setStatusCode(404, 'Categories not found!');
        }

        if ($request->has('name')) {
            $categories->name = $request->name;
        }

        $categories->save();

        return response()->json([
            "status" => true,
            "category" => new CategoryResource($categories)
        ], 200)->setStatusCode(200, 'Categories data is updated!');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $categories = Category::findOrFail($id);
        $categories->delete();

        return response()->json(null, 204);
    }
}
