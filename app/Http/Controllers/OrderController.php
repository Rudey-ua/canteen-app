<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\Order as OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Dish;
use App\Models\Payment;
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

    public function show(Order $order): JsonResponse
    {
        return response()->json(new OrderResource($order));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $table = Table::find($validated['table_id']);

        $order = null;
        $payment = null;

        DB::transaction(function () use ($validated, $table, &$order, &$payment) {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'table_id' => $validated['table_id'],
                'status' => 'ordered',
                'order_date' => now()
            ]);

            $totalAmount = 0;

            foreach ($validated['dishes'] as $dish) {
                $quantity = $dish['quantity'];
                $dishModel = Dish::find($dish['id']);

                $order->dishes()->attach($dishModel->id, ['quantity' => $quantity]);

                $dishAmount = $dishModel->price * $quantity;
                $totalAmount += $dishAmount;
            }

            $order->total_amount = $totalAmount;
            $order->save();

            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => $validated['payment_method'],
                'is_paid' => false,
                'payment_status' => 'pending'
            ]);

            $table->status = 'reserved';
            $table->save();
        });

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order),
            'payment' => new $payment
        ], 201);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
