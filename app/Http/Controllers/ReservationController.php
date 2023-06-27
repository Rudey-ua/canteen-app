<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Request;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use App\Services\TwilloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    protected TwilloService $twilio;

    public function __construct(TwilloService $twilio)
    {
        $this->twilio = $twilio;
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
        $reservation = Reservation::create($request->validated());

        $table = Table::findOrFail($request->validated()['table_id']);
        $table->update(['status' => 'reserved']);

        $bookingRequest = Request::findOrFail($request->validated()['reservation_requests_id']);
        $bookingRequest->update(['status' => 'approved']);

        /*Send sms to customer with confirmation for table reservation*/

        $restaurant = Restaurant::findOrFail($bookingRequest->restaurant_id);
        $customer = User::findOrfail($bookingRequest->user_id);
        $message = "Ваше бронювання столу в ресторані {$restaurant->name} на {$bookingRequest->requested_for_date} успішно підтверджено!";

        if ($customer->phone) {
            try {
                $this->twilio->sendSMS($customer->phone, $message);
            } catch (\Exception $e) {
                Log::error('Error while sending SMS:' . $e->getMessage());
            }
        }
        return response()->json(new ReservationResource($reservation), 201);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(null, 204);
    }
}
