<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\Reservation\Reservation as ReservationResource;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function index(): JsonResponse
    {
        $reservation = Reservation::all();

        return response()->json([
            "reservations" => new ReservationCollection($reservation)
        ]);
    }

    public function show(Reservation $reservation): JsonResponse
    {
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
        //TODO: скорее всего я не смогу обновить поля резервации если передам тот же самый id который был при создании бронирования
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

    public function destroy(Reservation $reservation): JsonResponse
    {
        $table = $reservation->table;
        $table->status = 'free';
        $table->save();

        $reservation->delete();
        return response()->json(null, 204);
    }
}
