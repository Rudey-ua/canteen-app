<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Payment\PaymentResource as PaymentResource;
use Illuminate\Http\Request;

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

    public function store(StoreOrderRequest $request, OrderService $orderService): JsonResponse
    {
        $order = $orderService->createOrder($request->validated());
        $payment = $orderService->createPayment($order, $request->validated());

        return response()->json([
            'order' => new OrderResource($order),
            'payment' => new PaymentResource($payment)
        ], 201);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }

    public function payOrderForTable(Table $table, PaymentService $paymentService): JsonResponse
    {
        $order = $paymentService->findOrder($table);

        if(!$order) return $paymentService->orderNotFound();

        if($order->status == 'paid') return $paymentService->orderAlreadyPaid();

        $request = new Request;
        $request->replace(['order_id' => $order->id]);

        return response()->json((new PaymentController)->store($request));
    }
}
