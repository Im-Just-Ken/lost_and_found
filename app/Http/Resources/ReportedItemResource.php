<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportedItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,
     
            'description' => $this->description,

            'location_text' => $this->location_text,
            'contact_number' => $this->contact_number,

            'date_lost' => $this->date_lost,

            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'key' => $this->status->name,
            ],

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'contact_number' => $this->user->contact_number,
                ];
            }),

            'primary_image' => new ItemImageResource(
                $this->whenLoaded('primaryImage')
            ),

            'images' => ItemImageResource::collection(
                $this->whenLoaded('images')
            )->resolve(),

            'created_at' => $this->created_at,

            'resolved_at' => $this->resolved_at,
        ];
    }
}