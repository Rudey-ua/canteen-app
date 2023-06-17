<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\Order as OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Dish;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Payment\Payment as PaymentResource;

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

        try {
            $order = OrderService::createOrder($validated);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $payment = OrderService::createPayment($order, $validated);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order),
            'payment' => new PaymentResource($payment)
        ], 201);
    }

    public function destroy(Order $order): JsonResponse
    {
        $table = $order->table;
        $table->status = 'free';
        $table->save();

        $order->delete();
        return response()->json(null, 204);
    }
}
