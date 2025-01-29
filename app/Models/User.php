<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role',
    ];

    protected $guarded = ["role"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * User -> SeatBlocks ilişkisi.
     * Kullanıcının blokladığı koltuklara erişim sağlar.
     *
     * @return HasMany
     */
    public function seatBlockss(): HasMany
    {
        return $this->hasMany(SeatBlock::class);
    }

    /**
     * User -> Reservations ilişkisi.
     * Kullanıcının rezervasyonlarına erişim sağlar.
     *
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * User -> Reservation -> ReserationItems ilişkisi.
     * Kullanıcının rezervasyon öğelerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function reservationItems(): HasManyThrough
    {
        return $this->hasManyThrough(ReservationItem::class, Reservation::class, 'user_id', 'reservation_id', 'id', 'id');
    }

    /**
     * User -> Reservation -> Tickets ilişkisi.
     * Kullanıcının biletlerine erişim sağlar.
     *
     * @return HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Reservation::class, 'user_id', '', 'id', '');
    }
}
