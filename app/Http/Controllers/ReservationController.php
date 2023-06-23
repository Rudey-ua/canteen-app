<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Models\Reservation;
use App\Models\ReservationRequest;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function index(): JsonResponse
    {
        $reservation = ReservationRequest::all();

        return response()->json([
            "reservations" => new ReservationCollection($reservation)
        ]);
    }

    public function show(ReservationRequest $reservation): JsonResponse
    {
        return response()->json( new ReservationResource($reservation));
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservationRequest = ReservationRequest::create($request->validated());

        return response()->json(new ReservationResource($reservationRequest), 201);
    }

    public function update(UpdateReservationRequest $request, ReservationRequest $reservation): JsonResponse
    {
        $reservation->update($request->validated());

        return response()->json(new ReservationResource($reservation));
    }

    public function destroy(ReservationRequest $reservation): JsonResponse
    {
        $reservation->delete();
        return response()->json(null, 204);
    }
}
