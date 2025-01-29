<?php

namespace App\Enums;

enum SeatStatus: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case BLOCKED = 'blocked';
    case SOLD = 'sold';

    public static function getDescriptions(): array
    {
        return [
            self::AVAILABLE->value => 'SERBEST',
            self::RESERVED->value => 'REZERVE.',
            self::BLOCKED->value => 'BLOKE.',
            self::SOLD->value => 'SATILDI',
        ];
    }
}
