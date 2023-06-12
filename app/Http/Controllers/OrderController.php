<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Http\Resources\Order as OrderResource;
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

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $orders = Order::find($id);

        if(!$orders) return response()->json([
            "status" => false,
            "message" => "Orders not found!"
        ], 404)->setStatusCode(404, 'Orders not found!');

        return response()->json([
            "status" => true,
            "order" => new OrderResource($orders)
        ], 200);
    }
}
