<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;

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

    public function show($id)
    {
        $categories = Category::find($id);

        if(!$categories) return response()->json([
            "status" => false,
            "message" => "Categories not found!"
        ], 404)->setStatusCode(404, 'Categories not found!');

        return response()->json([
            "status" => true,
            "categories" => new CategoryResource($categories)
        ], 200);
    }

    public function store(Request $request)
    {

    }

    public function update($id, Request $request)
    {

    }

    public function destroy($id)
    {

    }
}
