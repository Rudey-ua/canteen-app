<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

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
        $validatedData = $request->validated();
        $reservation = $this->reservationService->createReservation($validatedData);
        $this->reservationService->updateTableStatus($validatedData['table_id']);
        $bookingRequest = $this->reservationService->updateBookingRequestStatus($validatedData['reservation_requests_id']);
        $this->reservationService->sendConfirmationSMS($bookingRequest);

        return response()->json(new ReservationResource($reservation), 201);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(null, 204);
    }
}
