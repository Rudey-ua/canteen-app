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
        $newTableId = $request->validated()['table_id'];
        $newTable = Table::findOrFail($newTableId);

        if ($newTable->status === 'reserved') {
            return response()->json(['error' => 'Table has already been booked.'], 400);
        }

        $reservation->table->update(['status' => 'free']);
        $newTable->update(['status' => 'reserved']);
        $reservation->update($request->validated() + ['table_id' => $newTableId]);

        return response()->json(new ReservationResource($reservation));
    }

    public function destroy($id): JsonResponse
    {
        $table = Reservation::findOrFail($id);

        $table->delete();
        return response()->json(null, 204);
    }
}
