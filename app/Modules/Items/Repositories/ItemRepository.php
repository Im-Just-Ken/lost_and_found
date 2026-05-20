<?php

namespace App\Modules\Items\Repositories;

use App\Models\Shared\Item;
use App\Enums\ItemStatus;

class ItemRepository
{
    public function create(array $data): Item
    {
        return Item::create($data);
    }
    public function latestForUser(int $userId)
{
    return Item::query()
        ->where('user_id', $userId)
        ->with(['images'])
        ->latest()
        ->get();
}

public function getClaimedItemsForUser(int $userId)
{
    return Item::query()
        ->where('user_id', $userId)
        ->where('status', ItemStatus::CLAIMED)
        ->with(['images'])
        ->latest()
        ->get();
}

public function getMissingItemsForUser(int $userId)
{
    return Item::query()
        ->where('user_id', $userId)
        ->whereIn('status', [
            ItemStatus::LOST->value,
            ItemStatus::FOUND_PENDING->value,
            ItemStatus::FOUND->value,
        ])
        ->with(['images'])
        ->latest()
        ->get();
}
}