<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            "payments" => new PaymentCollection($payments)
        ]);
    }

    public function store(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $order = Order::with('dishes')->findOrFail($request->input('order_id'));

        $line_items = [];

        foreach($order->dishes as $dish) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $dish->name,
                    ],
                    'unit_amount' => $dish->price * 100, // Stripe charges in cents
                ],
                'quantity' => $dish->pivot->quantity,
            ];
        }

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/api/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_URL') . '/api/cancel',
        ]);

        $payment = $order->payment;
        $payment->transaction_id = $checkout_session->id;
        $payment->save();

        return response()->json(['url' => $checkout_session->url], 201);
    }

    public function success(): JsonResponse
    {
        $payment = Payment::where('transaction_id', request()->only('session_id'))->first();
        $payment->payment_status = 'completed';
        $payment->save();

        $order = Order::findOrFail($payment->order_id);
        $order->status = 'paid';
        $order->save();

        if (isset($order['table_id']))
        {
            $table = Table::findOrFail($order->table_id);
            $table->status = 'free';
            $table->save();
        }

        if (isset($order['reservation_id']))
        {
            $reservation = Reservation::findOrFail($order->reservation_id);
            $table = Table::findOrFail($reservation->table_id);
            $table->status = 'free';
            $table->save();
        }

        return response()->json(['payment' => new PaymentResource($payment)], 200);
    }

    public function cancel(): JsonResponse
    {
        $stripeSessionId = request()->only('session_id');

        $payment = Payment::where('transaction_id', $stripeSessionId)->first();
        $payment->payment_status = "canceled";
        $payment->save();

        return response()->json([
            "message" => "Order is cancelled."
        ], 200);
    }
}
