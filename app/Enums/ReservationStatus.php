<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case EXPIRED = 'expired';
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case USED = 'used';
    case CANCELLED = 'cancelled';

    public static function getDescriptions(): array
    {
        return [
            self::EXPIRED->value => 'Rezervasyon süresi doldu ve geçersiz hale geldi.',
            self::PENDING->value => 'Rezervasyon oluşturuldu, ancak henüz onaylanmadı.',
            self::CONFIRMED->value => 'Rezervasyon onaylandı ve bilet oluşturuldu.',
            self::USED->value => 'Rezervasyon onaylandı ve iptal edilemez..',
            self::CANCELLED->value => 'Rezervasyon iptal edildi.',
        ];
    }
}
