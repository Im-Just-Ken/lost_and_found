<?php

namespace App\Modules\Items\Actions;

use App\Modules\Items\DTO\ItemData;
use App\Models\Shared\Item;


class UpdateItemAction
{
    public function execute(Item $item, ItemData $dto): Item
    {
        $item->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'contact_number' => $dto->contact_number,
            'location_text' => $dto->location_text,
            'date_lost' => $dto->date_lost,
        ]);

        return $item;
    }
}