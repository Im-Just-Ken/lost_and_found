<?php

namespace App\Enums;

use App\Enums\Contracts\ExportableEnum;

enum ItemStatus:  int implements ExportableEnum
{


case LOST = 0;              // User reported lost
case FOUND_PENDING = 1;     // Someone claims they found it (awaiting admin)
case FOUND = 2;             // Approved by admin
case MATCHED = 3;           // Linked to a lost item
case CLAIMED = 4;           // Owner confirmed
case ARCHIVED = 5;          // Closed

    public function label(): string
    {
        return match ($this) {
            self::LOST => 'Lost',
            self::FOUND_PENDING => 'Found (Pending)',
            self::FOUND => 'Found',
            self::MATCHED => 'Matched',
            self::CLAIMED => 'Claimed',
            self::ARCHIVED => 'Archived',
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