<?php

namespace App\Http\Controllers;

use App\Http\Resources\DishCollection;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();

        return response()->json([
            "status" => true,
            "dishes" => new DishCollection($dishes)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show()
    {

    }
}
