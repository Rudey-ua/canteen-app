<?php

namespace App\Http\Controllers;

use App\Http\Resources\Reservation\Reservation as ReservationResource;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Models\Reservation;
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

    public function store()
    {

    }
}
