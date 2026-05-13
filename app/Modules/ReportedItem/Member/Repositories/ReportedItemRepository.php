<?php

namespace App\Modules\ReportedItem\Member\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;

class ReportedItemRepository
{
       public function latest()
    {
        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
            ])

            /**
             * Only lost items
             */
            ->where('status', ItemStatus::LOST)

            /**
             * Hide current user's own items
             */
            ->where('user_id', '!=', Auth::id())

            ->latest()
            ->get();
    }
}