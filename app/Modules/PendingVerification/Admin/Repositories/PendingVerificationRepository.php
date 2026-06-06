<?php

namespace App\Modules\PendingVerification\Admin\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;

class PendingVerificationRepository
{
       public function latest()
    {
        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
            ])

         
            ->where('status', ItemStatus::FOUND_PENDING)

          
            ->where('user_id', '!=', Auth::id())

            ->latest()
            ->get();
    }
}