<?php

namespace App\Modules\Items\Actions;

use App\Modules\Items\DTO\ItemData;
use App\Models\Shared\ItemHistory;
use App\Models\Shared\Item;
use App\Modules\Items\Repositories\ItemRepository;
use App\Enums\ItemHistoryActionType;
use App\Enums\ItemStatus;

class CreateItemAction
{
    public function __construct(
        protected ItemRepository $repo
    ) {}

    public function execute(ItemData $dto):Item
    {
        $item = $this->repo->create([
            'user_id' => $dto->user_id,
            'type' => $dto->type,
            'title' => $dto->title,
            'description' => $dto->description,
            'contact_number' => $dto->contact_number,
            'status' => ItemStatus::LOST,
            'date_lost' => $dto->date_lost,
            'location_text' => $dto->location_text,
   
        ]);

        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => $dto->user_id,
            'action_type' => ItemHistoryActionType::CREATED,
            'meta' => [
                'type' => $dto->type
            ]
        ]);

        return $item;
    }
}