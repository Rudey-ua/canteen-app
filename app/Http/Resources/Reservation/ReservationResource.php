<?php

namespace App\Http\Resources\Reservation;

use App\Http\Resources\Restaurant\RestaurantResource;
use App\Http\Resources\Table\TableResource;
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
            "table" => new TableResource($this->table),
            "reservation_date" => $this->reservation_date,
            "note" => $this->note
        ];
    }
}
