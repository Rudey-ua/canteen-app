<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $validated): Order
    {
        return DB::transaction(function () use ($validated) {

            $order = null;

            if (isset($validated['table_id'])) {
                $order = $this->createOrderFromArray(['table_id' => $validated['table_id']]);
                $this->changeTableStatusToReserved($validated['table_id']);
            } else {
                $order = $this->createOrderFromArray(['reservation_id' => $validated['reservation_id']]);
            }

            $totalAmount = $this->addDishesToOrder($order, $validated['dishes']);

            $order->total_amount = $totalAmount;
            $order->save();

            return $order;
        });
    }

    private function createOrderFromArray($data)
    {
        return Order::create([
            key($data) => current($data),
            'status' => 'ordered',
            'order_date' => now()
        ]);
    }

    private function addDishesToOrder(Order $order, array $dishes): float
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

    private function changeTableStatusToReserved(int $tableId): void
    {
        $table = Table::findOrFail($tableId);
        $table->update(['status' => 'reserved']);
    }

    public function createPayment(Order $order, array $validated): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending'
        ]);
    }
}
