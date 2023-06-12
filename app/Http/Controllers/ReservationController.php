<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Resources\Reservation as ReservationResource;
use App\Http\Resources\ReservationCollection;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();

        return response()->json([
            "orders" => new ReservationCollection($reservation)
        ]);
    }

    public function show($id): JsonResponse
    {
        $reservation = Reservation::findOrFail($id);

        return response()->json( new ReservationResource($reservation));
    }
}
