<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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

            'permission_group_id' => (string) $this->permission_group_id,

            'role_group' => $this->whenLoaded('roleGroup', fn () => [
                'id' => $this->roleGroup->id,
                'name' => $this->roleGroup->name,
            ]),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}