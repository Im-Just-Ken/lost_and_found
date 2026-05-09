<?php

namespace App\Enums;

use App\Enums\Contracts\ExportableEnum;

enum ItemHistoryActionType:  int implements ExportableEnum
{
    case CREATED = 0;
    case UPDATED = 1;
    case IMAGE_ADDED = 2;
    case STATUS_CHANGED = 3;
    case MATCHED = 4;
    case RESOLVED = 5;

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::UPDATED => 'Updated',
            self::IMAGE_ADDED => 'Image Added',
            self::STATUS_CHANGED => 'Status Changed',
            self::MATCHED => 'Matched',
            self::RESOLVED => 'Resolved',
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