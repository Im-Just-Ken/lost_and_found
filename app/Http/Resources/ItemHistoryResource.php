<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'action_type' => [
                    'value' => $this->action_type->value,
                    'label' => $this->action_type->label(),
                    'key' => $this->action_type->name, 
                ],
            'notes' => $this->notes,
            'meta' => $this->meta,

            'created_at' => $this->created_at,
        ];
    }
}