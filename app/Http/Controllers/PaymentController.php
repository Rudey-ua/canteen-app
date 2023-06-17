<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\PaymentCollection;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderService;
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
        /*$order = Order::findOrFail($request->input('order_id'));

        try {
            OrderService::processPayment($order, $request->input('stripeToken'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }*/

        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $order = Order::find($request->input('order_id'));

        $line_items = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Ваш заказ',
                    ],
                    'unit_amount' => $order->total_amount * 100,
                ],
                'quantity' => 1,
            ],
        ];

        // Создайте новую сессию Checkout
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/api/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1:8000/api/cancel',
        ]);

        // Перенаправьте пользователя на URL-адрес Checkout
        return response()->json(['url' => $checkout_session->url], 200);
    }
}
