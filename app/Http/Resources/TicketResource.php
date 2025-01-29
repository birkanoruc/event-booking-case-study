<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * id, status, ticket_code, reservation_id, seat_id, created_at, updated_at
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'ticket_code' => $this->ticket_code,
            'reservation' => new ReservationResource($this->reservation),
            'seat' => new SeatResource($this->seat),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => [
                'self' => route('tickets.show', $this->id),
                'download' => route('tickets.download', $this->id),
                'transfer' => route('tickets.transfer', $this->id),
            ],
        ];
    }
}
