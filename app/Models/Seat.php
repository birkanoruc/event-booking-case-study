<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Enums\SeatBlockStatus;
use App\Enums\SeatStatus;
use App\Enums\TicketStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Seat extends Model
{
    use HasFactory;

    protected $table = "seats";

    protected $fillable = [
        'venue_id',
        'section',
        'row',
        'number',
        'status',
        'price',
    ];

    protected $guarded = ["status"];

    protected $casts = [
        'status' => SeatStatus::class,
    ];

    /**
     * Seat -> Venue ilişkisi.
     * Koltuğun mekanına erişim sağlar.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Seat -> ReservationItems ilişkisi.
     * Koltuğun rezervasyon öğelerine erişim sağlar.
     *
     * @return HasMany
     */
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    /**
     * Seat -> SeatBlocks ilişkisi.
     * Koltuğun bloklu koltuk verilerine erişim sağlar.
     * Not: Bloklu koltuk tablosunda her zaman expires_at kontrol edilmelidir.
     *
     * @return HasMany
     */
    public function seatBlocks(): HasMany
    {
        return $this->hasMany(SeatBlock::class);
    }

    /**
     * Seat -> Tickets ilişkisi.
     * Koltuğun biletlerine erişim sağlar.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Seat -> Venue -> Events ilişkisi.
     * Koltuğun etkinliklerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, Venue::class, 'id', 'venue_id', 'venue_id', 'id');
    }

    /**
     * Seat -> ReservationItem -> Reservation ilişkisi.
     * Koltuğun rezervasyonuna erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, ReservationItem::class, 'seat_id', 'id', 'id', 'reservation_id');
    }

    /**
     * Koltuk durumunu hesaplayan accessor.
     * Sıralama önemli çünkü koltuk bloklu veya rezerve durumda iken satılmış olabilir.
     *
     * @return SeatStatus
     */
    public function getStatusAttribute(): SeatStatus
    {
        $request = request();

        // İstekten gelen event_id
        if ($request->route("id")) {
            $event_id = $request->route("id");
        } else if ($request->query('event_id')) {
            $event_id = $request->query("event_id");
        }

        // Koltuğa ait iptal edilmemiş bir bilet var mı?  O halde koltuk satılmış.
        $seatTickets = $this->tickets()
            ->whereHas('reservation', function ($query) use ($event_id) {
                $query->where('event_id', $event_id);
            })
            ->get();
        if ($seatTickets->where("status", "!=", TicketStatus::CANCELLED)->isNotEmpty()) {
            return SeatStatus::SOLD;
        }

        // Koltuk rezervasyon sürecinin içinde mi? O halde koltuk rezervasyon durumunda.
        if ($this->reservations()->where("event_id", $event_id)->get()->where('status', ReservationStatus::PENDING)->isNotEmpty()) {
            return SeatStatus::RESERVED;
        }

        // Koltuk birisi tarafından bloklanmış mı? O halde koltuk blok durumunda.
        if ($this->seatBlocks()->where("event_id", $event_id)->get()->where("status", SeatBlockStatus::PENDING)->isNotEmpty()) {
            return SeatStatus::BLOCKED;
        }

        // O halde koltuk müsait durumda.
        return SeatStatus::AVAILABLE;
    }
}
