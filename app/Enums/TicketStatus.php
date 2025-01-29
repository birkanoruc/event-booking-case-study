<?php

namespace App\Enums;

enum TicketStatus: string
{
    case VALID = 'valid';
    case USED = 'used';
    case CANCELLED = 'cancelled';

    public static function getDescriptions(): array
    {
        return [
            self::VALID->value => 'AKTİF.',
            self::USED->value => 'TRANSFER EDİLEMEZ',
            self::CANCELLED->value => 'İPTAL',
        ];
    }
}
