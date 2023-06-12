<?php

namespace App\Http\Controllers;

use App\Http\Resources\TableCollection;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Table as TableResource;
use Illuminate\Http\Request;


class TableController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $tables = Table::query();

        $tables->when($status, function ($query, $status) {
            return $query->where('status', $status);
        });

        $tables = $tables->get();

        return response()->json([
            "tables" => new TableCollection($tables)
        ]);
    }

    public function show($id): JsonResponse
    {
        $tables = Table::findOrFail($id);

        return response()->json(new TableResource($tables));
    }
}
