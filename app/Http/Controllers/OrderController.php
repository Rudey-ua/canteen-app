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
            $order = $this->createOrder($validated);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $payment = $this->createPayment($order, $validated);

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order),
            'payment' => new PaymentResource($payment)
        ], 201);
    }

    private function createOrder(array $validated): Order
    {
        return DB::transaction(function () use ($validated) {
            $table = Table::find($validated['table_id']);

            // If the table is reserved, validate that the reservation is made by the same user
            if ($table->status == 'reserved') {
                $reservation = Reservation::where('table_id', $table->id)->latest()->first();

                if ($reservation && $reservation->user_id != $validated['user_id']) {
                    throw new Exception("The table is reserved by another user");
                }
            } else {
                // If the table is not reserved, reserve it for this user
                $table->status = 'reserved';
                $table->save();

                Reservation::create([
                    'user_id' => $validated['user_id'],
                    'table_id' => $table->id,
                    'reservation_date' => now()
                ]);
            }

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

            return $order;
        });
    }

    private function createPayment(Order $order, array $validated): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => $validated['payment_method'],
            'is_paid' => false,
            'payment_status' => 'pending'
        ]);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
