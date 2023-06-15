<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\Category as CategoryResource;
use App\Http\Resources\Category\CategoryCollection;
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
        $category = Category::findOrFail($id);

        return response()->json(new CategoryResource($category));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json(new CategoryResource($category), 201);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $category->update($request->validated());

        return response()->json(new CategoryResource($category));
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json(null, 204);
    }
}
