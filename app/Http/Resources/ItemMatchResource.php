<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemMatchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'confidence_score' => $this->confidence_score,

             'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],


            'lost_item' => new ItemResource(
                $this->whenLoaded('lostItem')
            ),

            'found_item' => new ItemResource(
                $this->whenLoaded('foundItem')
            ),

            'created_at' => $this->created_at,
        ];
    }
}