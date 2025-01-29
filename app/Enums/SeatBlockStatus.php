<?php

namespace App\Enums;

enum SeatBlockStatus: string
{
    case EXPIRED = 'expired';
    case PENDING = 'pending';

    public static function getDescriptions(): array
    {
        return [
            self::EXPIRED->value => 'Koltuk bloklama süresi doldu, koltuk serbest durumda.',
            self::PENDING->value => 'Koltuk bir kullanıcı tarafından bloklandı, tekrar bloklanamaz.',
        ];
    }
}
