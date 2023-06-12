<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Resources\Reservation as ReservationResource;
use App\Http\Resources\ReservationCollection;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();

        return response()->json([
            "status" => true,
            "orders" => new ReservationCollection($reservation)
        ], 200)->setStatusCode(200, 'The resource has been fetched and transmitted in the message body.');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $reservation = Reservation::find($id);

        if(!$reservation) return response()->json([
            "status" => false,
            "message" => "Reservation not found!"
        ], 404)->setStatusCode(404, 'Reservation not found!');

        return response()->json([
            "status" => true,
            "reservation" => new ReservationResource($reservation)
        ], 200);
    }
}
