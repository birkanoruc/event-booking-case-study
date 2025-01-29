<?php

namespace App\Http\Resources;

use App\Enums\EventStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * id, status, name, description, start_date, end_date, venue_id,  created_at, updated_at
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => Carbon::parse($this->start_date)->translatedFormat('d F Y, H:i'),
            'end_date' => Carbon::parse($this->end_date)->translatedFormat('d F Y, H:i'),
            'status' => EventStatus::getDescriptions()[$this->status->value],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => [
                'self' => route('events.show', $this->id),
                "seats" => route('events.seats', $this->id),
            ],
            'relations' => [
                'venue' => new VenueResource($this->venue),
            ]
        ];

        // Admin kullanıcılar için cevaplara link ekler
        if (Auth::user() && Auth::user()->role === UserRole::ADMIN) {
            $response['links'] = array_merge($response['links'], [
                'create' => route('events.store'),
                'update' => route('events.update', $this->id),
                'delete' => route('events.destroy', $this->id),
            ]);
        }

        return $response;
    }
}
