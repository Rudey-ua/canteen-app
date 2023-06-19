<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Table;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
   public static function createOrder(array $validated): Order
    {
        return DB::transaction(function () use ($validated) {

            /*$table = Table::find($validated['table_id']);
            // If the table is reserved, validate that the reservation is made by the same user
            if ($table->status == 'reserved') {
                $reservation = Reservation::where('table_id', $table->id)->latest()->first();

                if ($reservation && $reservation->user_id != $validated['user_id']) {
                    throw new Exception("The table is reserved by another user");
                }
            } else {
                // If the table is not reserved, reserve it for this user
                self::reserveTableForUser($table, $validated['user_id']);
            }*/

            $order = self::createOrderRecord($validated);
            $totalAmount = self::addDishesToOrder($order, $validated['dishes']);

            $order->total_amount = $totalAmount;
            $order->save();

            return $order;
        });
    }

    private static function reserveTableForUser(Table $table, $userId): void
    {
        $table->status = 'ordered';
        $table->save();

        Reservation::create([
            'user_id' => $userId,
            'table_id' => $table->id,
            'reservation_date' => now()
        ]);
    }

    private static function createOrderRecord(array $validated): Order
    {
        return Order::create([
            'reservation_id' => $validated['reservation_id'],
            'status' => 'ordered',
            'order_date' => now()
        ]);
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
