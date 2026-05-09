<?php
namespace App\Modules\Items\Actions;

use App\Enums\ItemHistoryActionType;
use App\Models\Shared\ItemHistory;
use App\Models\Shared\Item;


class CreateItemHistoryAction
{
    public function created(Item $item, $dto): void
    {
        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => $dto->user_id,
            'action_type' => ItemHistoryActionType::CREATED,
            'meta' => [
                'type' => $dto->type,
            ],
        ]);
    }
      public function updated(Item $item, $dto): void
    {
        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => $dto->user_id,
            'action_type' => ItemHistoryActionType::UPDATED,
            'meta' => [
                // For simplicity, we log all fields. In a real app, you might want to only log changed fields.
                'updated_fields' => [
                    'title' => $dto->title,
                    'description' => $dto->description,
                    'location_text' => $dto->location_text,
                    'date_lost' => $dto->date_lost,
                ],
            ],
        ]);
    }
}