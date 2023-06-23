<?php

namespace App\Http\Controllers;

use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Http\Resources\Table\TableResource;
use App\Http\Resources\Table\TableCollection;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
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

    public function show(Table $table): JsonResponse
    {
        return response()->json(new TableResource($table));
    }

    public function store(StoreTableRequest $request): JsonResponse
    {
        $table = Table::create($request->validated());

        return response()->json(new TableResource($table), 201);
    }

    public function update(UpdateTableRequest $request, Table $table): JsonResponse
    {
        $table->update($request->validated());

        return response()->json(new TableResource($table));
    }

    public function destroy(Table $table): JsonResponse
    {
        $table->delete();

        return response()->json(null, 204);
    }
}
