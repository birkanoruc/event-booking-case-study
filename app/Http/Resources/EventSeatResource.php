<?php

namespace App\Http\Resources;

use App\Enums\SeatStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventSeatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section' => $this->section,
            'row' => $this->row,
            'number' => $this->number,
            'price' => $this->price,
            'status' => SeatStatus::getDescriptions()[$this->status->value],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => [
                'block' => route('seats.block', [
                    'seat_id' => $this->id,
                    'event_id' => $request->id,
                ]),
                'release' => route('seats.release', [
                    'seat_id' => $this->id,
                    'event_id' => $request->id,
                ]),
            ]
        ];
    }
}
