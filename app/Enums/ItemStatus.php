<?php

namespace App\Enums;

use App\Enums\Contracts\ExportableEnum;

enum ItemStatus:  int implements ExportableEnum
{


case LOST = 0;              // User reported lost
case FOUND_PENDING = 1;     // Someone claims they found it (awaiting admin)
case FOUND = 2;             // Approved by admin
case CLAIMED = 3;           // Owner confirmed


    public function label(): string
    {
        return match ($this) {
            self::LOST => 'Lost',
            self::FOUND_PENDING => 'Found (For Review)',
            self::FOUND => 'Found',
            self::CLAIMED => 'Claimed',
  
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