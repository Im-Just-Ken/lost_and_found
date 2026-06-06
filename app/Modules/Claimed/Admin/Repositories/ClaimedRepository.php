<?php

namespace App\Modules\Claimed\Admin\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;

class ClaimedRepository
{
       public function latest()
    {
        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
            ])

         
            ->where('status', ItemStatus::CLAIMED)

          
            ->where('user_id', '!=', Auth::id())

            ->latest()
            ->get();
    }
}