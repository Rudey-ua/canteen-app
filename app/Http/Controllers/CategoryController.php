<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

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
        $categories = Category::create($request->validated());

        return response()->json(new CategoryResource($categories), 201);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $categories = Category::findOrFail($id);

        $categories->update($request->validated());

        return response()->json(new CategoryResource($categories));
    }

    public function destroy($id): JsonResponse
    {
        $categories = Category::findOrFail($id);
        $categories->delete();

        return response()->json(null, 204);
    }
}
