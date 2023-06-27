<?php

namespace App\Services;

use App\Models\Request;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    private TwilloService $twilio;

    public function __construct(TwilloService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function createReservation(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function updateTableStatus(int $tableId): void
    {
        $table = Table::findOrFail($tableId);
        $table->update(['status' => 'reserved']);
    }

    public function updateBookingRequestStatus(int $reservationRequestsId)
    {
        $request = Request::findOrFail($reservationRequestsId);
        $request->update(['status' => 'approved']);
        return $request;
    }

    public function sendConfirmationSMS(Request $bookingRequest): void
    {
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
    }
}
