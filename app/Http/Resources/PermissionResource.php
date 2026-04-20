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

            'permission_group_id' => (string) $this->permission_group_id,

            'permission_group' => $this->whenLoaded('permissionGroup', fn () => [
                'id' => $this->permissionGroup->id,
                'name' => $this->permissionGroup->name,
            ]),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}