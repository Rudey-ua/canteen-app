<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\Order as OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
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
        try {
            $order = OrderService::createOrder($request->validated());
            $payment = OrderService::createPayment($order, $request->validated());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
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
