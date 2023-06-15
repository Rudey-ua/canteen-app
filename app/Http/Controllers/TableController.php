<?php

namespace App\Http\Controllers;

use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
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
        $table = Table::findOrFail($id);

        return response()->json(new TableResource($table));
    }

    public function store(StoreTableRequest $request)
    {
        $table = Table::create($request->except('status'));

        return response()->json(new TableResource($table), 201);
    }

    public function update(UpdateTableRequest $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
