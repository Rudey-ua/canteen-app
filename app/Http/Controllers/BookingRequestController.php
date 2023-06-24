<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request\StoreRequest;
use App\Http\Requests\Request\UpdateRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\ReservationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookingRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $reservation = ReservationRequest::all();

        return response()->json([
            "reservations" => new ReservationCollection($reservation)
        ]);
    }

    public function show(ReservationRequest $request): JsonResponse
    {
        return response()->json( new ReservationResource($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->getAuthIdentifier();

        $reservationRequest = ReservationRequest::create($data);

        return response()->json(new ReservationResource($reservationRequest), 201);
    }

    public function update(UpdateRequest $updateRequest, ReservationRequest $request): JsonResponse
    {
        $request->update($updateRequest->validated());

        return response()->json(new ReservationResource($request));
    }

    public function destroy(ReservationRequest $request): JsonResponse
    {
        $request->delete();
        return response()->json(null, 204);
    }
}
