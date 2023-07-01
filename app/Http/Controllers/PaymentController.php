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
    public function index(): JsonResponse
    {
        $payments = Payment::all();

        return response()->json([
            "payments" => new PaymentCollection($payments)
        ]);
    }

    public function show(Payment $payment): JsonResponse
    {
        return response()->json(new PaymentResource($payment));
    }

    public function store(Request $request): JsonResponse
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
            'success_url' => env('APP_URL') . '/api/payment/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_URL') . '/payment/cancel',
        ]);

        $payment = $order->payment;
        $payment->transaction_id = $checkout_session->id;
        $payment->save();

        return response()->json(['url' => $checkout_session->url], 201);
    }
}
