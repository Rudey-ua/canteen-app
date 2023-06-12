<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            "categories" => new CategoryCollection($categories)
        ]);
    }

    public function show($id): JsonResponse
    {
        $categories = Category::findOrFail($id);

        return response()->json(new CategoryResource($categories));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $categories = Category::create([
            "name" => $request->name,
        ]);

        return response()->json(new CategoryResource($categories), 201);
    }

    public function update(StoreCategoryRequest $request, $id): JsonResponse
    {
        $categories = Category::findOrFail($id);

        $categories->name = $request->name;
        $categories->save();

        return response()->json(new CategoryResource($categories));
    }

    public function destroy($id): JsonResponse
    {
        $categories = Category::findOrFail($id);
        $categories->delete();

        return response()->json(null, 204);
    }
}
