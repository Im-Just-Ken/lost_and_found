<?php

namespace App\Modules\FoundByMe\Member\Repositories;

use App\Enums\ItemHistoryActionType;
use App\Models\Shared\Item;
use Illuminate\Support\Facades\Auth;

class FoundByMeRepository
{


  public function latest()
    {
        $userId = Auth::id();

        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
                'histories',
            ])
            ->get()
            ->filter(function ($item) use ($userId) {

                // 1. Get latest FOUND report
                $latestFound = $item->histories
                    ->where('action_type', ItemHistoryActionType::MARKED_FOUND)
                    ->sortByDesc('id')
                    ->first();

                if (!$latestFound) {
                    return false;
                }

                // 2. Must belong to current user
                if ($latestFound->user_id !== $userId) {
                    return false;
                }

                // 3. Check if THIS found report was rejected
                $wasRejected = $item->histories
                    ->where('action_type', ItemHistoryActionType::FOUND_REJECTED)
                    ->firstWhere('meta.finder_history_id', $latestFound->id);

                if ($wasRejected) {
                    return false;
                }

                return true;
            })
            ->values();
    }
}