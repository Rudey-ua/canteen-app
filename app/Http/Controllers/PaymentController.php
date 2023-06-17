<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
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
            'success_url' => 'http://127.0.0.1:8000/api/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1:8000/api/cancel',
        ]);

        $payment = $order->payment;
        $payment->transaction_id = $checkout_session->id;
        $payment->save();

        return response()->json(['url' => $checkout_session->url], 200);
    }

    public function success(): JsonResponse
    {
        $payment = Payment::where('transaction_id', request()->only('session_id'))->first();
        $order = Order::where('id', $payment->order_id)->first();
        $table = Table::where('id', $order->table_id)->first();

        $order->status = 'paid';
        $order->save();

        $table->status = 'free';
        $table->save();

        $payment->payment_status = 'completed';
        $payment->save();

        return response()->json(['payment' => new PaymentResource($payment)], 200);
    }

    public function cancel()
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
