<?php

namespace App\Enums;

use App\Enums\Contracts\ExportableEnum;

enum ItemMatchStatus:  int implements ExportableEnum
{
    case PENDING = 0;
    case CONFIRMED = 1;
    case REJECTED = 2;


    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::REJECTED => 'Rejected',
        };
    }

        public static function toArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->name => $case->value,
            ])
            ->toArray();
    }
}