<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * id, price, reservation_id, seat_id, created_at, updated_at
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "price" => $this->reservation_id,
            'seat' => new SeatResource($this->seat),
        ];
    }
}
