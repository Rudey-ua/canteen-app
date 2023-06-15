<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\Reservation\Reservation as ReservationResource;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Models\Reservation;
use App\Models\Table;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class ReservationController extends Controller
{
    public function index()
    {
        $reservation = Reservation::all();

        return response()->json([
            "reservations" => new ReservationCollection($reservation)
        ]);
    }

    public function show($id): JsonResponse
    {
        $reservation = Reservation::findOrFail($id);

        return response()->json( new ReservationResource($reservation));
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        $table = Table::findOrFail($request->validated()['table_id']);

        if ($table->status === 'reserved') {
            return response()->json(['error' => 'Table already have been booked.'], 400);
        }

        $reservation = Reservation::create($request->validated());
        $table = $reservation->table;
        $table->status = 'reserved';
        $table->save();

        return response()->json(new ReservationResource($reservation));
    }

    public function update(UpdateReservationRequest $request, $id): JsonResponse
    {
        $reservation = Reservation::findOrFail($id);
        $newTable = Table::findOrFail($request->validated()['table_id']);

        if ($newTable->status === 'reserved') {
            return response()->json(['error' => 'Table already have been booked.'], 400);
        }

        $oldTable = $reservation->table;
        $oldTable->status = 'free';
        $oldTable->save();

        $newTable->status = 'reserved';
        $newTable->save();

        $reservation->table_id = $newTable->id;
        $reservation->save();

        $reservation->update($request->validated());

        return response()->json(new ReservationResource($reservation));
    }

    public function destroy($id): JsonResponse
    {
        $table = Reservation::findOrFail($id);

        $table->delete();
        return response()->json(null, 204);
    }
}
