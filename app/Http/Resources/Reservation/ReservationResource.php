<?php

namespace App\Http\Resources\Reservation;

use App\Http\Resources\Restaurant\RestaurantResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user" => new UserResource($this->user),
            "restaurant" => new RestaurantResource($this->restaurant),
            "seats_number" => $this->seats_number,
            "wishes" => $this->wishes,
            "requested_for_date" => $this->requested_for_date
        ];
    }
}
