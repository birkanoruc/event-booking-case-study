<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\ReservationStatus;
use App\Enums\TicketStatus;
use App\Models\Scopes\IncludeTrashedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reservations";

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'total_amount',
        'expires_at',
    ];

    protected $guarded = ["status"];

    protected $casts = [
        'status' => ReservationStatus::class,
    ];

    /**
     * Reservation -> Event ilişkisi.
     * Rezervasyonun etkinliğine erişim sağlar.
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Reservation -> User ilişkisi.
     * Rezervasyonun kullanıcısına erişim sağlar.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reservation -> ReservationItems ilişkisi.
     * Rezervasyonun rezervasyon öğelerine erişim sağlar.
     *
     * @return HasMany
     */
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    /**
     * Reservation -> Tickets ilişkisi.
     * Rezervasyonun biletlerine erişim sağlar.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Reservation -> Event -> Venue ilişkisi.
     * Rezervasyonun mekanına erişim sağlar.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsToThrough(Venue::class, Event::class);
    }

    /**
     * Reservation -> ReservationItems -> Seats ilişkisi.
     * Rezervasyonun koltuklarına erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function seats(): HasManyThrough
    {
        return $this->hasManyThrough(Seat::class, ReservationItem::class, 'reservation_id', 'id', 'id', 'venue_id');
    }

    /**
     * Rezervasyon durumunu hesaplayan accessor.
     * Sıralama önemli çünkü rezervasyon süresi dolmuş durumda iken aynı anda onaylanmış veya iptal edilmiş olabilir.
     *
     * @return ReservationStatus
     */
    public function getStatusAttribute(): ReservationStatus
    {
        // Rezervasyonun etkinliği iptal edilmiş mi? Veya rezervasyon iptal edilmiş mi? O Halde bilet iptal edilmiş durumda.
        if ($this->event()->get()->where("status", EventStatus::CANCELLED)->isNotEmpty() or $this->trashed()) {
            return ReservationStatus::CANCELLED;
        }

        // Rezervasyona ait iptal edilmeyen bilet var mı? O halde rezervasyon onaylanmış durumda.
        else if ($this->tickets()->get()->where("status", "!=", TicketStatus::CANCELLED)->isNotEmpty()) {
            return ReservationStatus::CONFIRMED;
        }

        // Rezervasyon süresi dolmuş mu? O halde rezervasyon süresi dolmuş durumda.
        else if ($this->expires_at < Carbon::now()) {
            return ReservationStatus::EXPIRED;
        }

        // Hiçbir şarta uymadı mı? Demek ki rezervasyon sürecindeyiz.
        return ReservationStatus::PENDING;
    }
}
