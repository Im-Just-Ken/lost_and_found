<?php

namespace App\Modules\VerifiedFound\Admin\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;

class VerifiedFoundRepository
{
       public function latest()
    {
        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
            ])

         
            ->where('status', ItemStatus::FOUND)

          
            ->where('user_id', '!=', Auth::id())

            ->latest()
            ->get();
    }
}