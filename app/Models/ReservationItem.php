<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationItem extends Model
{
    use HasFactory;

    protected $table = "reservation_items";

    protected $fillable = [
        'reservation_id',
        'seat_id',
        'price',
    ];

    /**
     * ReservationItem -> Reservation ilişkisi
     * Rezervasyon öğesinin rezervasyonuna erişim sağlar.
     *
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * ReservationItem -> Seat ilişkisi.
     * Rezervasyon öğesinin koltuğuna erişim sağlar.
     *
     * @return BelongsTo
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * ReservationItem -> Reservation -> Event ilişkisi.
     * Rezervasyon öğesinin etkinliğine erişim sağlar.
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsToThrough(Event::class, Reservation::class);
    }

    /**
     * ReservationItem -> Reservation -> Ticket ilişkisi.
     * Rezervasyon öğesinin biletine erişim sağlar.
     *
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsToThrough(Ticket::class, Reservation::class);
    }

    /**
     * ReservationItem -> Seat -> Venue ilişkisi (Rezervasyon öğesinin koltuğu üzerinden mekana erişim).
     * Rezervasyon öğesinin mekanına erişim sağlar.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsToThrough(Venue::class, Seat::class);
    }
}
