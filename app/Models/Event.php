<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\SeatStatus;
use App\Models\Scopes\IncludeTrashedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "events";

    protected $fillable = [
        "name",
        "description",
        "venue_id",
        "start_date",
        "end_date",
    ];

    protected $guarded = ["status"];

    protected $casts = [
        'status' => EventStatus::class,
    ];

    /**
     * Event -> Venue ilişkisi
     * Etkinliğin mekanınına erşim sağlar.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }

    /**
     * Event -> Venue -> Seats ilişkisi
     * Etkinliğin koltuklarına erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function seats(): HasManyThrough
    {
        return $this->hasManyThrough(Seat::class, Venue::class, 'id', 'venue_id', 'venue_id', 'id');
    }

    /**
     * Event -> SeatBlocks ilişkisi.
     * Etkinliğin bloklanmış koltuklarına erişim sağlar.
     *
     * @return HasMany
     */
    public function seatBlocks(): HasMany
    {
        return $this->hasMany(SeatBlock::class);
    }

    /**
     * Event -> Reservations ilişkisi
     * Etkinliğin rezervasyonlarına erişim sağlar.
     *
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Event -> Reservations -> ReservationItems ilişkisi
     * Etkinliğin rezervasyon öğelerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservationItems(): HasManyThrough
    {
        return $this->hasManyThrough(ReservationItem::class, Reservation::class, 'event_id', 'reservation_id', 'id', 'id');
    }

    /**
     * Event -> Reservations -> Tickets ilişkisi
     * Etkinliğin biletlerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Reservation::class, 'event_id', 'reservation_id', 'id', 'id');
    }

    /**
     * Event -> Reservation-> Users ilişkisi
     * Etkinliğin kullanıcılarına erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Reservation::class, 'event_id', 'id', 'id', 'user_id');
    }

    /**
     * Etkinlik durumunu hesaplayan accessor.
     * Sıralama önemli çünkü etkinlik biletler tükenmiş durumda iken aynı anda bitmiş, başlamamış veya iptal edilmiş olabilir.
     *
     * @return EventStatus
     */
    public function getStatusAttribute(): EventStatus
    {
        // Etkinlik silinmiş mi?
        if ($this->trashed()) {
            // O halde etkinlik iptal edilmiş durumda.
            return EventStatus::CANCELLED;
        }

        // Etkinlik başlamamış mı?
        else if ($this->start_date > Carbon::now()) {
            // O halde etkinlik henüz başlamamış durumda.
            return EventStatus::DRAFT;
        }

        // Etkinliğe 24 saatten az mı var?
        else if ($this->start_date->diffInHours(Carbon::now()) <= 24) {
            return EventStatus::CLOSER;
        }

        // Etkinlik bitmiş mi?
        else if ($this->end_date < Carbon::now()) {
            // O halde etkinlik bitmiş durumda.
            return EventStatus::COMPLETED;
        }

        // O halde etkinlik satışa açık bir şekilde devam ediyor.
        return EventStatus::PUBLISHED;
    }
}
