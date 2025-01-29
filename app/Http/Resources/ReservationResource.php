<?php

namespace App\Http\Resources;

use App\Enums\ReservationStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * id, status, total_amount, expires_at, user_id, event_id, created_at, updated_at
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => ReservationStatus::getDescriptions()[$this->status->value],
            'total_amount' => $this->total_amount,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reservation_items' => ReservationItemResource::collection($this->reservationItems),  // Düzeltilmiş kısım
            'links' => [
                'self' => route('reservations.show', $this->id),
                'confirm' => route('reservations.confirm', $this->id),
                'cancel' => route('reservations.destroy', $this->id),
                'event' => route('events.show', $this->event_id),
            ]
        ];
    }
}
