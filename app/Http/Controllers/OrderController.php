<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\Order as OrderResource;
use App\Http\Resources\Order\OrderCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::all();

        return response()->json([
            "orders" => new OrderCollection($orders)
        ]);
    }

    public function show($id): JsonResponse
    {
        $order = Order::findOrFail($id);

        return response()->json(new OrderResource($order));
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $table = Table::find($validated['table_id']);

        DB::transaction(function () use ($validated, $table) {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'table_id' => $validated['table_id'],
                'status' => 'ordered',
                'order_date' => now()
            ]);

            foreach ($validated['dishes'] as $dish) {
                $order->dishes()->attach($dish['id'], ['quantity' => $dish['quantity']]);
            }

            $table->status = 'reserved';
            $table->save();
        });

        return response()->json(['message' => 'Order created successfully'], 201);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
