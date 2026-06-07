<?php

namespace App\Services;

use App\Models\Shared\Item;
use App\Enums\ItemHistoryActionType;
use Closure;
use Illuminate\Support\Facades\Mail;

class FinderNotificationService
{
    public static function sendToLatestFinder(
        Item $item,
        Closure $mailFactory
    ): void {

        $finder = $item->histories()
            ->where(
                'action_type',
                ItemHistoryActionType::MARKED_FOUND
            )
            ->latest()
            ->first()?->user;

        if (! $finder) {
            return;
        }

        $recipient = config('admin_emails.use_local_admin_email')
            ? config('admin_emails.admin_email')
            : $finder->email;

        Mail::to($recipient)
            ->send(
                $mailFactory($finder)
            );
    }
}