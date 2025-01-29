<?php

namespace App\Enums;

enum EventStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CANCELLED = 'cancelled';
    case CLOSER = 'closer';
    case COMPLETED = 'completed';

    public static function getDescriptions(): array
    {
        return [
            self::DRAFT->value => 'BAŞLAMADI',
            self::CLOSER->value => 'YAKLAŞTI',
            self::PUBLISHED->value => 'DEVAM EDİYOR',
            self::CANCELLED->value => 'İPTAL',
            self::COMPLETED->value => 'SONA ERDİ',
        ];
    }

    public static function getValuesForValidation(): string
    {
        return implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}
