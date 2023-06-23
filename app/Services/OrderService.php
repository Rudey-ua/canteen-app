<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class OrderService
{
   public static function createOrder(array $validated): Order
    {
        return DB::transaction(function () use ($validated) {

            $order = Order::create([
                'table_id' => $validated['table_id'],
                'status' => 'ordered',
                'order_date' => now()
            ]);

            $totalAmount = self::addDishesToOrder($order, $validated['dishes']);

            $order->total_amount = $totalAmount;
            $order->save();

            return $order;
        });
    }

    private static function addDishesToOrder(Order $order, array $dishes): float
    {
        $totalAmount = 0;

        foreach ($dishes as $dish) {
            $quantity = $dish['quantity'];
            $dishModel = Dish::find($dish['id']);

            $order->dishes()->attach($dishModel->id, ['quantity' => $quantity]);

            $dishAmount = $dishModel->price * $quantity;
            $totalAmount += $dishAmount;
        }

        return $totalAmount;
    }

    public static function createPayment(Order $order, array $validated): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending'
        ]);
    }
}
