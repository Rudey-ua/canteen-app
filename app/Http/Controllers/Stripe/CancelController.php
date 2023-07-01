<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancelController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $stripeSessionId = request()->only('session_id');

        $this->cancelPayment($stripeSessionId);

        return response()->json([
            "message" => "Order is cancelled."
        ], 400);
    }

    private function cancelPayment($sessionId): void
    {
        $payment = Payment::where('transaction_id', $sessionId)->firstOrFail();
        $payment->update(['payment_status' => 'canceled']);
    }

}
