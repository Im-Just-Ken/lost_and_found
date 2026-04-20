<?php

namespace App\Modules\Permission\DTO;
use App\Enums\PermissionStatus;

class PermissionData
{
    public function __construct(
        public readonly string $name,
        public readonly int $permission_group_id,
          public readonly PermissionStatus $status, 
    ) {}

public static function fromArray(array $data): self
{
    return new self(
        name: $data['name'],
        permission_group_id: (int) $data['permission_group_id'],
        status: isset($data['status'])
            ? PermissionStatus::from($data['status'])
            : PermissionStatus::ACTIVE,
    );
}
}