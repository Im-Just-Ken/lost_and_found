<?php

namespace App\Http\Resources;

use App\Enums\ItemHistoryActionType;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportedItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
     
            'description' => $this->description,

            'location_text' => $this->location_text,
            'contact_number' => $this->contact_number,
            'comment' => $this->comment,
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

            'latest_history' => $this->whenLoaded('latestHistory', function () {
                return $this->latestHistory ? [
                    'id' => $this->latestHistory->id,

                    'action_type' => [
                        'value' => $this->latestHistory->action_type?->value,
                        'label' => $this->latestHistory->action_type?->label(),
                    ],

                    'notes' => $this->latestHistory->notes,

                    'user' => $this->latestHistory->user ? [
                        'id' => $this->latestHistory->user->id,
                        'name' => $this->latestHistory->user->name,
                        'email' => $this->latestHistory->user->email,
                    ] : null,

                    'created_at' => $this->latestHistory->created_at,
                ] : null;
            }),
            'found_report' => $this->whenLoaded('histories', function () {
            $history = $this->histories
                ->firstWhere('action_type',ItemHistoryActionType::MARKED_FOUND);

            if (! $history) {
                return null;
            }

            return [
                'id' => $history->id,

                'notes' => $history->notes,

                'user' => $history->user ? [
                    'id' => $history->user->id,
                    'name' => $history->user->name,
                    'email' => $history->user->email,
                ] : null,

                'created_at' => $history->created_at,
            ];
        }),
            'histories' => $this->whenLoaded('histories', function () {
                return $this->histories->map(function ($history) {
                    return [
                        'id' => $history->id,

                        'action_type' => [
                            'value' => $history->action_type?->value,
                            'label' => $history->action_type?->label(),
                        ],

                        'notes' => $history->notes,

                        'user' => $history->user ? [
                            'id' => $history->user->id,
                            'name' => $history->user->name,
                            'email' => $history->user->email,
                        ] : null,

                        'created_at' => $history->created_at,
                    ];
                })->values();
            }),
        ];
    }
}