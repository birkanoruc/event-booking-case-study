<?php

namespace App\Models;

use App\Enums\SeatBlockStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeatBlock extends Model
{
    use HasFactory;

    protected $table = "seat_blocks";

    protected $fillable = [
        'seat_id',
        'user_id',
        'event_id',
        'expires_at',
    ];

    protected $guarded = ["status"];

    protected $casts = [
        "status" => SeatBlock::class,
    ];

    /**
     * SeatBlock -> Seat ilişkisi (Bloklanan koltuk bir mekanın koltuğuna aittir)
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * SeatBlock -> User ilişkisi (Bloklanan koltuk bir kullanıcıya aittir)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * SeatBlock -> Event ilişkisi (Bloklanan koltuk bir etkinliğe aittir)
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Koltuk blok durumunu hesaplayan accessor.
     *
     * @return SeatBlockStatus
     */
    public function getStatusAttribute(): SeatBlockStatus
    {
        return $this->expires_at < Carbon::now()
            ? SeatBlockStatus::EXPIRED
            : SeatBlockStatus::PENDING;
    }
}
