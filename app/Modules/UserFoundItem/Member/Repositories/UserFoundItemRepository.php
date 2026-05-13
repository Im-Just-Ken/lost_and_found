<?php

namespace App\Modules\UserFoundItem\Member\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;

class UserFoundItemRepository
{
 public function latest()
    {
       return Item::query()
    ->with(['images', 'primaryImage', 'user', 'latestHistory'])
    ->whereHas('latestHistory', function ($q) {
        $q->where('user_id', Auth::id())
          ->where('action_type', ItemHistoryActionType::MARKED_FOUND);
    })
    ->latest()
    ->get();
    }
}