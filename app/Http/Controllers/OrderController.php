<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
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
        $order = $this->findOrder($table);

        if(!$order) return $this->orderNotFound();

        if($order->status == 'paid') return $this->orderAlreadyPaid();

        $request = new Request;
        $request->replace(['order_id' => $order->id]);

        return response()->json((new PaymentController)->store($request));
    }

    private function findOrder(Table $table): ?Order
    {
        $reservation = $table->reservation;

        if ($reservation) {
            return Order::where('reservation_id', $reservation->id)->first();
        }
        return Order::where('table_id', $table->id)->first();
    }

    private function orderNotFound(): JsonResponse
    {
        return response()->json([
            'message' => 'There are no orders for this table!'
        ], 404);
    }

    private function orderAlreadyPaid(): JsonResponse
    {
        return response()->json([
            'message' => 'Order already paid!'
        ], 400);
    }
}
