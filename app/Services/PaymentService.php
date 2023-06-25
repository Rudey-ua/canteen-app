<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class PaymentService
{
    public function findOrder(Table $table): ?Order
    {
        $reservation = $table->reservation;

        if ($reservation) {
            return Order::where('reservation_id', $reservation->id)->first();
        }
        return Order::where('table_id', $table->id)->first();
    }

    public function orderNotFound(): JsonResponse
    {
        return response()->json([
            'message' => 'There are no orders for this table!'
        ], 404);
    }

    public function orderAlreadyPaid(): JsonResponse
    {
        return response()->json([
            'message' => 'Order already paid!'
        ], 400);
    }
}
