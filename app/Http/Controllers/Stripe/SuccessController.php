<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class SuccessController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $payment = $this->updatePaymentStatus(request()->only('session_id'));
        $order = $this->updateOrderStatus($payment->order_id);

        if (isset($order['table_id'])) {
            $this->updateTableStatus($order->table_id);
        }

        if (isset($order['reservation_id'])) {
            $this->updateReservationAndTableStatus($order['reservation_id']);
        }

        return response()->json(['payment' => new PaymentResource($payment)], 200);
    }

    private function updatePaymentStatus($sessionId): Payment
    {
        $payment = Payment::where('transaction_id', $sessionId)->firstOrFail();
        $payment->update(['payment_status' => 'completed']);

        return $payment;
    }

    private function updateOrderStatus($orderId): Order
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'paid']);

        return $order;
    }

    private function updateTableStatus($tableId): void
    {
        $table = Table::findOrFail($tableId);
        $table->update(['status' => 'free']);
    }

    private function updateReservationAndTableStatus($reservationId): void
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'paid']);

        $this->updateTableStatus($reservation->table_id);
    }
}
