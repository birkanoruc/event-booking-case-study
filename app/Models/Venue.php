<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Venue extends Model
{
    use HasFactory;

    protected $table = "venues";

    protected $fillable = [
        "name",
        "address",
        "capacity",
    ];

    /**
     * Venue -> Events ilişkisi.
     * Mekanın etkinliklerine erişim sağlar.
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Venue -> Event -> Reservations ilişkisi.
     * Mekanın rezervasyonlarına erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, Event::class);
    }

    /**
     * Venue -> Seats ilişkisi.
     * Mekanın koltuklarınae erişim sağlar.
     *
     * @return HasMany
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Venue -> Seat -> ReservationItems ilişkisi.
     * Mekanın rezervasyon öğelerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservationItems(): HasManyThrough
    {
        return $this->hasManyThrough(ReservationItem::class, Seat::class, 'venue_id', 'id', 'id', 'seat_id');
    }

    /**
     * Venue -> Seat -> Tickets ilişkisi.
     * Mekanın biletlerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Seat::class, 'venue_id', 'id', 'id', 'seat_id');
    }
}
