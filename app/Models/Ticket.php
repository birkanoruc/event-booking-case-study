<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\ReservationStatus;
use App\Enums\TicketStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Ticket extends Model
{
    use HasFactory;

    protected $table = "tickets";

    protected $fillable = [
        'reservation_id',
        'seat_id',
        'ticket_code',
        'status',
    ];

    protected $guarded = ["status"];

    protected $casts = [
        'status' => TicketStatus::class,
    ];

    /**
     * Ticket -> Reservation ilişkisi.
     * Biletin rezervasyonuna erişim sağlar.
     *
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Ticket -> Reservation -> User ilişkisi.
     * Biletin kullanıcısına erişim sağlar.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsToThrough(User::class, Reservation::class);
    }

    /**
     * Ticket -> Reservation -> ReservationItems ilişkisi.
     * Biletin rezervasyon öğelerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservationItems(): HasManyThrough
    {
        return $this->hasManyThrough(ReservationItem::class, Reservation::class, 'id', 'reservation_id', 'reservation_id', 'id');
    }

    /**
     * Ticket -> Seat ilişkisi.
     * Biletin koltuğuna erişim sağlar.
     *
     * @return BelongsTo
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Ticket -> Seat -> Venue ilişkisi.
     * Biletin mekanına erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function venue(): HasManyThrough
    {
        return $this->hasManyThrough(Venue::class, Seat::class, 'id', 'id', 'seat_id', 'venue_id');
    }

    /**
     * Ticket -> Reservation -> Event ilişkisi.
     * Biletin etkinliğine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function event(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, Reservation::class, 'id', 'id', 'reservation_id', 'event_id');
    }

    /**
     * Bilet durumunu hesaplayan accessor.
     * Sıralama önemli çünkü bilet kullanılabilir durumda iken aynı anda iptal edilemez durumda veya iptal edilmiş olabilir.
     *
     * @return TicketStatus
     */
    public function getStatusAttribute(): TicketStatus
    {
        // Biletin etkinliği iptal edilmiş mi? O Halde bilet iptal edilmiş durumda.
        $ticketEvent = $this->event()->get();
        if ($ticketEvent->where("status", EventStatus::CANCELLED)->isNotEmpty()) {
            return TicketStatus::CANCELLED;
        }

        // Biletin rezervasyonu iptal edişmiş mi? O halde bilet iptal edilmiş durumda.
        else if ($this->reservation()->where("status", ReservationStatus::CANCELLED)->get()->isNotEmpty()) {
            return TicketStatus::CANCELLED;
        }

        // Biletin etkinliğine 24 saatten az mı kalmış? O halde bilet transfer edilemez durumda.
        else if ($ticketEvent->where('start_date', '<=', Carbon::now()->addDay())->isNotEmpty()) {
            return TicketStatus::USED;
        }

        // Herhangi birine uymadı mı? O halde bilet kullanılabilir durumda.
        return TicketStatus::VALID;
    }
}
