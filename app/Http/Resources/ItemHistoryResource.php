<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ItemHistoryActionType;

class ItemHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
              'id' => $this->id,
            'item_id' => $this->item_id,
            'user_id' => $this->user_id,
            'action_type' => [
                    'value' => $this->action_type->value,
                    'label' => $this->action_type->label(),
                    'key' => $this->action_type->name, 
                ],

            'item' => $this->whenLoaded('item', function () {
                return [
                    'id' => $this->item->id,
                    'title' => $this->item->title,
                ];
            }),

          
        'user' => [
            'id' => $this->user_id,
            'name' => match ($this->action_type) {
                ItemHistoryActionType::FOUND_APPROVED,
                ItemHistoryActionType::FOUND_REJECTED,
                ItemHistoryActionType::CLAIMED,
                ItemHistoryActionType::FOUND_REVERTED_TO_PENDING,
                ItemHistoryActionType::CLAIMED_REVERTED_TO_PENDING,
                    => 'Admin',

                default => $this->user?->name,
            },
        ],
            'notes' => $this->notes,
            'meta' => $this->meta,

            'created_at' => $this->created_at,
        ];
    }
}