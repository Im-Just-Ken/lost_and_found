<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => [
                    'value' => $this->status->value,
                    'label' => $this->status->label(),
                    'key' => $this->status->name, 
                ],
            'group_id' => (string) $this->group_id,
            'group' => $this->whenLoaded('Group', fn () => [
                'id' => $this->Group->id,
                'name' => $this->Group->name,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}