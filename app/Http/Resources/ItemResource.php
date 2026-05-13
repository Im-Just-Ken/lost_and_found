<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'latest_history' => $this->whenLoaded('latestHistory', function () {
                return [
                    'id' => $this->latestHistory?->id,
                    'action_type' => [
                        'value' => $this->latestHistory?->action_type?->value,
                        'label' => $this->latestHistory?->action_type?->label(),
                    ],
                    'user_id' => $this->latestHistory?->user_id,
                    'notes' => $this->latestHistory?->notes,
                    'created_at' => $this->latestHistory?->created_at,
                ];
            }),

            'title' => $this->title,
            'description' => $this->description,
            'contact_number' => $this->contact_number,

            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'key' => $this->status->name,
            ],

            'date_lost' => $this->date_lost,
            'location_text' => $this->location_text,
            'resolved_at' => $this->resolved_at,
            'deleted_at' => $this->deleted_at,

            /* ======================
                RELATIONSHIPS
            ====================== */

            'primary_image' => new ItemImageResource(
                $this->whenLoaded('primaryImage')
            ),

            'images' => ItemImageResource::collection(
                $this->whenLoaded('images')
            )->resolve(),

            'histories' => ItemHistoryResource::collection(
                $this->whenLoaded('histories')
            ),

            'matches_as_lost' => ItemMatchResource::collection(
                $this->whenLoaded('lostMatches')
            ),

            'matches_as_found' => ItemMatchResource::collection(
                $this->whenLoaded('foundMatches')
            ),

            /* ======================
                META
            ====================== */

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}