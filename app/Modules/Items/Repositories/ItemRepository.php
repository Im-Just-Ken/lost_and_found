<?php

namespace App\Modules\Items\Repositories;

use App\Models\Shared\Item;

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
}