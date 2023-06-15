<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\Order as OrderResource;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::all();

        return response()->json([
            "orders" => new OrderCollection($orders)
        ]);
    }

    public function show($id): JsonResponse
    {
        $order = Order::findOrFail($id);

        return response()->json(new OrderResource($order));
    }
}
