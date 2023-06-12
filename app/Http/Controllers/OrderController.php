<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            "status" => true,
            "orders" => new OrderCollection($orders)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show()
    {

    }
}
