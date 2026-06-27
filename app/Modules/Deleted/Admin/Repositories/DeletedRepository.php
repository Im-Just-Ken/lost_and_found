<?php

namespace App\Modules\Deleted\Admin\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;

class DeletedRepository
{
       public function latest()
    {
        return Item::query()
            ->with([
                'images',
                'primaryImage',
                'user',
            ])

         
            ->where('status', ItemStatus::DELETED)

          
            ->where('user_id', '!=', Auth::id())

            ->latest()
            ->get();
    }
}