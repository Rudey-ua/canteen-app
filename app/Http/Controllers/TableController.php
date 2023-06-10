<?php

namespace App\Http\Controllers;

use App\Http\Resources\TableCollection;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Table as TableResource;

class TableController extends Controller
{
    public function index(): JsonResponse
    {
        $tables = Table::all();

        return response()->json([
            "status" => true,
            "tables" => new TableCollection($tables)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $tables = Table::find($id);

        if(!$tables) return response()->json([
            "status" => false,
            "message" => "Tables not found!"
        ], 404)->setStatusCode(404, 'Tables not found!');

        return response()->json([
            "status" => true,
            "tables" => new TableResource($tables)
        ], 200);
    }
}
