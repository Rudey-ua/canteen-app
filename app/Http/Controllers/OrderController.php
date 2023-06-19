<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use App\Models\Reservation;
use App\Http\Resources\Reservation\ReservationResource;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Payment\PaymentResource as PaymentResource;

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
        $reservation = Reservation::find($request['reservation_id']);

        //TODO: make implementation if user don`t create reservation
        if(isset($request['reservation_id'])) {

        }

        try {
            $userData = $request->validated();
            $userData['user_id'] = auth()->user()->id;

            OrderService::assertOrderDoesNotExist($reservation->id);
            $order = OrderService::createOrder($userData);
            $payment = OrderService::createPayment($order, $userData);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'order' => new OrderResource($order),
            'reservation' => new ReservationResource($reservation),
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
