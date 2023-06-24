<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Request;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function index(): JsonResponse
    {
        $reservations = Reservation::all();

        return response()->json([
            "reservations" => new ReservationCollection($reservations)
        ]);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json(new ReservationResource($reservation));
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = Reservation::create($request->validated());

        $table = Table::findOrFail($request->validated()['table_id']);
        $table->update(['status' => 'reserved']);

        $bookingRequest = Request::findOrFail($request->validated()['reservation_requests_id']);
        $bookingRequest->update(['status' => 'approved']);

        return response()->json(new ReservationResource($reservation), 201);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(null, 204);
    }
}
