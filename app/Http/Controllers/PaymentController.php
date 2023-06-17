<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function pay(Request $request) {

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $charge = Charge::create([
            'amount' => $request->input('amount') * 100,
            'currency' => 'usd',
            'source' => $request->input('stripeToken'),
            'description' => 'Payment for order ' . $request->input('order_id'),
        ]);

        if ($charge->status == 'succeeded') {

            $payment = new Payment();
            $payment->order_id = $request->input('order_id');
            $payment->amount = $request->input('amount');
            $payment->payment_method = 'stripe';
            $payment->transaction_id = $charge->id;
            $payment->is_paid = true;
            $payment->payment_status = 'completed';
            $payment->save();

            $order = Order::find($request->input('order_id'));
            $order->status = 'paid';
            $order->save();
        }

        return response()->json(['status' => $charge->status]);
    }
}
