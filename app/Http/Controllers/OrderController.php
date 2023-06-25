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
    public function __construct(private PaymentService $paymentService)
    {
    }

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
        $order = OrderService::createOrder($request->validated());
        $payment = OrderService::createPayment($order, $request->validated());

        if (isset($request->validated()['table_id']))
        {
            $table = Table::findOrFail($request->validated()['table_id']);
            $table->update(['status' => 'reserved']);
        }

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

    public function payOrderForTable(Table $table): JsonResponse
    {
        $order = $this->paymentService->findOrder($table);

        if(!$order) return $this->paymentService->orderNotFound();

        if($order->status == 'paid') return $this->paymentService->orderAlreadyPaid();

        $request = new Request;
        $request->replace(['order_id' => $order->id]);

        return response()->json((new PaymentController)->store($request));
    }
}
