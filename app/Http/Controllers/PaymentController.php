<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\PaymentCollection;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            "payments" => new PaymentCollection($payments)
        ]);
    }
}
