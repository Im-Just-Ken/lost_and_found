<?php

namespace App\Enums;

use App\Enums\Contracts\ExportableEnum;

enum ItemHistoryActionType: int implements ExportableEnum
{
    /**
     * ITEM LIFECYCLE
     */
    case CREATED = 0;
    case UPDATED = 1;
    case DELETED = 2;
    case RESTORED = 3;
    case ARCHIVED = 4;

    /**
     * IMAGE EVENTS
     */
    case IMAGE_ADDED = 5;
    case IMAGE_REMOVED = 6;
    case PRIMARY_IMAGE_CHANGED = 7;

    /**
     * STATUS / WORKFLOW
     */
    case STATUS_CHANGED = 8;

    case MARKED_FOUND = 9;
    case FOUND_APPROVED = 10;
    case FOUND_REJECTED = 11;

    case MATCHED = 12;
    case MATCH_REMOVED = 13;

    case CLAIMED = 14;
   

    /**
     * REPORTING / MODERATION
     */
    case REPORTED = 15;
    case REVIEWED_BY_ADMIN = 16;

    /**
     * CONTACT / ACTIVITY
     */
    case CONTACTED_OWNER = 17;
    case CONTACTED_FINDER = 18;

    /**
     * LABELS
     */
    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::UPDATED => 'Updated',
            self::DELETED => 'Deleted',
            self::RESTORED => 'Restored',
            self::ARCHIVED => 'Archived',

            self::IMAGE_ADDED => 'Image Added',
            self::IMAGE_REMOVED => 'Image Removed',
            self::PRIMARY_IMAGE_CHANGED => 'Primary Image Changed',

            self::STATUS_CHANGED => 'Status Changed',

            self::MARKED_FOUND => 'Marked as Found',
            self::FOUND_APPROVED => 'Found Approved',
            self::FOUND_REJECTED => 'Found Rejected',

            self::MATCHED => 'Matched',
            self::MATCH_REMOVED => 'Match Removed',

            self::CLAIMED => 'Claimed',
           

            self::REPORTED => 'Reported',
            self::REVIEWED_BY_ADMIN => 'Reviewed by Admin',

            self::CONTACTED_OWNER => 'Contacted Owner',
            self::CONTACTED_FINDER => 'Contacted Finder',
        };
    }

    /**
     * EXPORT ARRAY
     */
    public static function toArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->name => $case->value,
            ])
            ->toArray();
    }
}